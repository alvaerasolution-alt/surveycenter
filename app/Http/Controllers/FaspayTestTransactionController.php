<?php

namespace App\Http\Controllers;

use App\Models\FaspayTestTransaction;
use App\Services\FaspayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class FaspayTestTransactionController extends Controller
{
    protected FaspayService $faspayService;

    public function __construct(FaspayService $faspayService)
    {
        $this->faspayService = $faspayService;
    }

    /**
     * Show list of test transactions
     */
    public function index()
    {
        $transactions = FaspayTestTransaction::where('user_id', Auth::id())
            ->orWhereNull('user_id')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('faspay.test-transactions.index', [
            'transactions' => $transactions,
            'activeCount' => FaspayTestTransaction::active()->count(),
            'paidCount' => FaspayTestTransaction::paid()->count(),
        ]);
    }

    /**
     * Show create form for new test transaction
     */
    public function create()
    {
        return view('faspay.test-transactions.create');
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

        try {
            // Generate unique bill number
            $billNo = 'TEST-' . now()->format('YmdHis') . '-' . Str::random(6);

            // Create test transaction
            $transaction = FaspayTestTransaction::create([
                'user_id' => Auth::id(),
                'bill_no' => $billNo,
                'amount' => $validated['amount'],
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'bill_description' => $validated['bill_description'] ?? 'Test Transaction',
                'notes' => $validated['notes'],
                'expires_at' => now()->addMinutes((int) config('faspay.invoice_expiration', 30)),
            ]);

            return redirect()->route('faspay.test-transaction.payment', $transaction)
                ->with('success', 'Test transaction created successfully. Redirecting to payment...');
        } catch (\Exception $e) {
            return back()->withInput()
                ->withErrors(['error' => 'Failed to create test transaction: ' . $e->getMessage()]);
        }
    }

    /**
     * Show payment page for test transaction
     */
    public function payment(FaspayTestTransaction $testTransaction)
    {
        // Check if already paid
        if ($testTransaction->isPaid()) {
            return redirect()->route('faspay.test-transaction.success', $testTransaction)
                ->with('info', 'This transaction has already been paid.');
        }

        // Check if expired
        if ($testTransaction->isExpired()) {
            $testTransaction->update(['status' => FaspayTestTransaction::STATUS_EXPIRED]);
            return redirect()->route('faspay.test-transaction.index')
                ->with('error', 'This transaction has expired.');
        }

        return view('faspay.test-transactions.payment', [
            'transaction' => $testTransaction,
            'faspayConfigured' => $this->faspayService->isConfigured(),
            'paymentChannels' => $this->faspayService->getPaymentChannels(),
            'supportedChannels' => $this->faspayService->getSupportedChannels(),
        ]);
    }

    /**
     * Process payment redirect to Faspay
     */
    public function processPayment(Request $request, FaspayTestTransaction $testTransaction)
    {
        if (!$this->faspayService->isConfigured()) {
            return back()->withErrors(['error' => 'Faspay is not configured. Please add credentials to .env']);
        }

        try {
            // Prepare invoice data
            $invoiceData = [
                'bill_no' => $testTransaction->bill_no,
                'bill_reff' => $testTransaction->bill_no,
                'bill_total' => $testTransaction->amount,
                'bill_description' => $testTransaction->bill_description,
                'cust_name' => $testTransaction->customer_name,
                'cust_email' => $testTransaction->customer_email,
                'cust_phone' => $testTransaction->customer_phone,
                'due_date' => $testTransaction->expires_at?->format('Y-m-d H:i:s'),
                'bill_expired_date' => $testTransaction->expires_at?->format('Y-m-d H:i:s'),
                'return_url' => route('faspay.return'),
                'notif_url' => route('faspay.notification'),
            ];

            // Create invoice at Faspay
            $response = $this->faspayService->createInvoice($invoiceData);

            if ($response['success'] && $response['payment_url']) {
                // Save transaction reference
                $testTransaction->update([
                    'trx_id' => $response['trx_id'] ?? null,
                    'status' => FaspayTestTransaction::STATUS_PROCESSING,
                ]);

                // Redirect to Faspay payment page
                return redirect($response['payment_url'])
                    ->with('success', 'Redirecting to Faspay payment gateway...');
            } else {
                return back()->withErrors(['error' => $response['message'] ?? 'Failed to create payment link. Please try again.']);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Payment processing error: ' . $e->getMessage()]);
        }
    }

    /**
     * Show success page after payment
     */
    public function success(FaspayTestTransaction $testTransaction)
    {
        return view('faspay.test-transactions.success', [
            'transaction' => $testTransaction,
        ]);
    }

    /**
     * Show transaction details
     */
    public function show(FaspayTestTransaction $testTransaction)
    {
        return view('faspay.test-transactions.show', [
            'transaction' => $testTransaction,
        ]);
    }

    /**
     * Delete test transaction
     */
    public function destroy(FaspayTestTransaction $testTransaction)
    {
        $testTransaction->delete();

        return redirect()->route('faspay.test-transaction.index')
            ->with('success', 'Test transaction deleted successfully.');
    }

    /**
     * Manual payment simulation for testing (DEVELOPMENT ONLY)
     */
    public function simulatePayment(Request $request, FaspayTestTransaction $testTransaction)
    {
        if (!app()->isLocal()) {
            return response()->json(['error' => 'This action is only available in development'], 403);
        }

        try {
            // Simulate payment notification
            $paymentData = [
                'trx_id' => 'SIM-' . Str::random(16),
                'bill_no' => $testTransaction->bill_no,
                'payment_status_code' => '2', // Success
                'payment_channel' => $request->input('payment_channel', 'QRIS'),
                'bank_user_name' => $request->input('bank_user_name', 'Test User'),
                'payment_date' => now()->format('Y-m-d H:i:s'),
                'payment_total' => $testTransaction->amount,
            ];

            // Mark as paid
            $testTransaction->markAsPaid($paymentData);

            return response()->json([
                'success' => true,
                'message' => 'Payment simulated successfully',
                'transaction' => $testTransaction,
                'redirect_url' => route('faspay.test-transaction.success', $testTransaction),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}
