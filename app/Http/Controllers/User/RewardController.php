<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PointTransaction;
use App\Models\RewardItem;
use App\Models\RewardRedemption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RewardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $pointBalance = $user->point_balance;
        $totalEarned = $user->total_points_earned;

        $rewardItems = RewardItem::available()
            ->orderBy('points_cost')
            ->get()
            ->groupBy('category');

        $recentPointHistory = PointTransaction::where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        $redemptions = RewardRedemption::where('user_id', $user->id)
            ->with('rewardItem')
            ->latest()
            ->take(10)
            ->get();

        return view('user.rewards.index', compact(
            'user',
            'pointBalance',
            'totalEarned',
            'rewardItems',
            'recentPointHistory',
            'redemptions'
        ));
    }

    public function redeem(Request $request, RewardItem $rewardItem)
    {
        $user = Auth::user();

        if (!$rewardItem->isAvailable()) {
            return back()->with('error', 'Reward ini sedang tidak tersedia.');
        }

        $pointBalance = $user->point_balance;

        if ($pointBalance < $rewardItem->points_cost) {
            return back()->with('error', 'Poin Anda tidak cukup. Butuh ' . number_format($rewardItem->points_cost, 0, ',', '.') . ' poin, saldo Anda ' . number_format($pointBalance, 0, ',', '.') . ' poin.');
        }

        // For tunai, payment info is required
        $phoneNumber = null;
        if ($rewardItem->category === RewardItem::CATEGORY_TUNAI) {
            $request->validate([
                'phone_number' => 'required|string|max:255',
            ]);
            $phoneNumber = $request->phone_number;
        } else {
            $phoneNumber = $request->phone_number;
        }

        DB::transaction(function () use ($user, $rewardItem, $phoneNumber) {
            // Deduct points
            $pointTx = PointTransaction::create([
                'user_id' => $user->id,
                'type' => PointTransaction::TYPE_REDEEM,
                'points' => $rewardItem->points_cost,
                'description' => 'Tukar poin: ' . $rewardItem->name,
            ]);

            // Create redemption record
            RewardRedemption::create([
                'user_id' => $user->id,
                'reward_item_id' => $rewardItem->id,
                'point_transaction_id' => $pointTx->id,
                'points_spent' => $rewardItem->points_cost,
                'status' => RewardRedemption::STATUS_PENDING,
                'phone_number' => $phoneNumber,
            ]);

            // Decrease stock if not unlimited
            if ($rewardItem->stock > 0) {
                $rewardItem->decrement('stock');
            }
        });

        return back()->with('success', 'Penukaran reward berhasil! Tim kami akan memproses dalam 1x24 jam.');
    }
}
