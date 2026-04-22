<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\SingaPayService;
use App\Services\FormLinkValidationService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Http\Controllers\SingaPayController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Response;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    private SingaPayService $singaPay;
    private FormLinkValidationService $formLinkValidationService;

    public function __construct(SingaPayService $singaPay, FormLinkValidationService $formLinkValidationService)
    {
        $this->singaPay = $singaPay;
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

        $items = json_decode($validated['items'], true);

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

        $invoice = $this->singaPay->createInvoice($finalPrice, $items);

        if (!isset($invoice['success']) || !$invoice['success']) {
            Log::error('Transaction Failed', ['user_id' => Auth::id(), 'error' => $invoice['message'] ?? 'Unknown error']);
            return back()->with('error', 'Gagal membuat pembayaran: ' . ($invoice['message'] ?? 'Kesalahan tidak diketahui.'));
        }

        Transaction::create([
            'survey_id' => $survey->id,
            'user_id' => Auth::id(),
            'amount' => $finalPrice,
            'status' => Transaction::STATUS_PENDING,
            'singapay_ref' => $invoice['reff_no']
        ]);

        return redirect($invoice['payment_url']);
    }

    public function handleInvoice(Request $request)
    {
        $data = $this->singaPay->webhook($request);
        return response()->json($data);
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
        if (empty($transaction->qr_data)) {
            $singapay = app(\App\Http\Controllers\SingaPayController::class);
            $qrData   = $singapay->generateQris($transaction);

            if ($qrData) {
                $transaction->update([
                    'qr_data' => $qrData,
                ]);
            }
        }

        return view('transactions.payment', [
            'transaction' => $transaction,
        ]);
    }



    public function processPayment(Request $request, Transaction $transaction)
    {
        $request->validate([
            'payment_method' => 'required|in:qris,transfer,gopay',
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

        if ($request->payment_method === 'qris') {
            // Redirect ke controller SingaPay untuk QRIS
            return redirect()->route('singapay.pay', $transaction->id);
        }

        if ($request->payment_method === 'gopay') {
            // Redirect ke halaman GoPay (saat ini placeholder)
            return redirect()->route('transactions.showTransfer', $transaction->id);
        }

        // Transfer VA (BCA/BNI)
        return redirect()->route('transactions.showTransfer', $transaction->id);
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
