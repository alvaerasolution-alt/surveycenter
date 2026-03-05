<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionProgressController extends Controller
{
    public function show(Transaction $transaction)
    {
        if (Auth::id() !== $transaction->user_id && !Auth::user()) {
            abort(403, 'Unauthorized');
        }

        return view('transactions.progress', [
            'transaction' => $transaction
        ]);
    }
}
