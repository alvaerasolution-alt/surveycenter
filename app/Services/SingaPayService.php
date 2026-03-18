<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;

class SingaPayService
{
    private string $clientId;
    private string $clientSecret;
    private string $apiKey;
    private string $baseUrl;
    private string $accountId;

    public function __construct()
    {
        $this->clientId = config('singapay.client_id');
        $this->clientSecret = config('singapay.client_secret');
        $this->apiKey = config('singapay.api_key');
        $this->accountId = config('singapay.account_id');
        $this->baseUrl = 'https://payment-b2b.singapay.id';
    }

    public function getListMethod()
    {
        $tokenData = $this->getToken('1.1');

        if (!$tokenData['success']) {
            return response()->json(['message' => $tokenData['message']], 500);
        }
        $token = $tokenData['token'];

        $endpoint = "{$this->baseUrl}/api/v1.0/payment-link-manage/payment-methods";

        $headers = [
            'X-PARTNER-ID' => $this->apiKey,
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$token}",
        ];

        try {
            $response = Http::withHeaders($headers)
                ->acceptJson()
                ->get($endpoint)
                ->throw()
                ->json();

            return response()->json($response);
        } catch (RequestException $e) {
            return $e->getMessage();
        }
    }

    public function createInvoice($amount, $items)
    {
        $tokenData = $this->getToken('1.1');

        if (!$tokenData['success']) {
            return ['success' => false, 'message' => $tokenData['message']];
        }
        $token = $tokenData['token'];

        $endpoint = "{$this->baseUrl}/api/v1.0/payment-link-manage/{$this->accountId}";

        $headers = [
            'X-PARTNER-ID' => $this->apiKey,
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$token}",
        ];

        $reffNo = 'RC' . strtoupper(uniqid());
        $expiredAt = now()
            ->addMinutes(30)
            ->getTimestampMs();

        $payload = [
            'reff_no' => $reffNo,
            'title' => "Pembayaran Transaksi #{$reffNo}",
            'required_customer_detail' => false,
            'max_usage' => 1,
            'expired_at' => $expiredAt,
            'total_amount' => $amount,
            'items' => $items,
            'whitelisted_payment_method' => [
                'QRIS',
                'VA_BCA',
                'VA_BNI',
                'VA_BRI',
                'VA_DANAMON',
                'VA_MAYBANK',
            ],
        ];

        try {
            $response = Http::withHeaders($headers)
                ->acceptJson()
                ->post($endpoint, $payload);

            if ($response->failed()) {
                Log::error('SingaPay Create Invoice Failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return ['success' => false, 'message' => 'Failed to create invoice: ' . $response->body()];
            }

            $data = $response->json();
            return array_merge(['success' => true], $data['data']);
        } catch (\Exception $e) {
            Log::error('SingaPay Create Invoice Exception', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function webhook(Request $request)
    {
        $payload = $request->all();

        if (!isset($payload['status'], $payload['success'])) {
            return [
                'received' => false,
                'handled' => false,
                'reason' => 'invalid_payload',
            ];
        }

        if (!($payload['success'] === true || $payload['success'] === 1 || $payload['success'] === 'true')) {
            return [
                'received' => true,
                'handled' => false,
            ];
        }

        $reffNo = data_get($payload, 'data.payment.additional_info.payment_link.reff_no');

        if (!$reffNo) {
            return [
                'received' => true,
                'handled' => false,
            ];
        }

        $order = Transaction::where('singapay_ref', $reffNo)->first();

        if (!$order) {
            return [
                'received' => true,
                'handled' => false,
            ];
        }

        $status = data_get($payload, 'data.transaction.status');

        if ($status === 'paid') {
            $order->status = 'paid';
            $order->payment_method = data_get($payload, 'data.payment.method');
            $order->save();
        }

        return [
            'received' => true,
            'handled' => true,
            'type' => data_get($payload, 'data.payment.method'),
        ];
    }

    private function generateSignature(string $timestamp): string
    {
        $payload = implode('_', [
            $this->clientId,
            $this->clientSecret,
            $timestamp,
        ]);

        return hash_hmac(
            'sha512',
            $payload,
            $this->clientSecret
        );
    }

    private function getToken(string $version): array
    {
        $payload = [
            'grant_type' => 'client_credentials',
        ];

        if ($version === '1.0') {
            $endpoint = "{$this->baseUrl}/api/v1.0/access-token/b2b";

            $headers = [
                'X-PARTNER-ID' => $this->apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret),
            ];
        } elseif ($version === '1.1') {
            $endpoint = "{$this->baseUrl}/api/v1.1/access-token/b2b";

            // Force timezone to Asia/Jakarta to match Singapay server expectation (WIB)
            $timestamp = now()->setTimezone('Asia/Jakarta')->format('Ymd');

            $signature = $this->generateSignature($timestamp);

            $headers = [
                'X-PARTNER-ID' => $this->apiKey,
                'X-CLIENT-ID' => $this->clientId,
                'X-Signature' => $signature,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ];
        } else {
            return ['success' => false, 'message' => 'Invalid API version'];
        }

        try {
            $response = Http::withHeaders($headers)
                ->acceptJson()
                ->post($endpoint, $payload);

            if ($response->failed()) {
                Log::error('SingaPay Get Token Failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return ['success' => false, 'message' => 'Token Error: ' . $response->body()];
            }

            return ['success' => true, 'token' => $response->json()['data']['access_token']];
        } catch (\Exception $e) {
            Log::error('SingaPay Get Token Exception', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => 'Token Exception: ' . $e->getMessage()];
        }
    }
}
