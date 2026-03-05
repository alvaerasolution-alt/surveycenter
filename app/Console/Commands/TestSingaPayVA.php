<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestSingaPayVA extends Command
{
    protected $signature = 'test:singapay-va {account_id} {--kind=temporary}';
    protected $description = 'Test Get List Virtual Accounts SingaPay';

    public function handle()
    {
        $baseUrl = config('singapay.base_url');   // sandbox base URL
        $apiKey  = config('singapay.api_key');
        $clientId = config('singapay.client_id');
        $clientSecret = config('singapay.client_secret');

        // Step 1: get access token
        $basicAuth = base64_encode($clientId . ':' . $clientSecret);

        $this->info("Requesting Access Token...");

        try {
            $tokenResponse = Http::withoutVerifying()
                ->timeout(15)
                ->asJson()
                ->withHeaders([
                    'X-PARTNER-ID' => $apiKey,
                    'Accept'       => 'application/json',
                    'Authorization'=> 'Basic ' . $basicAuth,
                ])
                ->post($baseUrl . '/api/v1.0/access-token/b2b', [
                    'grant_type' => 'client_credentials',
                ]);

            if ($tokenResponse->status() != 200) {
                $this->error("Failed to get token: HTTP " . $tokenResponse->status());
                $this->line($tokenResponse->body());
                return 1;
            }

            $accessToken = $tokenResponse->json('data.access_token');
            $this->info("Access Token: " . $accessToken);

        } catch (\Exception $e) {
            $this->error("Exception while getting token: " . $e->getMessage());
            return 1;
        }

        // Step 2: request list virtual accounts
        $accountId = $this->argument('account_id');
        $kind = $this->option('kind');

        $this->info("Requesting Virtual Accounts for account_id: $accountId, kind: $kind...");

        try {
            $response = Http::withoutVerifying()
                ->timeout(15)
                ->asJson()
                ->withHeaders([
                    'X-PARTNER-ID'  => $apiKey,
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Accept'        => 'application/json',
                ])
                ->get($baseUrl . '/api/v1.0/virtual-accounts/' . $accountId, [
                    'kind' => $kind
                ]);

            $this->info("Status: " . $response->status());
            $this->line("Body: " . $response->body());

        } catch (\Exception $e) {
            $this->error("Exception while requesting virtual accounts: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
