<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Statistik survey milik user
        $totalSurveys = Survey::where('user_id', $user->id)->count();
        $totalQuestions = Survey::where('user_id', $user->id)->sum('question_count');
        $totalResponden = \App\Models\Response::where('user_id', $user->id)->sum('respond_count');
        
        // Statistik transaksi
        $totalTransactions = Transaction::where('user_id', $user->id)->count();
        $totalSpent = Transaction::where('user_id', $user->id)->where('status', 'paid')->sum('amount');
        $pendingPayments = Transaction::where('user_id', $user->id)->where('status', 'pending')->sum('amount');

        // Survey terbaru milik user
        $recentSurveys = Survey::where('user_id', $user->id)
            ->withSum('responses', 'respond_count')
            ->with('transactions')
            ->latest()
            ->take(5)
            ->get();

        // Transaksi terakhir
        $recentTransactions = Transaction::where('user_id', $user->id)
            ->with('survey')
            ->latest()
            ->take(5)
            ->get();

        return view('user.dashboard.index', compact(
            'user',
            'totalSurveys',
            'totalQuestions',
            'totalResponden',
            'totalTransactions',
            'totalSpent',
            'pendingPayments',
            'recentSurveys',
            'recentTransactions'
        ));
    }
}
