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
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Tampilkan halaman utama
     */
    public function index()
    {
        // Caching queries selama 30 menit
        $tabs = Cache::remember('home_tabs', now()->addMinutes(30), fn() => Tab::orderBy('order')->get());
        
        $partnerLogos = Cache::remember('home_partner_logos', now()->addMinutes(30), fn() => PartnerLogo::all());
        
        $customerStories = Cache::remember('home_customer_stories', now()->addMinutes(30), fn() => CustomerStory::latest()->get());
        
        $articles = Cache::remember('home_articles', now()->addMinutes(30), fn() => Article::published()->latest()->take(6)->get());
        
        $jenis = Cache::remember('home_layanan_jenis', now()->addMinutes(30), fn() => Layanan::where('category', 'jenis')->get());
        
        $tambahan = Cache::remember('home_layanan_tambahan', now()->addMinutes(30), fn() => Layanan::where('category', 'tambahan')->get());
        
        $banners = Cache::remember('home_banners', now()->addMinutes(30), fn() => DiscountBanner::all());
        
        $testimoniImages = Cache::remember('home_testimoni_images', now()->addMinutes(30), fn() => 
            TestimoniImage::where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('created_at', 'desc')
                ->get()
        );

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
            'company'   => 'nullable|string|max:150',
            'notes'     => 'nullable|string',
            'source'    => 'nullable|string|max:50',
        ]);

        // Gabungkan notes + company
        $notes = $request->notes ?? '';
        if (!empty($request->company)) {
            $notes = 'Perusahaan: ' . $request->company . ($notes ? "\n" . $notes : '');
        }

        // Simpan ke database
        $customer = Customer::create([
            'full_name' => $request->full_name,
            'email'     => $request->email ?? '',
            'phone'     => $request->phone,
            'notes'     => $notes,
            'source'    => $request->source ?? 'website',
            'status'    => 'lead',
        ]);

        FollowUp::create([
            'customer_id'    => $customer->id,
            'follow_up_date' => now(),
            'status'         => 'pending',
            'note'           => 'Follow-up otomatis dari ' . ($request->source ?? 'website'),
        ]);

        // Nomor WhatsApp admin — ambil dari settings, fallback hardcode
        $adminPhone = preg_replace('/[^0-9]/', '',
            \App\Models\Setting::where('key', 'popup_admin_number')->value('value')
            ?: \App\Models\Setting::where('key', 'footer_whatsapp')->value('value')
            ?: '6285198887963'
        );

        // Buat pesan WA
        $msg = "Hai kak, aku mau tanya-tanya dulu.%0A"
            . "Perkenalkan nama saya *{$customer->full_name}*.%0A"
            . "Nomor saya: {$customer->phone}."
            . (!empty($request->company) ? "%0APerusahaan: {$request->company}" : '');

        $waLink = "https://wa.me/{$adminPhone}?text={$msg}";

        if ($request->ajax()) {
            return response()->json([
                'status'   => 'success',
                'message'  => 'Data berhasil disimpan',
                'redirect' => $waLink,
            ]);
        }

        return redirect()->away($waLink);
    }
}
