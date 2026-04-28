<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RewardRedemption;
use Illuminate\Http\Request;

class RewardRedemptionController extends Controller
{
    public function index(Request $request)
    {
        $query = RewardRedemption::with(['user', 'rewardItem'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $redemptions = $query->paginate(20)->withQueryString();

        return view('admin.reward-redemptions.index', compact('redemptions'));
    }

    public function updateStatus(Request $request, RewardRedemption $redemption)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,rejected',
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $redemption->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
        ]);

        return redirect()->route('admin.reward-redemptions.index')
            ->with('success', 'Status penukaran berhasil diperbarui.');
    }
}
