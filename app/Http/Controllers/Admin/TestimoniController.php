<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TestimoniImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestimoniController extends Controller
{
    public function index()
    {
        $testimonis = TestimoniImage::orderBy('sort_order')->orderBy('created_at', 'desc')->get();
        return view('admin.testimoni.index', compact('testimonis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'images'   => 'required|array|min:1',
            'images.*' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'caption'  => 'nullable|string|max:255',
        ]);

        foreach ($request->file('images') as $file) {
            $path = $file->store('testimoni', 'public');
            TestimoniImage::create([
                'image_path' => $path,
                'caption'    => $request->caption,
                'sort_order' => 0,
                'is_active'  => true,
            ]);
        }

        return redirect()->route('admin.testimoni.index')->with('success', 'Gambar testimoni berhasil diunggah!');
    }

    public function destroy(TestimoniImage $testimoni)
    {
        Storage::disk('public')->delete($testimoni->image_path);
        $testimoni->delete();
        return redirect()->route('admin.testimoni.index')->with('success', 'Gambar berhasil dihapus!');
    }

    public function toggleActive(TestimoniImage $testimoni)
    {
        $testimoni->update(['is_active' => !$testimoni->is_active]);
        return redirect()->route('admin.testimoni.index')->with('success', 'Status diperbarui!');
    }
}
