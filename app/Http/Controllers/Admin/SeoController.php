<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SeoController extends Controller
{
    // Daftar halaman yang bisa dikelola SEO-nya
    protected array $pages = [
        'home'        => 'Beranda (Home)',
        'about'       => 'Tentang Kami (About)',
        'pricing'     => 'Harga (Pricing)',
        'blog'        => 'Blog',
        'contact'     => 'Kontak (Contact)',
        'login'       => 'Login',
        'register'    => 'Register / Daftar',
    ];

    public function index()
    {
        $pages = $this->pages;

        // Ambil semua SEO settings sekaligus
        $settings = Setting::where('key', 'LIKE', 'seo_%')->get()->keyBy('key');

        return view('admin.seo.index', compact('pages', 'settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'single_slug' => 'required|string|alpha_dash',
            'title'       => 'nullable|string|max:200',
            'desc'        => 'nullable|string|max:500',
            'keywords'    => 'nullable|string|max:300',
        ]);

        $slug = $request->single_slug;

        Setting::updateOrCreate(['key' => "seo_title_{$slug}"],    ['value' => $request->title    ?? '']);
        Setting::updateOrCreate(['key' => "seo_desc_{$slug}"],     ['value' => $request->desc     ?? '']);
        Setting::updateOrCreate(['key' => "seo_keywords_{$slug}"], ['value' => $request->keywords ?? '']);

        return redirect()->back()->with('success', "SEO untuk halaman '{$slug}' berhasil disimpan!");
    }

}
