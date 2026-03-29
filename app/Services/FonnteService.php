<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    /**
     * Send an OTP message via WhatsApp using Fonnte API.
     */
    public function sendOtp(string $phone, string $otp): array
    {
        $token = config('services.fonnte.token');

        if (!$token) {
            Log::error('Fonnte API token is not configured.');
            return ['status' => false, 'reason' => 'Fonnte API token not configured'];
        }

        $message = "Kode OTP Anda untuk reset password SurveyCenter adalah: *{$otp}*\n\nKode ini berlaku selama 5 menit. Jangan bagikan kode ini kepada siapapun.";

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->asForm()->post('https://api.fonnte.com/send', [
                'target' => $phone,
                'message' => $message,
                'countryCode' => '62',
            ]);

            $result = $response->json();
            Log::info('Fonnte OTP sent', ['phone' => $phone, 'response' => $result]);

            return $result;
        } catch (\Exception $e) {
            Log::error('Fonnte OTP failed', ['phone' => $phone, 'error' => $e->getMessage()]);
            return ['status' => false, 'reason' => $e->getMessage()];
        }
    }
}
