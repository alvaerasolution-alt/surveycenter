<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GenerateSingaPayQris extends Command
{
    protected $signature = 'generate:singapay-qris
                            {account_id}
                            {amount}
                            {--expired_at=}
                            {--tip_value=1000}';

    protected $description = 'Generate QRIS dynamic via SingaPay API with debug info';

    public function handle()
    {
        $accountId = $this->argument('account_id');
        $amount    = (int) $this->argument('amount');

        // Jika tidak ada expired_at, default besok jam 23:59
        $expiredAt = $this->option('expired_at') ??
            now('UTC')->addDay()->setTime(23, 59, 0)->format('Y-m-d H:i:s');

        $tipValue = (int) $this->option('tip_value');

        $apiKey       = config('singapay.api_key');
        $clientId     = config('singapay.client_id');
        $clientSecret = config('singapay.client_secret');
        $baseUrl      = config('singapay.base_url', 'https://sandbox-payment-b2b.singapay.id');

        // Step 1: Get Access Token
        $this->info("Requesting Access Token...");
        try {
            $tokenResponse = Http::withHeaders([
                'X-PARTNER-ID'  => $apiKey,
                'Accept'        => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($clientId . ':' . $clientSecret),
            ])->post($baseUrl . '/api/v1.0/access-token/b2b', [
                'grant_type' => 'client_credentials',
            ]);

            $accessToken = $tokenResponse->json('data.access_token');

            $this->info("Access Token: $accessToken");
            $this->line("Access Token Response: " . $tokenResponse->body());
        } catch (\Exception $e) {
            $this->error("Exception while getting token: " . $e->getMessage());
            return 1;
        }

        // Step 2: Generate QRIS
        $payload = [
            'amount'        => $amount,
            'expired_at'    => $expiredAt,
            'tip_indicator' => 'fixed_amount',
            'tip_value'     => $tipValue,
        ];

        $headers = [
            'X-PARTNER-ID'  => $apiKey,
            'Authorization' => 'Bearer ' . $accessToken,
            'Accept'        => 'application/json',
        ];

        $this->info("Generating QRIS for account_id: $accountId ...");
        $this->line("Request Headers: " . json_encode($headers, JSON_PRETTY_PRINT));
        $this->line("Request Body: " . json_encode($payload, JSON_PRETTY_PRINT));

        try {
            $response = Http::withHeaders($headers)
                ->post($baseUrl . "/api/v1.0/qris-dynamic/{$accountId}/generate-qr", $payload);

            $data = $response->json();

            $this->line("Response HTTP Status: " . $response->status());
            $this->line("Response Body: " . json_encode($data, JSON_PRETTY_PRINT));

            if ($response->successful() && isset($data['data'])) {
                $trx = $data['data'];
                $this->info("✅ QRIS generated successfully!");
                $this->line("Reference No : " . ($trx['reff_no'] ?? 'N/A'));
                $this->line("Amount       : " . ($trx['amount'] ?? 'N/A'));
                $this->line("Tip Amount   : " . ($trx['tip_amount'] ?? 'N/A'));
                $this->line("Total Amount : " . ($trx['total_amount'] ?? 'N/A'));
                $this->line("Status       : " . ($trx['status'] ?? 'N/A'));
                $this->line("QR Data      : " . ($trx['qr_data'] ?? 'N/A'));
                $this->line("Expired At   : " . ($trx['expired_at'] ?? 'N/A'));
                $this->line("Created At   : " . ($trx['created_at'] ?? 'N/A'));
            } else {
                $this->error("❌ Failed to generate QRIS: HTTP " . $response->status());
            }
        } catch (\Exception $e) {
            $this->error("Exception while generating QRIS: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
