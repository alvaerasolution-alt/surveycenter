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
            // Pricing
            'pricing_tiers',
            'pricing_min_order',
            // Poin
            'point_ratio',
            'cashback_percentage',
            // Affiliate
            'affiliate_commission_percent',
        ])->pluck('value', 'key');

        // Decode tiers JSON for the form
        $pricingTiers = json_decode($settings['pricing_tiers'] ?? \App\Helpers\VolumePricing::DEFAULT_TIERS_JSON, true);

        return view('admin.settings.edit', compact('settings', 'pricingTiers'));
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
            // Pricing
            'tier_max'            => 'nullable|array',
            'tier_max.*'          => 'nullable|integer|min:1',
            'tier_price'          => 'nullable|array',
            'tier_price.*'        => 'required|integer|min:1',
            'pricing_min_order'   => 'nullable|integer|min:0',
            // Poin
            'point_ratio'         => 'nullable|integer|min:1',
            'cashback_percentage' => 'nullable|numeric|min:0|max:100',
            // Affiliate
            'affiliate_commission_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        $keys = [
            'video_url', 'footer_alamat', 'footer_whatsapp', 'footer_email',
            'sosmed_facebook', 'sosmed_twitter', 'sosmed_linkedin', 'sosmed_instagram', 'sosmed_tiktok',
            'popup_wa_title', 'popup_wa_subtitle', 'popup_admin_number',
        ];

        foreach ($keys as $key) {
            if ($request->has($key)) {
                Setting::set($key, $request->$key);
            }
        }

        // Simpan checkbox (tidak dikirim saat unchecked)
        Setting::set('popup_wa_enabled', $request->has('popup_wa_enabled') ? '1' : '0');

        // Build pricing tiers JSON
        if ($request->has('tier_price')) {
            $tiers = [];
            $maxValues = $request->input('tier_max', []);
            $priceValues = $request->input('tier_price', []);

            foreach ($priceValues as $i => $price) {
                $max = isset($maxValues[$i]) && $maxValues[$i] !== '' ? (int) $maxValues[$i] : null;
                $tiers[] = ['max' => $max, 'price' => (int) $price];
            }

            Setting::set('pricing_tiers', json_encode($tiers));
        }

        if ($request->has('pricing_min_order')) {
            Setting::set('pricing_min_order', (string) $request->pricing_min_order);
        }

        if ($request->has('point_ratio')) {
            Setting::set('point_ratio', (string) $request->point_ratio);
        }

        if ($request->has('cashback_percentage')) {
            Setting::set('cashback_percentage', (string) $request->cashback_percentage);
        }

        if ($request->has('affiliate_commission_percent')) {
            Setting::set('affiliate_commission_percent', (string) $request->affiliate_commission_percent);
        }

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
