<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Disbursement;

class SingaPayWebhookController extends Controller
{
    public function handleDisbursement(Request $request)
    {
        // Hanya cek X-PARTNER-ID jika environment = production
        if (app()->environment('production')) {
            $partnerId = $request->header('X-PARTNER-ID');
            if ($partnerId !== config('singapay.api_key')) {
                return response()->json([
                    'status' => 401,
                    'success' => false,
                    'message' => 'Invalid Partner ID',
                ], 401);
            }
        }

        $payload = $request->all();

        // Log payload untuk debug
        Log::info('SingaPay Disbursement Webhook', $payload);

        $data = $payload['data'] ?? null;

        // Decode if it's a JSON string
        if (is_string($data)) {
            $data = json_decode($data, true) ?? $data;
        }

        if (is_array($data) && isset($data['transaction_id'])) {
            Disbursement::updateOrCreate(
                ['transaction_id' => $data['transaction_id']],
                [
                    'status'                => $data['status'],
                    'bank_code'             => $data['bank_code'],
                    'bank_account_number'   => $data['bank_account_number'],
                    'bank_account_name'     => $data['bank_account_name'] ?? null,
                    'post_timestamp'        => $data['post_timestamp'],
                    'processed_timestamp'   => $data['processed_timestamp'] ?? null,
                    'notes'                 => $data['notes'] ?? null,
                    'amount_value'          => $data['amount']['value'] ?? 0,
                    'amount_currency'       => $data['amount']['currency'] ?? 'IDR',
                    'total_amount_value'    => $data['total_amount']['value'] ?? 0,
                    'total_amount_currency' => $data['total_amount']['currency'] ?? 'IDR',
                    'fees'                  => json_encode($data['fees'] ?? []),
                    'source_account_id'     => $data['source_account']['account_id'] ?? null,
                    'balance_after'         => json_encode($data['balance_after'] ?? []),
                ]
            );
        }

        return response()->json([
            'status'  => 200,
            'success' => true,
        ]);
    }
}
