<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class AdminTransactionProgressController extends Controller
{
    public function edit(Transaction $transaction)
    {
        if ($transaction->status !== Transaction::STATUS_PAID) {
            return redirect()->back()->with('error', 'Hanya transaksi yang sudah dibayar yang bisa diupdate progress.');
        }

        return view('admin.transactions.progress', compact('transaction'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'progress' => 'required|integer|min:0|max:100',
        ]);

        if ($transaction->status !== Transaction::STATUS_PAID) {
            return redirect()->back()->with('error', 'Hanya transaksi yang sudah dibayar yang bisa diupdate progress.');
        }

        $transaction->progress = $request->progress;
        $transaction->save();

        return redirect()->back()->with('success', 'Progress berhasil diperbarui.');
    }
}
