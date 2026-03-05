<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TabController extends Controller
{
    public function index()
    {
        $tabs = Tab::orderBy('order')->get();
        return view('admin.tabs.index', compact('tabs'));
    }


    public function create()
    {
        return view('admin.tabs.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'button_text' => 'nullable|string',
            'button_link' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'order' => 'nullable|integer'
        ]);

        // Tambahkan slug dari title
        $data['slug'] = Str::slug($request->title);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('tabs', 'public');
        }

        Tab::create($data);
        return redirect()->route('tabs.index')->with('success', 'Tab berhasil ditambahkan!');
    }

    public function edit(Tab $tab)
    {
        return view('admin.tabs.edit', compact('tab'));
    }

    public function update(Request $request, Tab $tab)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'button_text' => 'nullable|string',
            'button_link' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'order' => 'nullable|integer'
        ]);

        if ($request->hasFile('image')) {
            if ($tab->image) {
                Storage::disk('public')->delete($tab->image);
            }
            $data['image'] = $request->file('image')->store('tabs', 'public');
        }

        $tab->update($data);
        return redirect()->route('tabs.index')->with('success', 'Tab berhasil diperbarui!');
    }

    public function destroy(Tab $tab)
    {
        if ($tab->image) {
            Storage::disk('public')->delete($tab->image);
        }
        $tab->delete();
        return redirect()->route('tabs.index')->with('success', 'Tab berhasil dihapus!');
    }
}
