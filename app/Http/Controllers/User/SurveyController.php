<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SurveyController extends Controller
{
    /**
     * Display a listing of user's surveys.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Survey::where('user_id', $user->id)
            ->withCount('responses')
            ->with(['transactions' => function($q) {
                $q->latest()->limit(1);
            }]);

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            // Filter based on progress from transactions
            if ($request->status === 'completed') {
                $query->whereHas('transactions', function($q) {
                    $q->where('progress', 100);
                });
            } elseif ($request->status === 'in_progress') {
                $query->whereHas('transactions', function($q) {
                    $q->where('progress', '>', 0)->where('progress', '<', 100);
                });
            } elseif ($request->status === 'pending') {
                $query->whereHas('transactions', function($q) {
                    $q->where('progress', 0)->orWhereNull('progress');
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
        $request->validate([
            'title' => 'required|string|max:255',
            'question_count' => 'required|integer|min:1|max:100',
            'respondent_count' => 'required|integer|min:1|max:10000',
            'description' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();

        // Calculate cost
        $questionCost = $request->question_count * 1000;
        $respondentCost = $request->respondent_count * 1000;
        $totalCost = $questionCost + $respondentCost;

        // Create survey
        $survey = Survey::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'question_count' => $request->question_count,
        ]);

        // Create transaction
        Transaction::create([
            'survey_id' => $survey->id,
            'user_id' => $user->id,
            'amount' => $totalCost,
            'status' => 'pending',
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

        $survey->load(['responses', 'transactions' => function($q) {
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
        $hasPaidTransactions = $survey->transactions()->where('status', 'paid')->exists();
        
        if ($hasPaidTransactions) {
            return back()->with('error', 'Tidak dapat menghapus survey yang sudah dibayar.');
        }

        $survey->transactions()->delete();
        $survey->delete();

        return redirect()->route('user.surveys.index')
            ->with('success', 'Survey berhasil dihapus!');
    }
}
