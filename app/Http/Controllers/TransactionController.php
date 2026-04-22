<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\FaspayService;
use App\Services\FormLinkValidationService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Response;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    private FaspayService $faspayService;
    private FormLinkValidationService $formLinkValidationService;

    public function __construct(FaspayService $faspayService, FormLinkValidationService $formLinkValidationService)
    {
        $this->faspayService = $faspayService;
        $this->formLinkValidationService = $formLinkValidationService;
    }

    public function create(Survey $survey)
    {
        // Tampilkan form transaksi berdasarkan survey yang dipilih
        return view('transactions.create', compact('survey'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'question_count' => 'required|integer|min:1',
            'respondent_count' => 'required|integer|min:1',
            'items' => 'required|string',
            'link' => 'required|url|max:2048',
            'user_type' => 'required|in:mahasiswa,perusahaan,umum'
        ]);

        $pricePerQuestion = 1000;
        $baseTotal = $validated['question_count'] * $validated['respondent_count'] * $pricePerQuestion;

        $discount = 0;

        if ($validated['user_type'] === 'mahasiswa') {
            $discount = $baseTotal * 0.5;
        } elseif ($validated['user_type'] === 'perusahaan') {
            $discount = $baseTotal * 0.3;
        }

        $finalPrice = $baseTotal - $discount;

        if ($finalPrice < 100000) {
            abort(403, 'Minimal total pembayaran adalah Rp 100.000');
        }

        $survey = Survey::create([
            'title' => $validated['title'],
            'question_count' => $validated['question_count'],
            'respondent_count' => $validated['respondent_count'],
            'user_id' => Auth::id(),
        ]);

        Response::create([
            'survey_id' => $survey->id,
            'user_id' => Auth::id(),
            'respond_count' => $validated['respondent_count'],
            'google_form_link' => $validated['link'] ?? null,
        ]);

        if ((bool) config('payment_gateways.mock_mode', false)) {
            $mockStatus = $this->resolveMockStatus();

            $transaction = Transaction::create([
                'survey_id' => $survey->id,
                'user_id' => Auth::id(),
                'amount' => $finalPrice,
                'status' => $mockStatus,
                'singapay_ref' => 'MOCK-' . Str::upper(Str::random(12)),
            ]);

            return redirect()->route('transactions.progress', $transaction)
                ->with('success', 'Mock payment dibuat dengan status: ' . $mockStatus . '.');
        }

        // Create Faspay invoice (same flow as kost project)
        $billNo = 'TRX-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(6));

        $invoiceData = [
            'bill_no' => $billNo,
            'bill_total' => $finalPrice,
            'bill_description' => $validated['title'] ?? 'Survey Payment',
            'cust_name' => Auth::user()->name ?? 'Customer',
            'cust_email' => Auth::user()->email ?? '',
            'cust_phone' => Auth::user()->phone ?? '081234567890',
            'bill_expired_date' => now()->addMinutes((int) config('faspay.invoice_expiration', 30))->format('Y-m-d H:i:s'),
            'return_url' => route('faspay.return'),
        ];

        $response = $this->faspayService->createInvoice($invoiceData);

        if (!($response['success'] ?? false) || empty($response['payment_url'])) {
            Log::error('Transaction Failed (Faspay)', [
                'user_id' => Auth::id(),
                'error' => $response['message'] ?? 'Unknown error',
                'response' => $response,
            ]);
            return back()->with('error', 'Gagal membuat pembayaran: ' . ($response['message'] ?? 'Kesalahan tidak diketahui.'));
        }

        Transaction::create([
            'survey_id' => $survey->id,
            'user_id' => Auth::id(),
            'amount' => $finalPrice,
            'status' => Transaction::STATUS_PROCESSING,
            'bill_no' => $billNo,
            'payment_ref' => $billNo,
            'trx_id' => $response['trx_id'] ?? null,
        ]);

        return redirect($response['payment_url']);
    }

    public function handleInvoice(Request $request)
    {
        // Faspay webhook is handled by FaspayController::notification()
        return response()->json(['status' => 'ok']);
    }

    public function history()
    {
        $transactions = Transaction::with('survey')
            ->where('user_id', auth::id())
            ->latest()
            ->paginate(10);

        return view('transactions.history', compact('transactions'));
    }

    public function payment(Transaction $transaction)
    {
        return view('transactions.payment', [
            'transaction' => $transaction,
        ]);
    }

    public function processPayment(Request $request, Transaction $transaction)
    {
        $request->validate([
            'payment_method' => 'required|in:qris,transfer,gopay,virtual_account,e_wallet',
        ]);

        $transaction->update([
            'payment_method' => $request->payment_method,
        ]);

        if ((bool) config('payment_gateways.mock_mode', false)) {
            $transaction->update([
                'status' => $this->resolveMockStatus(),
            ]);

            return redirect()->route('transactions.progress', $transaction)
                ->with('success', 'Mock payment diproses tanpa gateway eksternal.');
        }

        // Create Faspay invoice for all payment methods (same as kost project)
        $billNo = 'TRX-' . $transaction->id . '-' . now()->format('YmdHis');

        $invoiceData = [
            'bill_no' => $billNo,
            'bill_total' => $transaction->amount,
            'bill_description' => $transaction->survey->title ?? 'Survey Payment',
            'cust_name' => Auth::user()->name ?? 'Customer',
            'cust_email' => Auth::user()->email ?? '',
            'cust_phone' => Auth::user()->phone ?? '081234567890',
            'bill_expired_date' => now()->addMinutes((int) config('faspay.invoice_expiration', 30))->format('Y-m-d H:i:s'),
            'return_url' => route('faspay.return'),
        ];

        $response = $this->faspayService->createInvoice($invoiceData);

        if (!($response['success'] ?? false) || empty($response['payment_url'])) {
            Log::error('Faspay processPayment failed', [
                'transaction_id' => $transaction->id,
                'response' => $response,
            ]);

            return back()->with('error', 'Gagal membuat pembayaran: ' . ($response['message'] ?? 'Kesalahan tidak diketahui.'));
        }

        $transaction->update([
            'bill_no' => $billNo,
            'payment_ref' => $billNo,
            'trx_id' => $response['trx_id'] ?? null,
            'status' => Transaction::STATUS_PROCESSING,
        ]);

        // Redirect to Faspay payment page (handles all payment methods)
        return redirect($response['payment_url']);
    }

    private function resolveMockStatus(): string
    {
        $defaultStatus = (string) config('payment_gateways.mock_default_status', Transaction::STATUS_PAID);
        $allowedStatuses = [
            Transaction::STATUS_PENDING,
            Transaction::STATUS_PROCESSING,
            Transaction::STATUS_PAID,
            Transaction::STATUS_FAILED,
        ];

        return in_array($defaultStatus, $allowedStatuses, true)
            ? $defaultStatus
            : Transaction::STATUS_PAID;
    }

    public function showTransfer(Transaction $transaction)
    {
        return view('transactions.transfer', compact('transaction'));
    }

    public function invoice(Transaction $transaction)
    {
        return view('transactions.invoice', compact('transaction'));
    }

    public function download(Transaction $transaction)
    {
        $pdf = Pdf::loadView('transactions.invoice_pdf', compact('transaction'));
        return $pdf->download("invoice-{$transaction->id}.pdf");
    }

    public function cart()
    {
        $transactions = Transaction::with('survey')
            ->where('user_id', auth::id())
            ->latest()
            ->get();

        return view('cart.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        $qrImage = QrCode::size(300)->generate($transaction->qr_data);
        return view('transactions.show', compact('transaction', 'qrImage'));
    }
}
