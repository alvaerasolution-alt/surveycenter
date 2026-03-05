<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestSingaPayVariations extends Command
{
    protected $signature = 'test:singapay-variations';
    protected $description = 'Test multiple signature variations with SingaPay sandbox';

    public function handle()
    {
        $baseUrl    = "https://sandbox-payment-b2b.singapay.id";
        $path       = "/api/v1.0/accounts";
        $method     = "GET";

        $clientId   = env('SINGAPAY_CLIENT_ID');
        $hmacKey    = env('SINGAPAY_HMAC_KEY');

        $this->info("Testing SingaPay variations...");
        $body = ""; // kosong karena GET

        // Hash body kosong pakai SHA256
        $emptyBodyHash = hash("sha256", $body);

        // Variasi canonical
        $canonicalOptions = [
            "with_slash"   => "$method|$path||%s",
            "without_slash"=> "$method|" . ltrim($path, "/") . "||%s",
            "with_bodyhash"=> "$method|$path|$emptyBodyHash|%s",
            "full_url"     => "$method|$baseUrl$path||%s",
        ];

        // Variasi timestamp
        $timeOptions = [
            "epoch_s"  => time(),
            "epoch_ms" => round(microtime(true) * 1000),
        ];

        // Variasi header
        $headerOptions = [
            "X-SIGNATURE"      => fn($sig) => ["X-CLIENT-KEY" => $clientId, "X-SIGNATURE" => $sig],
            "AuthorizationHMAC"=> fn($sig) => ["Authorization" => "HMAC $sig", "X-CLIENT-KEY" => $clientId],
        ];

        foreach ($canonicalOptions as $canonName => $canonFmt) {
            foreach ($timeOptions as $timeName => $timestamp) {
                $canonical = sprintf($canonFmt, $timestamp);
                $signature = hash_hmac("sha256", $canonical, $hmacKey);

                foreach ($headerOptions as $headerName => $makeHeader) {
                    $headers = $makeHeader($signature);
                    $headers["X-TIMESTAMP"] = $timestamp;

                    $this->line("\n👉 Testing [$canonName | $timeName | $headerName]");
                    $this->line("Canonical: $canonical");
                    $this->line("Signature: $signature");

                    try {
                        $response = Http::withHeaders($headers)->get($baseUrl . $path);
                        $status   = $response->status();
                        $body     = substr($response->body(), 0, 200);

                        $this->warn("Status: $status");
                        $this->line("Body: $body\n");

                        if ($status === 200) {
                            $this->info("🎉 SUCCESS with [$canonName | $timeName | $headerName]");
                            return Command::SUCCESS;
                        }
                    } catch (\Exception $e) {
                        $this->error("Error: " . $e->getMessage());
                    }
                }
            }
        }

        $this->error("❌ Tidak ada kombinasi yang berhasil. Cek dokumentasi endpoint/signature format.");
        return Command::FAILURE;
    }
}
