<?php

namespace App\Http\Controllers;

use App\Models\FaspayTestTransaction;
use App\Models\Transaction;
use App\Services\FaspayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class FaspayController extends Controller
{
    protected FaspayService $faspayService;

    public function __construct(FaspayService $faspayService)
    {
        $this->faspayService = $faspayService;
    }

    /**
     * Handle payment notification webhook from Faspay
     * This URL will be registered in Faspay merchant dashboard
     * Faspay will retry this endpoint 3 times if no OK response received
     */
    public function notification(Request $request): JsonResponse
    {
        Log::info('Faspay notification received', $request->all());

        try {
            // Get notification data (can be JSON or XML)
            $data = $request->json()->all() ?? [];
            if (empty($data)) {
                $data = $request->all();
            }

            // Validate notification signature
            $notification = $this->faspayService->handleNotification($data);

            if (!$notification['success']) {
                Log::warning('Faspay notification validation failed', $notification);
                return response()->json([
                    'response' => 'Payment Notification',
                    'trx_id' => $data['trx_id'] ?? null,
                    'merchant_id' => config('faspay.merchant_id'),
                    'bill_no' => $data['bill_no'] ?? null,
                    'response_code' => '99',
                    'response_desc' => 'Signature validation failed',
                    'response_date' => now()->format('Y-m-d H:i:s'),
                ]);
            }

            // Find transaction by bill_no
            $transaction = FaspayTestTransaction::where('bill_no', $notification['bill_no'])->first();

            // Fallback: cari di tabel transactions utama via kolom bill_no
            $mainTransaction = Transaction::where('bill_no', $notification['bill_no'])->first();
            // Jika tidak ada, coba via payment_ref
            if (!$mainTransaction) {
                $mainTransaction = Transaction::where('payment_ref', $notification['bill_no'])->first();
            }

            if (!$transaction && !$mainTransaction) {
                Log::warning('Faspay transaction not found', ['bill_no' => $notification['bill_no']]);

                return response()->json([
                    'response' => 'Payment Notification',
                    'trx_id' => $notification['trx_id'],
                    'merchant_id' => config('faspay.merchant_id'),
                    'bill_no' => $notification['bill_no'],
                    'response_code' => '05',
                    'response_desc' => 'Transaction not found',
                    'response_date' => now()->format('Y-m-d H:i:s'),
                ]);
            }

            if ($mainTransaction && $mainTransaction->status === Transaction::STATUS_PAID) {
                Log::info('Main transaction already paid, skipping re-processing', [
                    'bill_no' => $notification['bill_no'],
                    'transaction_id' => $mainTransaction->id,
                ]);
            }

            $paymentStatusCode = (string) $notification['payment_status_code'];

            // Handle different payment status codes
            switch ($paymentStatusCode) {
                case '2': // Payment Success
                    if ($transaction) {
                        $transaction->markAsPaid([
                            'trx_id' => $notification['trx_id'],
                            'payment_channel' => $notification['payment_channel'],
                            'bank_user_name' => $data['bank_user_name'] ?? null,
                            'payment_status_code' => $notification['payment_status_code'],
                            'payment_date' => $notification['payment_date'],
                        ]);
                    }

                    if ($mainTransaction && $mainTransaction->status !== Transaction::STATUS_PAID) {
                        $mainTransaction->update([
                            'status' => Transaction::STATUS_PAID,
                            'payment_method' => strtolower((string) ($notification['payment_channel'] ?? $mainTransaction->payment_method)),
                        ]);
                    }

                    Log::info('Transaction marked as paid', ['bill_no' => $notification['bill_no'], 'trx_id' => $notification['trx_id']]);
                    break;

                case '3': // Payment Failed
                    if ($transaction) {
                        $transaction->markAsFailed('Payment failed from Faspay');
                    }

                    if ($mainTransaction && $mainTransaction->status !== Transaction::STATUS_PAID) {
                        $mainTransaction->update([
                            'status' => Transaction::STATUS_FAILED,
                        ]);
                    }

                    Log::warning('Transaction marked as failed', ['bill_no' => $notification['bill_no']]);
                    break;

                case '7': // Payment Expired
                    if ($transaction) {
                        $transaction->update(['status' => FaspayTestTransaction::STATUS_EXPIRED]);
                    }

                    if ($mainTransaction && $mainTransaction->status !== Transaction::STATUS_PAID) {
                        $mainTransaction->update([
                            'status' => Transaction::STATUS_FAILED,
                        ]);
                    }

                    Log::info('Transaction expired', ['bill_no' => $notification['bill_no']]);
                    break;

                case '8': // Payment Cancelled
                    if ($transaction) {
                        $transaction->update(['status' => FaspayTestTransaction::STATUS_CANCELLED]);
                    }

                    if ($mainTransaction && $mainTransaction->status !== Transaction::STATUS_PAID) {
                        $mainTransaction->update([
                            'status' => Transaction::STATUS_FAILED,
                        ]);
                    }

                    Log::info('Transaction cancelled', ['bill_no' => $notification['bill_no']]);
                    break;

                case '0': // Unprocessed
                case '1': // In Process
                    if ($transaction) {
                        $transaction->update(['status' => FaspayTestTransaction::STATUS_PROCESSING]);
                    }

                    if ($mainTransaction && $mainTransaction->status !== Transaction::STATUS_PAID) {
                        $mainTransaction->update([
                            'status' => Transaction::STATUS_PROCESSING,
                        ]);
                    }

                    break;

                default:
                    Log::warning('Unknown payment status code', ['code' => $notification['payment_status_code']]);
            }

            // Store full payment response
            if ($transaction) {
                $transaction->update([
                    'payment_response' => json_encode($data),
                    'trx_id' => $notification['trx_id'] ?? $transaction->trx_id,
                    'payment_channel' => $notification['payment_channel'] ?? $transaction->payment_channel,
                    'bank_user_name' => $data['bank_user_name'] ?? $transaction->bank_user_name,
                    'payment_date' => $notification['payment_date'] ? \Carbon\Carbon::parse($notification['payment_date']) : $transaction->payment_date,
                ]);
            }

            // Return success response to Faspay
            return response()->json([
                'response' => 'Payment Notification',
                'trx_id' => $notification['trx_id'],
                'merchant_id' => config('faspay.merchant_id'),
                'bill_no' => $notification['bill_no'],
                'response_code' => '00',
                'response_desc' => 'Success',
                'response_date' => now()->format('Y-m-d H:i:s'),
            ]);
        } catch (\Exception $e) {
            Log::error('Faspay notification processing error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'response' => 'Payment Notification',
                'response_code' => '99',
                'response_desc' => 'System error',
                'response_date' => now()->format('Y-m-d H:i:s'),
            ], 500);
        }
    }

    /**
     * Handle return URL callback after customer completes payment
     * This is the landing page the customer is redirected to after paying
     * Note: Don't update payment status here - only the webhook notification updates status
     */
    public function returnUrl(Request $request)
    {
        Log::info('Faspay return URL accessed', $request->all());

        try {
            $billNo = $request->input('bill_no');
            $status = $request->input('status');
            $trxId  = $request->input('trx_id');

            // Cari di tabel test transactions
            $transaction = FaspayTestTransaction::where('bill_no', $billNo)->first();

            // Fallback: cari di tabel transactions utama
            if (!$transaction) {
                $mainTransaction = Transaction::where('bill_no', $billNo)
                    ->orWhere('payment_ref', $billNo)
                    ->first();

                if ($mainTransaction) {
                    // Update trx_id jika ada
                    if ($trxId) {
                        $mainTransaction->update(['trx_id' => $trxId]);
                    }

                    // Redirect ke halaman transaksi user
                    return redirect()->route('user.transactions.show', $mainTransaction)
                        ->with('info', 'Pembayaran sedang diproses. Status akan diperbarui otomatis.');
                }

                Log::warning('Return URL: Transaction not found in both tables', ['bill_no' => $billNo]);
                return view('faspay.return.error', [
                    'message' => 'Transaction not found',
                    'details' => ['bill_no' => $billNo],
                ]);
            }

            // Store the return data for reference (test transaction)
            $transaction->update([
                'trx_id' => $trxId ?? $transaction->trx_id,
            ]);

            // The actual status update happens via webhook notification
            if ($status === '0' || $transaction->isPaid()) {
                return view('faspay.return.success', [
                    'transaction' => $transaction,
                    'message'     => 'Payment successful! Waiting for confirmation...',
                ]);
            } else {
                return view('faspay.return.pending', [
                    'transaction' => $transaction,
                    'message'     => 'Payment is being processed. Please wait...',
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Return URL processing error', [
                'error' => $e->getMessage(),
            ]);

            return view('faspay.return.error', [
                'message' => 'An error occurred',
                'details' => ['error' => $e->getMessage()],
            ]);
        }
    }

    /**
     * Debug endpoint to check Faspay configuration (DEVELOPMENT ONLY)
     */
    public function debugConfig()
    {
        if (!app()->isLocal()) {
            return response()->json(['error' => 'Not available in production'], 403);
        }

        return response()->json([
            'configured' => $this->faspayService->isConfigured(),
            'environment' => config('faspay.environment'),
            'merchant_id' => config('faspay.merchant_id') ? '***' . substr(config('faspay.merchant_id'), -4) : 'NOT SET',
            'webhook_urls' => config('faspay.webhook_urls'),
            'payment_channels' => $this->faspayService->getPaymentChannels(),
            'supported_channels' => $this->faspayService->getSupportedChannels(),
        ]);
    }

    /**
     * List recent test transactions (for admin/testing panel)
     */
    public function listTransactions()
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $transactions = FaspayTestTransaction::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return response()->json($transactions);
    }
}
