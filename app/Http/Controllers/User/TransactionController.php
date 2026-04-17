<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Display a listing of user's transactions.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Transaction::where('user_id', $user->id)
            ->with(['survey' => function($q) {
                $q->select('id', 'title', 'question_count', 'respondent_count', 'form_link');
            }]);

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('from_date') && $request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->has('to_date') && $request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Sort
        $sort = $request->input('sort', 'latest');
        if ($sort === 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $transactions = $query->paginate(15);

        return view('user.transactions.index', compact('transactions'));
    }

    /**
     * Display the specified transaction.
     */
    public function show(Transaction $transaction)
    {
        // Ensure user owns this transaction
        if ($transaction->user_id != Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $transaction->load('survey');

        return view('user.transactions.show', compact('transaction'));
    }
}
