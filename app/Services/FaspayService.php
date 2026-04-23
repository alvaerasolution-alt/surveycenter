<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FaspayService
{
    protected string $merchantId;
    protected string $userId;
    protected string $password;
    protected string $apiKey;
    protected string $environment;
    protected string $baseUrl;
    protected string $paymentUrl;
    protected bool $loggingEnabled;

    public function __construct()
    {
        $this->merchantId = (string) config('faspay.merchant_id');
        $this->userId = (string) config('faspay.user_id');
        $this->password = (string) config('faspay.password');
        $this->apiKey = (string) config('faspay.api_key', '');
        $this->environment = (string) config('faspay.environment', 'sandbox');
        $this->loggingEnabled = (bool) config('faspay.logging.enabled', true);

        $endpoints = config('faspay.endpoints', []);
        $env = $endpoints[$this->environment] ?? ($endpoints['sandbox'] ?? []);

        $this->baseUrl = (string) ($env['base_url'] ?? '');
        $this->paymentUrl = (string) ($env['payment_url'] ?? '');

        $this->validateConfiguration();
    }

    private function validateConfiguration(): void
    {
        $required = [
            'merchantId' => 'FASPAY_MERCHANT_ID',
            'userId' => 'FASPAY_USER_ID',
            'password' => 'FASPAY_PASSWORD',
        ];

        foreach ($required as $property => $envKey) {
            if (!empty($this->{$property})) {
                continue;
            }

            if (app()->isLocal()) {
                Log::error("Faspay configuration error: {$envKey} is not set in .env");
                continue;
            }

            throw new \RuntimeException("Faspay configuration error: {$envKey} is not set in .env");
        }
    }

    public function generateSignature(string $billNo, string $paymentStatusCode): string
    {
        return sha1(md5($this->userId . $this->password . $billNo . $paymentStatusCode));
    }

    public function validateNotificationSignature(array $data): bool
    {
        $billNo = (string) ($data['bill_no'] ?? '');
        $paymentStatusCode = (string) ($data['payment_status_code'] ?? '');
        $receivedSignature = (string) ($data['signature'] ?? '');

        if ($billNo === '' || $paymentStatusCode === '' || $receivedSignature === '') {
            return false;
        }

        $expectedSignature = $this->generateSignature($billNo, $paymentStatusCode);
        $isValid = hash_equals($expectedSignature, $receivedSignature);

        if (!$isValid && $this->loggingEnabled) {
            Log::warning('Faspay signature validation failed', [
                'expected' => $expectedSignature,
                'received' => $receivedSignature,
                'bill_no' => $billNo,
                'payment_status_code' => $paymentStatusCode,
            ]);
        }

        return $isValid;
    }

    public function createInvoice(array $data): array
    {
        try {
            $billNo = (string) ($data['bill_no'] ?? ('BILL-' . time()));
            $rawBillTotal = (float) ($data['bill_total'] ?? 0);
            $normalizedBillTotal = (string) max(0, (int) round($rawBillTotal));

            $billDescription = (string) ($data['bill_desc'] ?? $data['bill_description'] ?? 'Payment for transaction');
            $phone = (string) ($data['msisdn'] ?? $data['cust_phone'] ?? '081234567890');
            $email = (string) ($data['email'] ?? $data['cust_email'] ?? 'customer@example.com');
            $billDate = (string) ($data['bill_date'] ?? now()->format('Y-m-d H:i:s'));

            $billExpiredInput = $data['bill_expired']
                ?? $data['bill_expired_date']
                ?? now()->addDay()->format('Y-m-d H:i:s');

            try {
                $billExpiredAt = Carbon::parse((string) $billExpiredInput);
            } catch (\Throwable $e) {
                $billExpiredAt = now()->addDay();
            }

            if ($billExpiredAt->isPast() || $billExpiredAt->isSameDay(now())) {
                $billExpiredAt = now()->addDay();
            }

            $invoiceData = [
                'merchant_id' => $this->merchantId,
                'merchant_user_id' => $this->userId,
                'bill_no' => $billNo,
                'bill_date' => $billDate,
                'bill_expired' => $billExpiredAt->format('Y-m-d H:i:s'),
                'bill_desc' => $billDescription,
                'bill_total' => $normalizedBillTotal,
                'cust_no' => (string) ($data['cust_no'] ?? (Auth::id() ?? 'GUEST')),
                'cust_name' => (string) ($data['cust_name'] ?? 'Customer'),
                'return_url' => (string) ($data['return_url'] ?? config('faspay.webhook_urls.return')),
                'msisdn' => $phone,
                'email' => $email,
                'item' => $data['item'] ?? [[
                    'product' => substr($billDescription, 0, 50),
                    'qty' => '1',
                    'amount' => $normalizedBillTotal,
                ]],
                'merchant_logo' => (string) ($data['merchant_logo'] ?? 'https://rumayakos.com/logo.png'),
                'signature' => $this->generateInvoiceSignature($this->userId, $billNo, $normalizedBillTotal),
            ];

            if ($this->loggingEnabled) {
                Log::info('Creating Faspay invoice', [
                    'bill_no' => $invoiceData['bill_no'],
                    'bill_total' => $invoiceData['bill_total'],
                    'endpoint' => $this->paymentUrl,
                ]);
            }

            $request = Http::timeout(30)->retry(2, 300, null, false);

            if ($this->environment === 'sandbox' || (bool) env('FASPAY_SKIP_SSL', false)) {
                $request = $request->withoutVerifying();
            }

            $apiReq = $request->post($this->paymentUrl, $invoiceData);
            $responseBody = $apiReq->body();
            $response = $apiReq->json() ?? [];

            if (($response['response_desc'] ?? '') === 'invalid signature') {
                $invoiceData['signature'] = $this->generateInvoiceSignature($this->merchantId, $billNo, $normalizedBillTotal);
                $apiReq = $request->post($this->paymentUrl, $invoiceData);
                $responseBody = $apiReq->body();
                $response = $apiReq->json() ?? [];
            }

            if ($this->loggingEnabled) {
                Log::info('Faspay invoice response', [
                    'status' => $apiReq->status(),
                    'body' => $responseBody,
                    'parsed' => $response,
                ]);
            }

            $paymentUrl = $response['redirect_url'] ?? $response['payment_url'] ?? null;
            $isSuccess = ($response['response_code'] ?? null) === '00';
            $message = $response['response_desc'] ?? null;

            if (!$isSuccess && empty($message) && !$apiReq->successful()) {
                $message = 'Faspay gateway error HTTP ' . $apiReq->status();
            }

            return [
                'success' => $isSuccess,
                'data' => $response,
                'payment_url' => $paymentUrl,
                'trx_id' => $response['trx_id'] ?? null,
                'message' => $message ?? ($isSuccess ? 'Success' : 'Invoice creation failed'),
            ];
        } catch (ConnectionException|RequestException $e) {
            if ($this->loggingEnabled) {
                Log::error('Faspay invoice request error', [
                    'error' => $e->getMessage(),
                    'bill_no' => $data['bill_no'] ?? 'unknown',
                ]);
            }

            return [
                'success' => false,
                'data' => [],
                'payment_url' => null,
                'trx_id' => null,
                'message' => 'Faspay gateway sedang bermasalah (HTTP 502/timeout). Coba lagi beberapa menit.',
            ];
        } catch (\Throwable $e) {
            if ($this->loggingEnabled) {
                Log::error('Faspay invoice creation failed', [
                    'error' => $e->getMessage(),
                    'bill_no' => $data['bill_no'] ?? 'unknown',
                ]);
            }

            return [
                'success' => false,
                'data' => [],
                'payment_url' => null,
                'trx_id' => null,
                'message' => 'Invoice creation failed',
            ];
        }
    }

    public function getPaymentStatus(string $billNo): array
    {
        try {
            $request = Http::timeout(30);

            if ($this->environment === 'sandbox') {
                $request = $request->withoutVerifying();
            }

            $response = $request
                ->post("{$this->baseUrl}/api/queryStatus", [
                    'merchant_id' => $this->merchantId,
                    'bill_no' => $billNo,
                    'user_id' => $this->userId,
                    'password' => $this->password,
                ])
                ->json();

            return [
                'success' => isset($response['status']) && ($response['status'] == '0' || $response['status'] === true),
                'data' => $response,
            ];
        } catch (\Throwable $e) {
            if ($this->loggingEnabled) {
                Log::error('Faspay status query failed', [
                    'error' => $e->getMessage(),
                    'bill_no' => $billNo,
                ]);
            }

            throw $e;
        }
    }

    public function handleNotification(array $data): array
    {
        if (!$this->validateNotificationSignature($data)) {
            return [
                'success' => false,
                'error' => 'Invalid signature',
                'response_code' => '99',
                'response_desc' => 'Signature validation failed',
            ];
        }

        $statusMap = [
            '0' => 'unpaid',
            '1' => 'processing',
            '2' => 'paid',
            '3' => 'failed',
            '4' => 'reversed',
            '5' => 'bill_not_found',
            '7' => 'expired',
            '8' => 'cancelled',
            '9' => 'unknown',
        ];

        $paymentStatusCode = (string) ($data['payment_status_code'] ?? '9');

        return [
            'success' => true,
            'trx_id' => $data['trx_id'] ?? null,
            'bill_no' => $data['bill_no'] ?? null,
            'payment_status' => $statusMap[$paymentStatusCode] ?? 'unknown',
            'payment_status_code' => $paymentStatusCode,
            'payment_date' => $data['payment_date'] ?? null,
            'payment_channel' => $data['payment_channel'] ?? null,
            'payment_total' => $data['payment_total'] ?? null,
            'response_code' => '00',
            'response_desc' => 'Success',
        ];
    }

    public function generatePaymentLink(string $billNo): string
    {
        return "{$this->paymentUrl}bill_no={$billNo}";
    }

    public function isConfigured(): bool
    {
        return !empty($this->merchantId) && !empty($this->userId) && !empty($this->password);
    }

    public function getSupportedChannels(): array
    {
        return config('faspay.supported_channels', []);
    }

    public function getPaymentChannels(): array
    {
        return config('faspay.payment_channels', []);
    }

    private function generateInvoiceSignature(string $identifier, string $billNo, string $billTotal): string
    {
        return sha1(md5($identifier . $this->password . $billNo . $billTotal));
    }
}
