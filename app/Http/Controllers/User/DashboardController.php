<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Response;
use App\Models\Survey;
use App\Models\Transaction;
use App\Models\DashboardBanner;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Statistik survey milik user
        $totalSurveys = Survey::where('user_id', $user->id)->count();
        $totalQuestions = Survey::where('user_id', $user->id)->sum('question_count');
        $totalTargetResponden = Survey::where('user_id', $user->id)->get()->sum('respondent_count');

        $responsesQuery = Response::whereNotNull('input_by_admin_id')
            ->whereHas('survey', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });

        $totalRespondenDiperoleh = (clone $responsesQuery)->sum('respond_count');
        $adminTerakhirInput = (clone $responsesQuery)
            ->with('inputByAdmin')
            ->latest('updated_at')
            ->first();
        
        // Statistik transaksi
        $totalTransactions = Transaction::where('user_id', $user->id)->count();
        $totalSpent = Transaction::where('user_id', $user->id)->where('status', Transaction::STATUS_PAID)->sum('amount');
        $pendingPayments = Transaction::where('user_id', $user->id)->where('status', Transaction::STATUS_PENDING)->sum('amount');
        $failedTransactions = Transaction::where('user_id', $user->id)->where('status', Transaction::STATUS_FAILED)->count();

        // Survey terbaru milik user
        $recentSurveys = Survey::where('user_id', $user->id)
            ->withSum('adminResponses', 'respond_count')
            ->with(['transactions' => function ($query) {
                $query->latest();
            }])
            ->latest()
            ->take(5)
            ->get();

        // Transaksi terakhir
        $recentTransactions = Transaction::where('user_id', $user->id)
            ->with('survey')
            ->latest()
            ->take(5)
            ->get();

        // Dashboard banners untuk slider (gambar saja)
        $banners = DashboardBanner::where('is_active', true)
            ->whereNotNull('image')
            ->where('image', '!=', '')
            ->orderBy('order')
            ->get();

        return view('user.dashboard.index', compact(
            'user',
            'totalSurveys',
            'totalQuestions',
            'totalTargetResponden',
            'totalRespondenDiperoleh',
            'adminTerakhirInput',
            'totalTransactions',
            'totalSpent',
            'pendingPayments',
            'failedTransactions',
            'recentSurveys',
            'recentTransactions',
            'banners'
        ));
    }
}
