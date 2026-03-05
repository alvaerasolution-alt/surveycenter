<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Layanan;

class LayananController extends Controller
{
    public function show($slug)
    {
        // Ambil layanan dari database
        $layanan = Layanan::where('slug', $slug)->firstOrFail();

        $jenis = Layanan::where('category', 'jenis')->get();
        $tambahan = Layanan::where('category', 'tambahan')->get();

        // Render ke view
        return view('pages-layanan.show', compact('layanan','jenis','tambahan'));
    }
}
