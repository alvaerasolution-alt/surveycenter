<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ListSingaPayQris extends Command
{
    protected $signature = 'list:singapay-qris {account_id}';
    protected $description = 'List all QRIS dynamic transactions for a given account_id';

    public function handle()
    {
        $accountId = $this->argument('account_id');

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

            if ($tokenResponse->failed()) {
                $this->error("Failed to get token: HTTP " . $tokenResponse->status());
                $this->line($tokenResponse->body());
                return 1;
            }

            $accessToken = $tokenResponse->json('data.access_token');
            $this->info("Access Token: $accessToken");
        } catch (\Exception $e) {
            $this->error("Exception while getting token: " . $e->getMessage());
            Log::error('Exception getting SingaPay token', ['exception' => $e]);
            return 1;
        }

        // Step 2: List QRIS
        $this->info("Fetching QRIS list for account_id: $accountId ...");

        try {
            $response = Http::withHeaders([
                'X-PARTNER-ID'  => $apiKey,
                'Authorization' => 'Bearer ' . $accessToken,
                'Accept'        => 'application/json',
            ])->get($baseUrl . "/api/v1.0/qris-dynamic/{$accountId}");

            $data = $response->json();

            if ($response->successful() && isset($data['data'])) {
                $qrisList = $data['data'];

                if (empty($qrisList)) {
                    $this->info("No QRIS transactions found for account_id: $accountId");
                    return 0;
                }

                $this->info("✅ QRIS List:");
                foreach ($qrisList as $trx) {
                    $this->line("--------------------------------------------------");
                    $this->line("Reference No : " . ($trx['reff_no'] ?? 'N/A'));
                    $this->line("Amount       : " . ($trx['amount'] ?? 'N/A'));
                    $this->line("Tip Amount   : " . ($trx['tip_amount'] ?? 'N/A'));
                    $this->line("Total Amount : " . ($trx['total_amount'] ?? 'N/A'));
                    $this->line("Status       : " . ($trx['status'] ?? 'N/A'));
                    $this->line("QR Data      : " . ($trx['qr_data'] ?? 'N/A'));
                    $this->line("Expired At   : " . ($trx['expired_at'] ?? 'N/A'));
                    $this->line("Created At   : " . ($trx['created_at'] ?? 'N/A'));
                }
            } else {
                $this->error("❌ Failed to fetch QRIS list: HTTP " . $response->status());
                $this->line($response->body());
            }
        } catch (\Exception $e) {
            $this->error("Exception while fetching QRIS list: " . $e->getMessage());
            Log::error('Exception fetching SingaPay QRIS list', ['exception' => $e]);
            return 1;
        }

        return 0;
    }
}
