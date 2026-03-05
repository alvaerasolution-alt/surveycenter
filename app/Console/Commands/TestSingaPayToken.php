<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestSingaPayToken extends Command
{
    protected $signature = 'test:singapay-token';
    protected $description = 'Test SingaPay sandbox: get access token & request endpoint lain';

    public function handle()
    {
        $baseUrl = config('singapay.base_url');   // contoh: https://sandbox-api.singapay.id
        $clientId = config('singapay.client_id');
        $clientSecret = config('singapay.client_secret');
        $apiKey   = config('singapay.api_key');

        // Step 1: generate base64 Basic Auth
        $basicAuth = base64_encode($clientId . ':' . $clientSecret);

        $this->info("Requesting Access Token...");

        try {
            $tokenResponse = Http::withoutVerifying()
                ->timeout(15)
                ->asJson()
                ->withHeaders([
                    'X-PARTNER-ID' => $apiKey,
                    'Accept'       => 'application/json',
                    'Authorization' => 'Basic ' . $basicAuth,
                ])
                ->post($baseUrl . '/api/v1.0/access-token/b2b', [
                    'grant_type' => 'client_credentials',
                ]);

            if ($tokenResponse->status() != 200) {
                $this->error("Failed to get token: HTTP " . $tokenResponse->status());
                $this->line($tokenResponse->body());
                return 1;
            }

            $tokenData = $tokenResponse->json('data');
            $accessToken = $tokenData['access_token'];
            $this->info("Access Token: " . $accessToken);
        } catch (\Exception $e) {
            $this->error("Exception while getting token: " . $e->getMessage());
            return 1;
        }

        // Step 2: request endpoint lain dengan Bearer token
        $this->info("Requesting /api/v1.0/accounts with Bearer token...");

        try {
            $response = Http::withoutVerifying()
                ->timeout(15)
                ->asJson()
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Accept'        => 'application/json',
                    'X-PARTNER-ID'  => $apiKey,
                ])
                ->get($baseUrl . '/api/v1.0/accounts');

            $this->info("Status: " . $response->status());
            $this->line("Body: " . $response->body());
        } catch (\Exception $e) {
            $this->error("Exception while calling endpoint: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
