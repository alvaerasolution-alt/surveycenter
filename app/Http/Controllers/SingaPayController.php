<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SingaPayController extends Controller
{
    protected $apiKey;
    protected $clientId;
    protected $clientSecret;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey       = config('singapay.api_key');
        $this->clientId     = config('singapay.client_id');
        $this->clientSecret = config('singapay.client_secret');
        $this->baseUrl      = config('singapay.base_url', 'https://sandbox-payment-b2b.singapay.id');
    }



    /**
     * Redirect user ke SingaPay untuk pembayaran QRIS/Payment URL
     */
    public function pay(Transaction $transaction)
    {
        $token = $this->getToken();
        if (!$token) {
            return back()->with('error', 'Gagal terhubung ke payment gateway.');
        }

        $customerName  = Auth::check() ? Auth::user()->name : 'Guest User';
        $customerPhone = Auth::check() && Auth::user()->phone ? Auth::user()->phone : '0000000000';

        $response = Http::withHeaders([
            'X-PARTNER-ID'  => $this->apiKey,
            'Authorization' => "Bearer {$token}",
            'Accept'        => 'application/json',
        ])->post("{$this->baseUrl}/api/v1/payment", [
            'command' => 'payment',
            'data' => [
                'reference_number' => $transaction->id,
                'amount'           => $transaction->amount,
                'customer_name'    => $customerName,
                'customer_phone'   => $customerPhone,
            ]
        ]);

        $data = $response->json();
        Log::info('SingaPay API Response', ['data' => $data]);

        if (!empty($data['payment_url'])) {
            return redirect()->away($data['payment_url']);
        }

        return back()->with('error', 'Gagal membuat transaksi pembayaran.');
    }

    /**
     * Callback endpoint dari SingaPay
     */
    public function callback(Request $request)
    {
        $data = $request->all();
        Log::info('SingaPay Callback', $data);

        $transaction = Transaction::find($data['reference_number'] ?? null);

        if ($transaction) {
            $incomingStatus = $data['status'] ?? 'unknown';
            $mappedStatus = match ($incomingStatus) {
                'paid', 'settlement', 'success' => Transaction::STATUS_PAID,
                'pending', 'unpaid', 'created' => Transaction::STATUS_PENDING,
                'processing' => Transaction::STATUS_PROCESSING,
                'failed', 'cancelled', 'expired' => Transaction::STATUS_FAILED,
                default => $incomingStatus,
            };

            $transaction->update([
                'status'       => $mappedStatus,
                'singapay_ref' => $data['transaction_id'] ?? null,
            ]);
        }

        return response()->json(['status' => 'ok']);
    }

    /**
     * Public method untuk generate QRIS agar bisa dipanggil dari TransactionController
     */
    public function generateQris(Transaction $transaction)
    {
        $token = $this->getToken();
        if (!$token) {
            return response()->json(['error' => 'Gagal ambil token'], 500);
        }

        // expired besok jam 23:59
        $expiredAt = now()->addDay()->setTime(23, 59)->format('Y-m-d H:i:s');

        $response = Http::withHeaders([
            'X-PARTNER-ID'  => $this->apiKey,
            'Authorization' => "Bearer {$token}",
            'Accept'        => 'application/json',
        ])->post("{$this->baseUrl}/api/v1.0/qris-dynamic/{$this->clientId}/generate-qr", [
            "amount"        => $transaction->amount,
            "expired_at"    => $expiredAt,
            "tip_indicator" => "fixed_amount",
            "tip_value"     => 1000
        ]);

        $data = $response->json();

        if ($response->successful() && isset($data['data']['qr_data'])) {
            // simpan qr_data ke transaksi
            $transaction->update([
                'singapay_ref' => $data['data']['reff_no'],
                'status'       => $data['data']['status'],
                'qr_data'      => $data['data']['qr_data'],
            ]);

            return view('transactions.qris', [
                'transaction' => $transaction,
                'qrString'    => $data['data']['qr_data'],
            ]);
        }

        return response()->json([
            'status' => $response->status(),
            'body'   => $response->body()
        ], $response->status());
    }

    public function generateQrisDebug(Transaction $transaction)
    {
        $token = $this->getToken();

        if (!$token) {
            Log::error('QRIS Debug: gagal ambil token');
            return response()->json(['error' => 'Gagal ambil token'], 500);
        }

        $response = Http::withHeaders([
            'X-PARTNER-ID' => $this->apiKey,
            'Authorization' => "Bearer {$token}",
            'Accept' => 'application/json'
        ])->post("{$this->baseUrl}/api/v1.0/qris-dynamic/{$this->clientId}/generate-qr", [
            'partnerReferenceNo' => $transaction->id,
            'amount' => [
                'value' => number_format($transaction->amount, 2, '.', ''),
                'currency' => 'IDR'
            ]
        ]);

        $data = $response->json();

        // Log raw response
        Log::info('QRIS Debug Response', [
            'status' => $response->status(),
            'body' => $response->body(),
            'json' => $data
        ]);

        // Kembalikan JSON ke browser untuk debug
        return response()->json($data);
    }

    public function generateGopay(Transaction $transaction)
    {
        $token = $this->getToken();
        if (!$token) return null;

        $response = Http::withHeaders([
            'X-PARTNER-ID' => $this->apiKey,
            'Authorization' => "Bearer {$token}",
        ])->post("{$this->baseUrl}/v1.0/gopay-dynamic/{$this->clientId}/generate-qr", [
            'partnerReferenceNo' => $transaction->id,
            'amount' => [
                'value' => number_format($transaction->amount, 2, '.', ''),
                'currency' => 'IDR'
            ]
        ]);

        $data = $response->json();
        return $data['data']['qr_data'] ?? null;
    }


    /**
     * Public method untuk generate Virtual Account
     */
    public function generateVa(Transaction $transaction, $bank)
    {
        $prefix = $bank === 'BCA' ? '1065' : '98888';
        return $prefix . $transaction->id;
    }

    /**
     * Ambil token SingaPay
     */
    private function getToken()
    {
        $response = Http::withHeaders([
            'X-PARTNER-ID' => $this->apiKey,
            'Accept'       => 'application/json',
            'Authorization' => 'Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret),
        ])->post("{$this->baseUrl}/api/v1.0/access-token/b2b", [
            'grant_type' => 'client_credentials'
        ]);


        Log::info('Token Response Debug', [
            'status' => $response->status(),
            'body' => $response->body()
        ]);

        if ($response->successful()) {
            return $response->json('access_token');
        }

        Log::error('Gagal request token SingaPay', ['response' => $response->body()]);
        return null;
    }
}
