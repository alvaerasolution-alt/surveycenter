<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionProgressController extends Controller
{
    /**
     * Menampilkan daftar transaksi yang sudah dibayar (paid)
     */
    public function index()
    {
        $transactions = Transaction::with(['survey', 'user'])
            ->where('status', 'paid')
            ->orderByDesc('updated_at')
            ->paginate(10);

        // View baru di folder admin/crmTransaction
        return view('admin.crmTransaction.index', compact('transactions'));
    }

    /**
     * Menampilkan form update progress transaksi
     */
    public function edit(Transaction $transaction)
    {
        if ($transaction->status !== 'paid') {
            return redirect()->route('admin.transactions.progress.index')
                ->with('error', 'Hanya transaksi yang sudah dibayar yang bisa diupdate progress.');
        }

        // View baru di folder admin/crmTransaction
        return view('admin.crmTransaction.progress', compact('transaction'));
    }

    /**
     * Update progress transaksi
     */
    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'progress' => 'required|integer|min:0|max:100',
        ]);

        if ($transaction->status !== 'paid') {
            return redirect()->route('admin.transactions.progress.index')
                ->with('error', 'Hanya transaksi yang sudah dibayar yang bisa diupdate progress.');
        }

        $transaction->progress = $request->progress;
        $transaction->save();

        return redirect()->route('admin.transactions.progress.index')
            ->with('success', 'Progress berhasil diperbarui.');
    }
}
