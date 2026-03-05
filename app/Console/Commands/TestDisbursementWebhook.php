<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestDisbursementWebhook extends Command
{
    protected $signature = 'webhook:test-disbursement';
    protected $description = 'Simulasi webhook disbursement SingaPay';

    public function handle()
    {
        $this->info('Menjalankan simulasi webhook...');

        // URL endpoint sesuai route api.php
        $url = 'http://127.0.0.1:8000/api/webhook/singapay/disbursement';

        $payload = [
            "status" => 200,
            "success" => true,
            "data" => [
                "transaction_id" => "999",
                "status" => "success",
                "amount" => ["value" => "10000.00", "currency" => "IDR"],
                "bank_code" => "BRI",
                "bank_account_number" => "1234567890",
                "bank_account_name" => "Tes User",
                "post_timestamp" => "1714618220440",
                "processed_timestamp" => "1714618220440",
                "fees" => [["name" => "transfer fee", "value" => "2000.00", "currency" => "IDR"]],
                "total_amount" => ["value" => "12000.00", "currency" => "IDR"],
                "source_account" => [
                    "account_id" => "888",
                    "balance_after" => ["value" => "50000.00", "currency" => "IDR"]
                ]
            ]
        ];

        // Header hanya untuk production (local tidak perlu)
        $headers = [];
        if (app()->environment('production')) {
            $headers['X-PARTNER-ID'] = config('singapay.api_key');
        }

        $response = Http::withHeaders($headers)
            ->post($url, $payload);

        $this->info('Webhook dijalankan. Response:');
        $this->line($response->body());
    }
}
 