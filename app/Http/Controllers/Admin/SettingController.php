<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function edit()
    {
        $videoUrl = Setting::where('key', 'video_url')->first();
        return view('admin.settings.edit', compact('videoUrl'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'video_url' => 'required|string'
        ]);

        Setting::updateOrCreate(
            ['key' => 'video_url'],
            ['value' => $request->video_url]
        );

        return redirect()->back()->with('success', 'Video berhasil diperbarui!');
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
