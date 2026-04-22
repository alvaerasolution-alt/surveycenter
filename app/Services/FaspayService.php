<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

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

        $endpoints = config('faspay.endpoints');
        $env = $endpoints[$this->environment] ?? $endpoints['sandbox'];
        $this->baseUrl = $env['base_url'];
        $this->paymentUrl = $env['payment_url'];

        $this->validateConfiguration();
    }

    /**
     * Validate Faspay configuration
     */
    private function validateConfiguration(): void
    {
        $required = ['merchantId' => 'FASPAY_MERCHANT_ID', 'userId' => 'FASPAY_USER_ID', 'password' => 'FASPAY_PASSWORD'];

        foreach ($required as $property => $envKey) {
            if (empty($this->{$property})) {
                if (app()->isLocal()) {
                    Log::error("Faspay configuration error: {$envKey} is not set in .env");
                } else {
                    throw new \Exception("Faspay configuration error: {$envKey} is not set in .env");
                }
            }
        }
    }

    /**
     * Generate signature for payment notification validation
     * Signature: sha1(md5(user_id + password + bill_no + payment_status_code))
     */
    public function generateSignature(string $billNo, string $paymentStatusCode): string
    {
        $hash = md5($this->userId . $this->password . $billNo . $paymentStatusCode);
        return sha1($hash);
    }

    /**
     * Validate payment notification signature
     */
    public function validateNotificationSignature(array $data): bool
    {
        $expectedSignature = $this->generateSignature($data['bill_no'], (string) $data['payment_status_code']);
        $receivedSignature = $data['signature'] ?? null;

        $isValid = hash_equals($expectedSignature, $receivedSignature ?? '');

        if (!$isValid && $this->loggingEnabled) {
            Log::warning('Faspay signature validation failed', [
                'expected' => $expectedSignature,
                'received' => $receivedSignature,
                'bill_no' => $data['bill_no'],
                'payment_status_code' => $data['payment_status_code'],
            ]);
        }

        return $isValid;
    }

    /**
     * Create payment invoice/transaction in Faspay
     */
    public function createInvoice(array $data): array
    {
        try {
            $billNo = $data['bill_no'] ?? 'BILL-' . time();
            $billTotal = (string) $data['bill_total'];

            $signatureHash = md5($this->userId . $this->password . $billNo . $billTotal);
            $signature = sha1($signatureHash);

            $billDescription = $data['bill_desc'] ?? $data['bill_description'] ?? 'Payment for transaction';
            $phone = $data['msisdn'] ?? $data['cust_phone'] ?? '081234567890';
            $email = $data['email'] ?? $data['cust_email'] ?? 'customer@example.com';

            $invoiceData = [
                'merchant_id' => $this->merchantId,
                'bill_no' => $billNo,
                'bill_date' => $data['bill_date'] ?? now()->format('Y-m-d H:i:s'),
                'bill_expired' => $data['bill_expired']
                    ?? $data['bill_expired_date']
                    ?? now()->addDay()->format('Y-m-d H:i:s'),
                'bill_desc' => $billDescription,
                'bill_total' => $billTotal,
                'cust_no' => $data['cust_no'] ?? (Auth::id() ?? 'GUEST'),
                'cust_name' => $data['cust_name'] ?? 'Customer',
                'return_url' => $data['return_url'] ?? config('faspay.webhook_urls.return'),
                'msisdn' => $phone,
                'email' => $email,
                'item' => $data['item'] ?? [[
                    'product' => $billDescription,
                    'qty' => '1',
                    'amount' => $billTotal,
                ]],
                'merchant_logo' => $data['merchant_logo'] ?? 'https://rumayakos.com/logo.png',
                'signature' => $signature,
            ];

            if ($this->loggingEnabled) {
                Log::info('Creating Faspay invoice', [
                    'bill_no'  => $invoiceData['bill_no'],
                    'endpoint' => $this->paymentUrl,
                ]);
            }

            $request = Http::timeout(30);
            if ($this->environment === 'sandbox' || env('FASPAY_SKIP_SSL', false)) {
                $request->withoutVerifying();
            }

            $apiReq = $request->post($this->paymentUrl, $invoiceData);
            $response = $apiReq->json() ?? [];

            if ($this->loggingEnabled) {
                Log::info('Faspay invoice response', [
                    'status' => $apiReq->status(),
                    'parsed' => $response
                ]);
            }

            $paymentUrl = $response['redirect_url'] ?? $response['payment_url'] ?? null;
            $isSuccess = ($response['response_code'] ?? null) === '00';

            return [
                'success' => $isSuccess,
                'data' => $response,
                'payment_url' => $paymentUrl,
                'trx_id' => $response['trx_id'] ?? null,
                'message' => $response['response_desc'] ?? ($isSuccess ? 'Success' : 'Invoice creation failed'),
            ];
        } catch (\Exception $e) {
            if ($this->loggingEnabled) {
                Log::error('Faspay invoice creation failed', [
                    'error'   => $e->getMessage(),
                    'bill_no' => $data['bill_no'] ?? 'unknown',
                ]);
            }

            throw $e;
        }
    }

    /**
     * Get payment status
     */
    public function getPaymentStatus(string $billNo): array
    {
        try {
            $request = Http::timeout(30);

            if ($this->environment === 'sandbox') {
                $request->withoutVerifying();
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
        } catch (\Exception $e) {
            if ($this->loggingEnabled) {
                Log::error('Faspay status query failed', [
                    'error' => $e->getMessage(),
                    'bill_no' => $billNo,
                ]);
            }

            throw $e;
        }
    }

    /**
     * Parse and validate webhook notification from Faspay
     */
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

        // Map payment status codes from Faspay
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

        return [
            'success' => true,
            'trx_id' => $data['trx_id'] ?? null,
            'bill_no' => $data['bill_no'] ?? null,
            'payment_status' => $statusMap[(string) ($data['payment_status_code'] ?? '9')] ?? 'unknown',
            'payment_status_code' => (string) ($data['payment_status_code'] ?? '9'),
            'payment_date' => $data['payment_date'] ?? null,
            'payment_channel' => $data['payment_channel'] ?? null,
            'payment_total' => $data['payment_total'] ?? null,
            'response_code' => '00',
            'response_desc' => 'Success',
        ];
    }

    /**
     * Generate payment link
     */
    public function generatePaymentLink(string $billNo): string
    {
        return "{$this->paymentUrl}bill_no={$billNo}";
    }

    /**
     * Check if Faspay is properly configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->merchantId) && !empty($this->userId) && !empty($this->password);
    }

    /**
     * Get supported payment channels
     */
    public function getSupportedChannels(): array
    {
        return config('faspay.supported_channels', []);
    }

    /**
     * Get payment channel settings
     */
    public function getPaymentChannels(): array
    {
        return config('faspay.payment_channels', []);
    }
}
