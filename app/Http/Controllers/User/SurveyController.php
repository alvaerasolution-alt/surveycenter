<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use App\Models\Transaction;
use App\Services\FormLinkValidationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SurveyController extends Controller
{
    public function __construct(private FormLinkValidationService $formLinkValidationService)
    {
    }

    /**
     * Display a listing of user's surveys.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Survey::where('user_id', $user->id)
            ->withSum('adminResponses', 'respond_count')
            ->with(['transactions' => function($q) {
                $q->latest()->limit(1);
            }]);

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            // Filter based on main stage progress and payment status
            if ($request->status === 'completed') {
                $query->whereHas('transactions', function($q) {
                    $q->where('progress', '>=', 100);
                });
            } elseif ($request->status === 'in_progress') {
                $query->whereHas('transactions', function($q) {
                    $q->whereIn('status', [Transaction::STATUS_PROCESSING, Transaction::STATUS_PAID])
                      ->where('progress', '<', 100);
                });
            } elseif ($request->status === 'pending') {
                $query->where(function($subQuery) {
                    $subQuery->whereDoesntHave('transactions')
                        ->orWhereHas('transactions', function($q) {
                            $q->where('status', Transaction::STATUS_PENDING);
                        });
                });
            }
        }

        // Search
        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $surveys = $query->latest()->paginate(10);

        return view('user.surveys.index', compact('surveys'));
    }

    /**
     * Show the form for creating a new survey.
     */
    public function create()
    {
        return view('user.surveys.create');
    }

    /**
     * Store a newly created survey.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'question_count' => 'required|integer|min:1|max:100',
            'respondent_count' => 'required|integer|min:1|max:10000',
            'form_link' => 'required|url|max:2048',
            'description' => 'nullable|string|max:1000',
        ]);

        $formLinkError = $this->formLinkValidationService->validate(
            $validated['form_link'] ?? null,
            $validated['title']
        );

        if ($formLinkError !== null) {
            throw ValidationException::withMessages(['form_link' => $formLinkError]);
        }

        $user = Auth::user();

        // Calculate cost
        $questionCost = $validated['question_count'] * 1000;
        $respondentCost = $validated['respondent_count'] * 1000;
        $totalCost = $questionCost + $respondentCost;

        // Create survey
        $survey = Survey::create([
            'user_id' => $user->id,
            'title' => $validated['title'],
            'question_count' => $validated['question_count'],
            'respondent_count' => $validated['respondent_count'],
        ]);

        // Create transaction
        Transaction::create([
            'survey_id' => $survey->id,
            'user_id' => $user->id,
            'amount' => $totalCost,
            'status' => Transaction::STATUS_PENDING,
            'progress' => 0,
        ]);

        return redirect()->route('user.surveys.show', $survey)
            ->with('success', 'Survey berhasil dibuat! Silakan lakukan pembayaran untuk memulai.');
    }

    /**
     * Display the specified survey.
     */
    public function show(Survey $survey)
    {
        // Ensure user owns this survey
        if ($survey->user_id != Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $survey->load(['responses', 'adminResponses', 'transactions' => function($q) {
            $q->latest();
        }]);

        $latestTransaction = $survey->transactions->first();

        return view('user.surveys.show', compact('survey', 'latestTransaction'));
    }

    /**
     * Show the form for editing the survey.
     */
    public function edit(Survey $survey)
    {
        if ($survey->user_id != Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('user.surveys.edit', compact('survey'));
    }

    /**
     * Update the specified survey.
     */
    public function update(Request $request, Survey $survey)
    {
        if ($survey->user_id != Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $survey->update([
            'title' => $request->title,
        ]);

        return redirect()->route('user.surveys.show', $survey)
            ->with('success', 'Survey berhasil diperbarui!');
    }

    /**
     * Remove the specified survey.
     */
    public function destroy(Survey $survey)
    {
        if ($survey->user_id != Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Only allow delete if no transactions or all transactions are pending
        $hasPaidTransactions = $survey->transactions()->where('status', Transaction::STATUS_PAID)->exists();
        
        if ($hasPaidTransactions) {
            return back()->with('error', 'Tidak dapat menghapus survey yang sudah dibayar.');
        }

        $survey->transactions()->delete();
        $survey->delete();

        return redirect()->route('user.surveys.index')
            ->with('success', 'Survey berhasil dihapus!');
    }

    /**
     * Export survey details to PDF
     */
    public function exportPdf(Survey $survey)
    {
        // Ensure user owns this survey
        if ($survey->user_id != Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Load relationships
        $survey->load(['responses', 'adminResponses', 'transactions' => function($q) {
            $q->latest();
        }]);

        // Generate PDF
        $pdf = Pdf::loadView('user.surveys.export-pdf', [
            'survey' => $survey,
            'transactions' => $survey->transactions,
            'responses' => $survey->responses,
            'adminResponses' => $survey->adminResponses,
            'user' => Auth::user(),
            'generatedAt' => now(),
        ])
        ->setPaper('a4')
        ->setOption('margin-top', 10)
        ->setOption('margin-bottom', 10)
        ->setOption('margin-left', 10)
        ->setOption('margin-right', 10);

        return $pdf->download("survey-{$survey->id}-" . now()->format('Y-m-d-His') . ".pdf");
    }

    /**
     * Export survey responses to PDF
     */
    public function exportResponsesPdf(Survey $survey)
    {
        // Ensure user owns this survey
        if ($survey->user_id != Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Load responses
        $survey->load('responses');

        if ($survey->responses->isEmpty()) {
            return back()->with('error', 'Tidak ada respons untuk diekspor.');
        }

        // Generate PDF
        $pdf = Pdf::loadView('user.surveys.export-responses-pdf', [
            'survey' => $survey,
            'responses' => $survey->responses,
            'user' => Auth::user(),
            'generatedAt' => now(),
        ])
        ->setPaper('a4', 'landscape')
        ->setOption('margin-top', 10)
        ->setOption('margin-bottom', 10)
        ->setOption('margin-left', 10)
        ->setOption('margin-right', 10);

        return $pdf->download("survey-responses-{$survey->id}-" . now()->format('Y-m-d-His') . ".pdf");
    }
}
