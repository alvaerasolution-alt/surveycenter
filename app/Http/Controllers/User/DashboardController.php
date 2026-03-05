<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Survey;


class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Data dummy untuk statistik
        $totalSurveys = \App\Models\Survey::count();
        $totalQuestions = \App\Models\Survey::sum('question_count');
        $totalCost = ($totalQuestions * 1000);

        return view('user.dashboard.dashboard', [
            'totalSurveys' => $totalSurveys,
            'totalQuestions' => $totalQuestions,
            'totalCost' => $totalCost,
        ]);
    }
}
