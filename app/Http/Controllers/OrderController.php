<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;

class OrderController extends Controller
{
    public function index()
    {
        // Ambil transaksi user yang sudah paid
        $transactions = Transaction::with('survey')
            ->where('user_id', auth::id())
            ->where('status', 'paid') // Hanya transaksi yang sudah dibayar
            ->latest()
            ->get();

        return view('orders.index', compact('transactions'));
    }
}
