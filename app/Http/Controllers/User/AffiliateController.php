<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AffiliateWithdrawal;
use App\Models\ReferralCommission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AffiliateController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $referralUrl = $user->referral_url;
        $referralCode = $user->referral_code;

        // Users who registered via this user's referral link
        $referrals = User::where('referred_by_id', $user->id)
            ->select('id', 'name', 'email', 'created_at')
            ->withCount(['transactions as paid_orders' => function ($q) {
                $q->where('status', 'paid');
            }])
            ->latest()
            ->get();

        $totalReferrals = $referrals->count();
        $totalWithOrders = $referrals->filter(fn ($r) => $r->paid_orders > 0)->count();

        // Commission history
        $commissions = ReferralCommission::where('referrer_id', $user->id)
            ->with(['referredUser:id,name,email', 'transaction:id,amount'])
            ->latest()
            ->take(20)
            ->get();

        $totalCommissionEarned = (int) ReferralCommission::where('referrer_id', $user->id)->sum('commission_amount');
        $affiliateBalance = $user->affiliate_balance;

        // Withdrawal history
        $withdrawals = AffiliateWithdrawal::where('user_id', $user->id)
            ->latest()
            ->take(20)
            ->get();

        $pendingWithdrawal = (int) AffiliateWithdrawal::where('user_id', $user->id)
            ->where('status', AffiliateWithdrawal::STATUS_PENDING)
            ->sum('amount');

        return view('user.affiliate.index', compact(
            'user',
            'referralUrl',
            'referralCode',
            'referrals',
            'totalReferrals',
            'totalWithOrders',
            'commissions',
            'totalCommissionEarned',
            'affiliateBalance',
            'withdrawals',
            'pendingWithdrawal'
        ));
    }

    public function withdraw(Request $request)
    {
        $request->validate([
            'amount'              => 'required|integer|min:100000',
            'bank_name'           => 'required|string|max:100',
            'account_number'      => 'required|string|max:50',
            'account_holder_name' => 'required|string|max:150',
        ]);

        $user = Auth::user();
        $balance = $user->affiliate_balance;

        if ($request->amount > $balance) {
            return back()->withErrors(['amount' => 'Saldo tidak mencukupi. Saldo tersedia: Rp ' . number_format($balance, 0, ',', '.')])->withInput();
        }

        AffiliateWithdrawal::create([
            'user_id'             => $user->id,
            'amount'              => $request->amount,
            'bank_name'           => $request->bank_name,
            'account_number'      => $request->account_number,
            'account_holder_name' => $request->account_holder_name,
            'status'              => AffiliateWithdrawal::STATUS_PENDING,
        ]);

        return back()->with('success', 'Permintaan withdrawal Rp ' . number_format($request->amount, 0, ',', '.') . ' berhasil dikirim. Menunggu persetujuan admin.');
    }
}
