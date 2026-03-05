<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PartnerLogo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartnerLogoController extends Controller
{
    public function index()
    {
        $logos = PartnerLogo::all();
        return view('admin.partner_logos.index', compact('logos'));
    }

    public function create()
    {
        return view('admin.partner_logos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'required|image|mimes:png,jpg,jpeg,svg|max:2048',
        ]);

        $path = $request->file('logo')->store('partner_logos', 'public');

        PartnerLogo::create([
            'name' => $request->name,
            'logo_path' => $path,
        ]);

        return redirect()->route('partner-logos.index')->with('success', 'Logo berhasil ditambahkan!');
    }

    public function edit(PartnerLogo $partnerLogo)
    {
        return view('admin.partner_logos.edit', compact('partnerLogo'));
    }

    public function update(Request $request, PartnerLogo $partnerLogo)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
        ]);

        $path = $partnerLogo->logo_path;
        if ($request->hasFile('logo')) {
            Storage::disk('public')->delete($path);
            $path = $request->file('logo')->store('partner_logos', 'public');
        }

        $partnerLogo->update([
            'name' => $request->name,
            'logo_path' => $path,
        ]);

        return redirect()->route('partner-logos.index')->with('success', 'Logo berhasil diperbarui!');
    }

    public function destroy(PartnerLogo $partnerLogo)
    {
        Storage::disk('public')->delete($partnerLogo->logo_path);
        $partnerLogo->delete();

        return redirect()->route('partner-logos.index')->with('success', 'Logo berhasil dihapus!');
    }
}
