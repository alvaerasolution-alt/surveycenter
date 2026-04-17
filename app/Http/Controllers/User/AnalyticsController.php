<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Display analytics dashboard
     */
    public function index()
    {
        $user = Auth::user();

        // Get user's surveys with stats
        $surveys = Survey::where('user_id', $user->id)
            ->withSum('adminResponses', 'respond_count')
            ->with(['transactions' => function ($q) {
                $q->latest()->limit(1);
            }])
            ->latest()
            ->get();

        // Calculate key metrics
        $totalSurveys = $surveys->count();
        $totalTargetResponses = $surveys->sum('respondent_count');
        $totalObtainedResponses = $surveys->sum(function ($survey) {
            return (int) ($survey->admin_responses_sum_respond_count ?? 0);
        });
        $responseAchievementRate = $totalTargetResponses > 0
            ? round(($totalObtainedResponses / $totalTargetResponses) * 100)
            : 0;

        $totalSpending = Transaction::where('user_id', $user->id)->sum('amount');
        $paidTransactions = Transaction::where('user_id', $user->id)
            ->where('status', Transaction::STATUS_PAID)
            ->sum('amount');

        // Main stage completion breakdown
        $stage1CompletedSurveys = $surveys->filter(function ($survey) {
            $latestTransaction = $survey->transactions->first();
            return $latestTransaction && in_array($latestTransaction->status, [Transaction::STATUS_PROCESSING, Transaction::STATUS_PAID], true);
        })->count();

        $stage2CompletedSurveys = $surveys->filter(function ($survey) {
            $latestTransaction = $survey->transactions->first();
            return $latestTransaction && $latestTransaction->progress >= 100;
        })->count();

        // Survey status breakdown
        $completedSurveys = $surveys->filter(function ($survey) {
            $latestTransaction = $survey->transactions->first();
            return $latestTransaction && $latestTransaction->progress >= 100;
        })->count();

        $inProgressSurveys = $surveys->filter(function ($survey) {
            $latestTransaction = $survey->transactions->first();
            return $latestTransaction && $latestTransaction->progress > 0 && $latestTransaction->progress < 100;
        })->count();

        $pendingSurveys = $surveys->filter(function ($survey) {
            $latestTransaction = $survey->transactions->first();
            return !$latestTransaction || $latestTransaction->status === Transaction::STATUS_PENDING;
        })->count();

        $verificationSurveys = $surveys->filter(function ($survey) {
            $latestTransaction = $survey->transactions->first();
            return $latestTransaction && $latestTransaction->status === Transaction::STATUS_PROCESSING;
        })->count();

        // Revenue breakdown by month
        $revenueByMonth = Transaction::where('user_id', $user->id)
            ->where('status', Transaction::STATUS_PAID)
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(amount) as total')
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->orderByRaw('year DESC, month DESC')
            ->limit(6)
            ->get()
            ->reverse();

        // Response trends
        $responseTrends = DB::table('responses')
            ->whereIn('survey_id', $surveys->pluck('id')->toArray())
            ->whereNotNull('input_by_admin_id')
            ->selectRaw('DATE(created_at) as date, SUM(respond_count) as count')
            ->groupByRaw('DATE(created_at)')
            ->orderByRaw('date DESC')
            ->limit(30)
            ->get()
            ->reverse();

        // Top performing surveys
        $topSurveys = $surveys
            ->map(function ($survey) {
                $latestTransaction = $survey->transactions->first();

                return [
                    'survey' => $survey,
                    'target_responses' => (int) $survey->respondent_count,
                    'obtained_responses' => (int) ($survey->admin_responses_sum_respond_count ?? 0),
                    'transaction' => $latestTransaction,
                    'stage1_done' => $latestTransaction && in_array($latestTransaction->status, [Transaction::STATUS_PROCESSING, Transaction::STATUS_PAID], true),
                    'stage2_done' => $latestTransaction && $latestTransaction->progress >= 100,
                ];
            })
            ->sortByDesc('obtained_responses')
            ->take(5)
            ->values();

        // Transaction status breakdown
        $transactionStats = Transaction::where('user_id', $user->id)
            ->selectRaw('status, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        return view('user.analytics.index', compact(
            'totalSurveys',
            'totalTargetResponses',
            'totalObtainedResponses',
            'responseAchievementRate',
            'totalSpending',
            'paidTransactions',
            'stage1CompletedSurveys',
            'stage2CompletedSurveys',
            'completedSurveys',
            'inProgressSurveys',
            'pendingSurveys',
            'verificationSurveys',
            'surveys',
            'revenueByMonth',
            'responseTrends',
            'topSurveys',
            'transactionStats'
        ));
    }
}
