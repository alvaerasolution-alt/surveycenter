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

        $oldProgress = $transaction->progress;
        $transaction->progress = $request->progress;
        $transaction->save();
        
        $wantsToNotify = $request->filled('notification_message');
        $justCompleted = ($oldProgress < 100 && $request->progress == 100);
        
        if ($wantsToNotify || $justCompleted) {
            $msg = $request->notification_message;
            if (!$msg && $justCompleted) {
                $msg = 'Survey Anda "' . $transaction->survey->title . '" telah selesai (100%). Silakan periksa detailnya.';
            }
            
            if ($msg) {
                $transaction->user->notify(new \App\Notifications\SurveyCompletedNotification($transaction->survey, $msg));
            }
        }

        return redirect()->route('admin.transactions.progress.index')
            ->with('success', 'Progress berhasil diperbarui.');
    }
}
