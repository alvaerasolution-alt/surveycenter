<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\PaymentFailedMail;
use App\Mail\PaymentSuccessMail;
use App\Models\Transaction;
use App\Services\FaspayService;
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
        if ($transaction->status === Transaction::STATUS_PAID) {
            return redirect()->route('user.transactions.show', $transaction)
                ->with('info', 'Transaksi ini sudah dibayar.');
        }

        $gatewayOptions = $this->getGatewayOptions();
        $defaultGateway = $this->resolveDefaultGateway($gatewayOptions);

        return view('user.payments.show', compact('transaction', 'gatewayOptions', 'defaultGateway'));
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

        $gatewayOptions = $this->getGatewayOptions();
        $availableGatewayKeys = collect($gatewayOptions)
            ->filter(fn (array $gateway) => ($gateway['enabled'] ?? false) && ($gateway['configured'] ?? false))
            ->keys()
            ->all();

        $defaultGateway = $this->resolveDefaultGateway($gatewayOptions);

        $validated = $request->validate([
            'payment_gateway' => 'nullable|string',
            'payment_method' => 'required|in:qris,virtual_account,e_wallet',
        ]);

        $selectedGateway = $validated['payment_gateway'] ?? $defaultGateway;

        if (!in_array($selectedGateway, $availableGatewayKeys, true)) {
            return back()->withInput()->withErrors([
                'payment_gateway' => 'Gateway pembayaran yang dipilih tidak tersedia.',
            ]);
        }

        // Don't process if already paid
        if ($transaction->status === Transaction::STATUS_PAID) {
            return back()->with('warning', 'Transaksi ini sudah dibayar.');
        }

        try {
            if ($selectedGateway === 'faspay') {
                return $this->processFaspayPayment($transaction, $validated['payment_method']);
            }

            return $this->processSingaPayPayment($transaction, $validated['payment_method']);
        } catch (\Exception $e) {
            Log::error('Payment Processing Exception', [
                'transaction_id' => $transaction->id,
                'user_id' => Auth::id(),
                'gateway' => $selectedGateway,
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
        if ($status === Transaction::STATUS_PAID) {
            $transaction->update(['status' => Transaction::STATUS_PAID]);
            
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
        } elseif ($status === Transaction::STATUS_FAILED || $status === 'expired' || $status === 'cancelled') {
            $transaction->update(['status' => Transaction::STATUS_FAILED]);
            
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

    private function processSingaPayPayment(Transaction $transaction, string $paymentMethod)
    {
        $invoice = $this->singaPay->createInvoice(
            $transaction->amount,
            [
                [
                    'name' => $transaction->survey->title ?? 'Survey Payment',
                    'quantity' => 1,
                    'unit_price' => $transaction->amount,
                ],
            ]
        );

        if (!isset($invoice['success']) || !$invoice['success']) {
            Log::error('SingaPay payment failed', [
                'transaction_id' => $transaction->id,
                'user_id' => Auth::id(),
                'error' => $invoice['message'] ?? 'Unknown error',
            ]);

            return back()->with('error', 'Gagal membuat pembayaran: ' . ($invoice['message'] ?? 'Kesalahan tidak diketahui.'));
        }

        $transaction->update([
            'payment_method' => $paymentMethod,
            'singapay_ref' => $invoice['reff_no'] ?? null,
            'status' => Transaction::STATUS_PROCESSING,
        ]);

        Log::info('SingaPay payment started', [
            'transaction_id' => $transaction->id,
            'user_id' => Auth::id(),
            'reference' => $invoice['reff_no'] ?? null,
        ]);

        return redirect($invoice['payment_url']);
    }

    private function processFaspayPayment(Transaction $transaction, string $paymentMethod)
    {
        /** @var FaspayService $faspayService */
        $faspayService = app(FaspayService::class);

        if (!$faspayService->isConfigured()) {
            return back()->withInput()->withErrors([
                'payment_gateway' => 'Faspay belum dikonfigurasi. Silakan pilih SingaPay.',
            ]);
        }

        $billNo = 'TRX-' . $transaction->id . '-' . now()->format('YmdHis');

        $invoiceData = [
            'bill_no' => $billNo,
            'bill_reff' => 'SURVEY-' . $transaction->id,
            'bill_total' => $transaction->amount,
            'bill_description' => $transaction->survey->title ?? 'Survey Payment',
            'cust_name' => Auth::user()->name ?? 'Customer',
            'cust_email' => Auth::user()->email ?? '',
            'cust_phone' => Auth::user()->phone ?? '',
            'due_date' => now()->addMinutes((int) config('faspay.invoice_expiration', 30))->format('Y-m-d H:i:s'),
            'bill_expired_date' => now()->addMinutes((int) config('faspay.invoice_expiration', 30))->format('Y-m-d H:i:s'),
            'return_url' => route('faspay.webhook.return'),
            'notif_url' => route('faspay.webhook.notification'),
        ];

        $response = $faspayService->createInvoice($invoiceData);

        if (!($response['success'] ?? false) || empty($response['payment_url'])) {
            Log::error('Faspay payment failed', [
                'transaction_id' => $transaction->id,
                'user_id' => Auth::id(),
                'response' => $response,
            ]);

            return back()->with('error', 'Gagal membuat link pembayaran Faspay. Silakan coba lagi.');
        }

        $transaction->update([
            'payment_method' => $paymentMethod,
            'singapay_ref' => $billNo,
            'status' => Transaction::STATUS_PROCESSING,
        ]);

        Log::info('Faspay payment started', [
            'transaction_id' => $transaction->id,
            'user_id' => Auth::id(),
            'bill_no' => $billNo,
            'trx_id' => $response['trx_id'] ?? null,
        ]);

        return redirect($response['payment_url']);
    }

    private function getGatewayOptions(): array
    {
        $gateways = config('payment_gateways.gateways', []);
        $order = config('payment_gateways.order', []);

        if (empty($order)) {
            return $gateways;
        }

        $ordered = [];

        foreach ($order as $key) {
            if (isset($gateways[$key])) {
                $ordered[$key] = $gateways[$key];
            }
        }

        foreach ($gateways as $key => $gateway) {
            if (!isset($ordered[$key])) {
                $ordered[$key] = $gateway;
            }
        }

        return $ordered;
    }

    private function resolveDefaultGateway(array $gatewayOptions): string
    {
        $configuredDefault = (string) config('payment_gateways.default', 'singapay');

        if (
            isset($gatewayOptions[$configuredDefault])
            && ($gatewayOptions[$configuredDefault]['enabled'] ?? false)
            && ($gatewayOptions[$configuredDefault]['configured'] ?? false)
        ) {
            return $configuredDefault;
        }

        foreach ($gatewayOptions as $key => $gateway) {
            if (($gateway['enabled'] ?? false) && ($gateway['configured'] ?? false)) {
                return $key;
            }
        }

        return 'singapay';
    }
}
