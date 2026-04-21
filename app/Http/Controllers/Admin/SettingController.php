<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function edit()
    {
        $settings = Setting::whereIn('key', [
            'video_url',
            'footer_alamat',
            'footer_whatsapp',
            'footer_email',
            'sosmed_facebook',
            'sosmed_twitter',
            'sosmed_linkedin',
            'sosmed_instagram',
            'sosmed_tiktok',
            'popup_wa_enabled',
            'popup_wa_title',
            'popup_wa_subtitle',
            'popup_admin_number',
        ])->pluck('value', 'key');

        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'video_url'           => 'nullable|string',
            'footer_alamat'       => 'nullable|string',
            'footer_whatsapp'     => 'nullable|string',
            'footer_email'        => 'nullable|email',
            'sosmed_facebook'     => 'nullable|string',
            'sosmed_twitter'      => 'nullable|string',
            'sosmed_linkedin'     => 'nullable|string',
            'sosmed_instagram'    => 'nullable|string',
            'sosmed_tiktok'       => 'nullable|string',
            'popup_wa_enabled'    => 'nullable|in:0,1',
            'popup_wa_title'      => 'nullable|string|max:100',
            'popup_wa_subtitle'   => 'nullable|string|max:150',
            'popup_admin_number'  => 'nullable|string|max:20',
        ]);

        $keys = [
            'video_url', 'footer_alamat', 'footer_whatsapp', 'footer_email',
            'sosmed_facebook', 'sosmed_twitter', 'sosmed_linkedin', 'sosmed_instagram', 'sosmed_tiktok',
            'popup_wa_title', 'popup_wa_subtitle', 'popup_admin_number',
        ];

        foreach ($keys as $key) {
            if ($request->has($key)) {
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $request->$key]
                );
            }
        }

        // Simpan checkbox (tidak dikirim saat unchecked)
        Setting::updateOrCreate(
            ['key' => 'popup_wa_enabled'],
            ['value' => $request->has('popup_wa_enabled') ? '1' : '0']
        );

        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui!');
    }

    // ─── Syarat & Ketentuan ───────────────────────────────────
    public function terms()
    {
        $terms = Setting::where('key', 'terms_content')->value('value') ?? '';
        return view('admin.settings.terms', compact('terms'));
    }

    public function updateTerms(Request $request)
    {
        $request->validate([
            'terms_content' => 'nullable|string',
        ]);

        Setting::updateOrCreate(
            ['key' => 'terms_content'],
            ['value' => $request->terms_content ?? '']
        );

        return redirect()->back()->with('success', 'Syarat & Ketentuan berhasil diperbarui!');
    }
}
