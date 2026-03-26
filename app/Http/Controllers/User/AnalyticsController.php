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
            ->withCount('responses')
            ->with(['transactions' => function ($q) {
                $q->latest()->limit(1);
            }])
            ->latest()
            ->get();

        // Calculate key metrics
        $totalSurveys = $surveys->count();
        $totalResponses = $surveys->sum('responses_count');
        $totalSpending = Transaction::where('user_id', $user->id)->sum('amount');
        $paidTransactions = Transaction::where('user_id', $user->id)
            ->where('status', 'paid')
            ->sum('amount');

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
            return !$latestTransaction || $latestTransaction->progress === 0;
        })->count();

        // Revenue breakdown by month
        $revenueByMonth = Transaction::where('user_id', $user->id)
            ->where('status', 'paid')
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(amount) as total')
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->orderByRaw('year DESC, month DESC')
            ->limit(6)
            ->get()
            ->reverse();

        // Response trends
        $responseTrends = DB::table('responses')
            ->whereIn('survey_id', $surveys->pluck('id')->toArray())
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupByRaw('DATE(created_at)')
            ->orderByRaw('date DESC')
            ->limit(30)
            ->get()
            ->reverse();

        // Top performing surveys
        $topSurveys = $surveys
            ->map(function ($survey) {
                return [
                    'survey' => $survey,
                    'responses' => $survey->responses_count,
                    'transaction' => $survey->transactions->first(),
                ];
            })
            ->sortByDesc('responses')
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
            'totalResponses',
            'totalSpending',
            'paidTransactions',
            'completedSurveys',
            'inProgressSurveys',
            'pendingSurveys',
            'surveys',
            'revenueByMonth',
            'responseTrends',
            'topSurveys',
            'transactionStats'
        ));
    }
}
