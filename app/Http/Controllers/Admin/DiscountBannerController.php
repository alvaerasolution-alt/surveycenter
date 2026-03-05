<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiscountBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DiscountBannerController extends Controller
{
    public function index()
    {
        $banners = DiscountBanner::orderBy('order')->get();
        return view('admin.discount_banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.discount_banners.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'subtitle' => 'nullable|string',
            'button_text' => 'nullable|string',
            'button_link' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'background' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('discounts', 'public');
        }

        DiscountBanner::create($data);

        return redirect()->route('admin.discount-banners.index')->with('success', 'Banner berhasil ditambahkan!');
    }

    public function edit(DiscountBanner $discountBanner)
    {
        return view('admin.discount_banners.edit', compact('discountBanner'));
    }

    public function update(Request $request, DiscountBanner $discountBanner)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'subtitle' => 'nullable|string',
            'button_text' => 'nullable|string',
            'button_link' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'background' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            if ($discountBanner->image) {
                Storage::disk('public')->delete($discountBanner->image);
            }
            $data['image'] = $request->file('image')->store('discounts', 'public');
        }

        $discountBanner->update($data);

        return redirect()->route('admin.discount-banners.index')->with('success', 'Banner berhasil diperbarui!');
    }

    public function destroy(DiscountBanner $discountBanner)
    {
        if ($discountBanner->image) {
            Storage::disk('public')->delete($discountBanner->image);
        }
        $discountBanner->delete();

        return redirect()->route('admin.discount-banners.index')->with('success', 'Banner berhasil dihapus!');
    }
}
