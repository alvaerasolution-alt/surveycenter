<?php

namespace App\Http\Controllers;

use App\Models\SingaPayTestTransaction;
use App\Services\SingaPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SingaPayTestController extends Controller
{
    private SingaPayService $singaPay;

    public function __construct(SingaPayService $singaPay)
    {
        $this->singaPay = $singaPay;
    }

    /**
     * List all test transactions
     */
    public function index()
    {
        $transactions = SingaPayTestTransaction::where('user_id', Auth::id())
            ->orWhereNull('user_id')
            ->latest()
            ->paginate(15);

        return view('singapay.test-transactions.index', [
            'transactions' => $transactions,
            'activeCount' => SingaPayTestTransaction::active()->count(),
            'paidCount' => SingaPayTestTransaction::paid()->count(),
        ]);
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('singapay.test-transactions.create');
    }

    /**
     * Store new test transaction
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1000|max:100000000',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string|max:20',
            'bill_description' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        $billNo = $this->generateBillNo();

        $transaction = SingaPayTestTransaction::create([
            'user_id' => Auth::id(),
            'bill_no' => $billNo,
            'amount' => $validated['amount'],
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'],
            'bill_description' => $validated['bill_description'] ?? 'Test Transaction',
            'notes' => $validated['notes'],
            'expires_at' => now()->addMinutes(30),
        ]);

        return redirect()->route('singapay.test.payment', $transaction)
            ->with('success', 'Test transaction dibuat. Lanjutkan ke pembayaran.');
    }

    /**
     * Show payment page — create SingaPay invoice and redirect
     */
    public function payment(SingaPayTestTransaction $singaPayTestTransaction)
    {
        $transaction = $singaPayTestTransaction;

        if ($transaction->isPaid()) {
            return redirect()->route('singapay.test.success', $transaction)
                ->with('info', 'Transaksi ini sudah dibayar.');
        }

        if ($transaction->isExpired()) {
            $transaction->update(['status' => SingaPayTestTransaction::STATUS_EXPIRED]);
            return redirect()->route('singapay.test.index')
                ->with('error', 'Transaksi sudah expired.');
        }

        return view('singapay.test-transactions.payment', [
            'transaction' => $transaction,
        ]);
    }

    /**
     * Process payment — call SingaPay API to create invoice, redirect to payment URL
     */
    public function processPayment(SingaPayTestTransaction $singaPayTestTransaction)
    {
        $transaction = $singaPayTestTransaction;

        if ($transaction->isPaid()) {
            return back()->with('warning', 'Transaksi sudah dibayar.');
        }

        try {
            $invoice = $this->singaPay->createInvoice(
                $transaction->amount,
                [
                    [
                        'name' => $transaction->bill_description,
                        'quantity' => 1,
                        'unit_price' => $transaction->amount,
                    ],
                ]
            );

            if (!isset($invoice['success']) || !$invoice['success']) {
                Log::error('SingaPay Test: Create invoice failed', [
                    'transaction_id' => $transaction->id,
                    'error' => $invoice['message'] ?? 'Unknown',
                ]);

                return back()->with('error', 'Gagal membuat invoice: ' . ($invoice['message'] ?? 'Error tidak diketahui'));
            }

            $transaction->update([
                'singapay_ref' => $invoice['reff_no'] ?? null,
                'payment_url' => $invoice['payment_url'] ?? null,
                'status' => SingaPayTestTransaction::STATUS_PROCESSING,
            ]);

            Log::info('SingaPay Test: Invoice created', [
                'transaction_id' => $transaction->id,
                'reff_no' => $invoice['reff_no'] ?? null,
                'payment_url' => $invoice['payment_url'] ?? null,
            ]);

            if (!empty($invoice['payment_url'])) {
                return redirect($invoice['payment_url']);
            }

            return back()->with('error', 'Payment URL tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('SingaPay Test: Exception', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Success page
     */
    public function success(SingaPayTestTransaction $singaPayTestTransaction)
    {
        return view('singapay.test-transactions.success', [
            'transaction' => $singaPayTestTransaction,
        ]);
    }

    /**
     * Show detail
     */
    public function show(SingaPayTestTransaction $singaPayTestTransaction)
    {
        return view('singapay.test-transactions.show', [
            'transaction' => $singaPayTestTransaction,
        ]);
    }

    /**
     * Delete
     */
    public function destroy(SingaPayTestTransaction $singaPayTestTransaction)
    {
        $singaPayTestTransaction->delete();
        return redirect()->route('singapay.test.index')
            ->with('success', 'Test transaction dihapus.');
    }

    /**
     * Webhook handler — SingaPay calls this when payment status changes
     */
    public function webhook(Request $request)
    {
        $payload = $request->all();
        Log::info('SingaPay Test Webhook Received', $payload);

        if (!isset($payload['status'], $payload['success'])) {
            return response()->json(['status' => 'invalid_payload'], 400);
        }

        if (!($payload['success'] === true || $payload['success'] === 1 || $payload['success'] === 'true')) {
            return response()->json(['status' => 'not_success']);
        }

        $reffNo = data_get($payload, 'data.payment.additional_info.payment_link.reff_no');

        if (!$reffNo) {
            return response()->json(['status' => 'no_reff_no']);
        }

        $transaction = SingaPayTestTransaction::where('singapay_ref', $reffNo)->first();

        if (!$transaction) {
            Log::warning('SingaPay Test Webhook: Transaction not found', ['reff_no' => $reffNo]);
            return response()->json(['status' => 'not_found']);
        }

        $status = data_get($payload, 'data.transaction.status');
        $method = data_get($payload, 'data.payment.method');

        if ($status === 'paid' || $status === 'settlement' || $status === 'success') {
            $transaction->markAsPaid(array_merge($payload, ['payment_method' => $method]));
            Log::info('SingaPay Test Webhook: Marked as paid', ['id' => $transaction->id]);
        } elseif (in_array($status, ['failed', 'cancelled', 'expired'])) {
            $transaction->update([
                'status' => SingaPayTestTransaction::STATUS_FAILED,
                'webhook_payload' => $payload,
            ]);
        }

        return response()->json(['status' => 'ok']);
    }

    /**
     * Check status — poll endpoint for AJAX
     */
    public function checkStatus(SingaPayTestTransaction $singaPayTestTransaction)
    {
        return response()->json([
            'status' => $singaPayTestTransaction->fresh()->status,
            'is_paid' => $singaPayTestTransaction->fresh()->isPaid(),
        ]);
    }

    private function generateBillNo(): string
    {
        $prefix = strtoupper(trim((string) config('singapay.invoice_prefix', 'TRX')));
        $prefix = preg_replace('/[^A-Z0-9]/', '', $prefix) ?: 'TRX';

        return $prefix . '-TEST-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4));
    }
}
