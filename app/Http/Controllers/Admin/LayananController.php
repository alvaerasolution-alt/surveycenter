<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Layanan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class LayananController extends Controller
{
    public function index()
    {
        $layanans = Layanan::latest()->paginate(10);
        return view('admin.layanan.index', compact('layanans'));
    }

    public function create()
    {
        return view('admin.layanan.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'title'       => 'required|string|max:255',
        'description' => 'required|string',
        'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'category'    => 'nullable|string|max:100',
    ]);

    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('layanan', 'public');
    }

    Layanan::create([
        'title'       => $request->title,
        'slug'        => Str::slug($request->title),
        'description' => $request->description,
        'image'       => $imagePath,
        'category'    => $request->category,
    ]);

    return redirect()->route('admin.layanan.index')
        ->with('success', 'Layanan berhasil ditambahkan.');
}


    public function edit(Layanan $layanan)
    {
        return view('admin.layanan.edit', compact('layanan'));
    }

    public function update(Request $request, Layanan $layanan)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = [
            'title'       => $request->title,
            'slug'        => Str::slug($request->title),
            'description' => $request->description,
        ];

        if ($request->hasFile('image')) {
            if ($layanan->image) {
                Storage::disk('public')->delete($layanan->image);
            }
            $data['image'] = $request->file('image')->store('layanan', 'public');
        }

        $layanan->update($data);

        return redirect()->route('admin.layanan.index')
            ->with('success', 'Layanan berhasil diperbarui.');
    }

    public function destroy(Layanan $layanan)
    {
        if ($layanan->image) {
            Storage::disk('public')->delete($layanan->image);
        }
        $layanan->delete();

        return redirect()->route('admin.layanan.index')
            ->with('success', 'Layanan berhasil dihapus.');
    }
}
