<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AffiliateWithdrawal;
use Illuminate\Http\Request;

class AffiliateWithdrawalController extends Controller
{
    public function index()
    {
        $withdrawals = AffiliateWithdrawal::with('user:id,name,email')
            ->latest()
            ->paginate(20);

        $pendingCount = AffiliateWithdrawal::where('status', AffiliateWithdrawal::STATUS_PENDING)->count();

        return view('admin.affiliate-withdrawals.index', compact('withdrawals', 'pendingCount'));
    }

    public function approve(AffiliateWithdrawal $withdrawal)
    {
        if (!$withdrawal->isPending()) {
            return back()->with('error', 'Withdrawal ini sudah diproses.');
        }

        $withdrawal->update([
            'status' => AffiliateWithdrawal::STATUS_APPROVED,
            'processed_at' => now(),
        ]);

        return back()->with('success', 'Withdrawal Rp ' . number_format($withdrawal->amount, 0, ',', '.') . ' untuk ' . $withdrawal->user->name . ' berhasil di-approve.');
    }

    public function reject(Request $request, AffiliateWithdrawal $withdrawal)
    {
        if (!$withdrawal->isPending()) {
            return back()->with('error', 'Withdrawal ini sudah diproses.');
        }

        $request->validate([
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $withdrawal->update([
            'status' => AffiliateWithdrawal::STATUS_REJECTED,
            'admin_notes' => $request->admin_notes,
            'processed_at' => now(),
        ]);

        return back()->with('success', 'Withdrawal berhasil di-reject.');
    }
}
