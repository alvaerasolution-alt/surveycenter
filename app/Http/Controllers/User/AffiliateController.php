<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ReferralCommission;
use App\Models\User;
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

        $totalCommissionPoints = ReferralCommission::where('referrer_id', $user->id)->sum('points_earned');

        return view('user.affiliate.index', compact(
            'user',
            'referralUrl',
            'referralCode',
            'referrals',
            'totalReferrals',
            'totalWithOrders',
            'commissions',
            'totalCommissionPoints'
        ));
    }
}
