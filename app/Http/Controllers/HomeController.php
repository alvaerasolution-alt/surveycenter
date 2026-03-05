<?php

namespace App\Http\Controllers;

use App\Models\Tab;
use App\Models\Article;
use App\Models\Layanan;
use App\Models\Customer;
use App\Models\FollowUp;
use App\Models\PartnerLogo;
use Illuminate\Http\Request;
use App\Models\CustomerStory;
use App\Models\DiscountBanner;
use App\Models\TestimoniImage;

class HomeController extends Controller
{
    /**
     * Tampilkan halaman utama
     */
    public function index()
    {
        // Ambil data Tabs
        $tabs = Tab::orderBy('order')->get();

        // Ambil data Partner Logos
        $partnerLogos = PartnerLogo::all();

        // Ambil data Customer Stories untuk Carousel
        $customerStories = CustomerStory::latest()->get();

        // Ambil artikel terbaru
        $articles = Article::latest()->take(6)->get();

        $jenis = Layanan::where('category', 'jenis')->get();
        $tambahan = Layanan::where('category', 'tambahan')->get();

        $banners = DiscountBanner::all();

        // Ambil gambar testimoni aktif
        $testimoniImages = TestimoniImage::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();

        // Kirim ke view welcome.blade.php
        return view('welcome', compact('tabs', 'partnerLogos', 'customerStories', 'articles', 'jenis', 'tambahan', 'banners', 'testimoniImages'));
    }

    /**
     * Simpan data Customer dari Form CRM
     */
    public function storeCustomer(Request $request)
    {
	   // Cek static API key pada request API
	   if ($request->ajax() && $request->header('X-API-KEY') !== 'MXuMiiKBC898/dclL1g0+Hy1wyUgvXMI3KiUdCCuG8U=') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }
		
        // Validasi input form
        $request->validate([
            'full_name' => 'required|string|max:150',
            'email'     => 'nullable|email|max:150',
            'phone'     => 'required|string|max:20',
            'notes'     => 'nullable|string',
        ]);

        // Simpan ke database
        $customer = Customer::create([
            'full_name' => $request->full_name,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'notes'     => $request->notes,
            'status'    => 'lead',
        ]);

        FollowUp::create([
            'customer_id' => $customer->id,
            'follow_up_date' => now(),
            'status' => 'pending',
            'note' => 'Follow-up otomatis setelah customer dibuat'
        ]);

        // Buat pesan untuk admin/sales
        $message = "Hai kak, aku mau tanya-tanya dulu.%0A"
            . "Perkenalkan nama saya *{$customer->full_name}*.%0A"
            . "Nomor saya: {$customer->phone}.%0A"
            . (!empty($customer->notes) ? "Catatan: {$customer->notes}%0A" : "");

        // Nomor WhatsApp admin/sales (gunakan format internasional, contoh 6281234567890)
        $adminPhone = "6285198887963";

        // Buat link WhatsApp
        $waLink = "https://wa.me/{$adminPhone}?text={$message}";
		
		if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Customer berhasil dibuat',
                'redirect' => $waLink
            ]);
        }

        // Redirect langsung ke WhatsApp admin
        return redirect()->away($waLink);
    }
}
