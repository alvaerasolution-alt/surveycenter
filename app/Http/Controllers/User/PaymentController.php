<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\PaymentFailedMail;
use App\Mail\PaymentSuccessMail;
use App\Models\Transaction;
use App\Services\SingaPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    private SingaPayService $singaPay;

    public function __construct(SingaPayService $singaPay)
    {
        $this->singaPay = $singaPay;
    }

    /**
     * Show payment page for a transaction
     */
    public function show(Transaction $transaction)
    {
        // Ensure user owns this transaction
        if ($transaction->user_id != Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Don't allow payment if already paid
        if ($transaction->status === 'paid') {
            return redirect()->route('user.transactions.show', $transaction)
                ->with('info', 'Transaksi ini sudah dibayar.');
        }

        return view('user.payments.show', compact('transaction'));
    }

    /**
     * Process payment - initiate Singapay invoice
     */
    public function process(Request $request, Transaction $transaction)
    {
        // Ensure user owns this transaction
        if ($transaction->user_id != Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Validate payment method
        $request->validate([
            'payment_method' => 'required|in:qris,virtual_account,e_wallet',
        ]);

        // Don't process if already paid
        if ($transaction->status === 'paid') {
            return back()->with('warning', 'Transaksi ini sudah dibayar.');
        }

        try {
            // Create invoice via Singapay service
            $invoice = $this->singaPay->createInvoice(
                $transaction->amount,
                [
                    [
                        'name' => $transaction->survey->title ?? 'Survey Payment',
                        'quantity' => 1,
                        'unit_price' => $transaction->amount
                    ]
                ]
            );

            if (!isset($invoice['success']) || !$invoice['success']) {
                Log::error('Payment Processing Failed', [
                    'transaction_id' => $transaction->id,
                    'user_id' => Auth::id(),
                    'error' => $invoice['message'] ?? 'Unknown error'
                ]);
                return back()->with('error', 'Gagal membuat pembayaran: ' . ($invoice['message'] ?? 'Kesalahan tidak diketahui.'));
            }

            // Update transaction with payment reference
            $transaction->update([
                'payment_method' => $request->payment_method,
                'singapay_ref' => $invoice['reff_no'] ?? null,
                'status' => 'processing',
            ]);

            Log::info('Payment Processing Started', [
                'transaction_id' => $transaction->id,
                'user_id' => Auth::id(),
                'singapay_ref' => $invoice['reff_no'] ?? null
            ]);

            // Redirect to Singapay payment page
            return redirect($invoice['payment_url']);
        } catch (\Exception $e) {
            Log::error('Payment Processing Exception', [
                'transaction_id' => $transaction->id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.');
        }
    }

    /**
     * Payment success callback
     */
    public function success(Transaction $transaction)
    {
        // Ensure user owns this transaction
        if ($transaction->user_id != Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('user.payments.success', compact('transaction'));
    }

    /**
     * Payment failed callback
     */
    public function failed(Transaction $transaction)
    {
        // Ensure user owns this transaction
        if ($transaction->user_id != Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('user.payments.failed', compact('transaction'));
    }

    /**
     * Handle webhook from Singapay - called when payment is confirmed
     * This should be called from the webhook handler, not directly by users
     */
    public function handleWebhook($transactionId, $status)
    {
        $transaction = Transaction::find($transactionId);
        
        if (!$transaction) {
            Log::warning('Webhook: Transaction not found', ['transaction_id' => $transactionId]);
            return false;
        }

        $oldStatus = $transaction->status;
        
        // Update transaction status
        if ($status === 'paid') {
            $transaction->update(['status' => 'paid']);
            
            Log::info('Payment Confirmed via Webhook', [
                'transaction_id' => $transaction->id,
                'user_id' => $transaction->user_id,
                'old_status' => $oldStatus
            ]);
            
            // Send success email
            try {
                Mail::to($transaction->user->email)->queue(new PaymentSuccessMail($transaction));
                Log::info('Payment Success Email Queued', ['transaction_id' => $transaction->id]);
            } catch (\Exception $e) {
                Log::error('Failed to Queue Payment Success Email', [
                    'transaction_id' => $transaction->id,
                    'error' => $e->getMessage()
                ]);
            }
            
            return true;
        } elseif ($status === 'failed' || $status === 'expired' || $status === 'cancelled') {
            $transaction->update(['status' => 'failed']);
            
            Log::info('Payment Failed via Webhook', [
                'transaction_id' => $transaction->id,
                'user_id' => $transaction->user_id,
                'reason' => $status
            ]);
            
            // Send failed email
            try {
                Mail::to($transaction->user->email)->queue(new PaymentFailedMail($transaction));
                Log::info('Payment Failed Email Queued', ['transaction_id' => $transaction->id]);
            } catch (\Exception $e) {
                Log::error('Failed to Queue Payment Failed Email', [
                    'transaction_id' => $transaction->id,
                    'error' => $e->getMessage()
                ]);
            }
            
            return true;
        }
        
        return false;
    }
}
