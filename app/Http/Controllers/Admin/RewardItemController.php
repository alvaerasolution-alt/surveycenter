<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RewardItem;
use Illuminate\Http\Request;

class RewardItemController extends Controller
{
    public function index()
    {
        $items = RewardItem::latest()->paginate(20);

        return view('admin.reward-items.index', compact('items'));
    }

    public function create()
    {
        return view('admin.reward-items.form', [
            'item' => null,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'category'    => 'required|in:tunai,lainnya',
            'points_cost' => 'required|integer|min:1',
            'value'       => 'nullable|string|max:100',
            'stock'       => 'required|integer|min:-1',
        ]);

        RewardItem::create($data);

        return redirect()->route('admin.reward-items.index')->with('success', 'Reward item berhasil ditambahkan.');
    }

    public function edit(RewardItem $reward_item)
    {
        return view('admin.reward-items.form', [
            'item' => $reward_item,
        ]);
    }

    public function update(Request $request, RewardItem $reward_item)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'category'    => 'required|in:tunai,lainnya',
            'points_cost' => 'required|integer|min:1',
            'value'       => 'nullable|string|max:100',
            'stock'       => 'required|integer|min:-1',
        ]);

        $reward_item->update($data);

        return redirect()->route('admin.reward-items.index')->with('success', 'Reward item berhasil diperbarui.');
    }

    public function destroy(RewardItem $reward_item)
    {
        $reward_item->delete();

        return redirect()->route('admin.reward-items.index')->with('success', 'Reward item berhasil dihapus.');
    }
}
