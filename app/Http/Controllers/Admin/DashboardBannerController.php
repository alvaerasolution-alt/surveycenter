<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DashboardBanner;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class DashboardBannerController extends Controller
{
    public function index()
    {
        $banners = DashboardBanner::orderBy('order')->get();
        return view('admin.dashboard_banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.dashboard_banners.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'nullable|string|max:255',
            'subtitle'    => 'nullable|string|max:500',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:500',
            'image'       => 'required|image|mimes:jpg,jpeg,png,webp|max:5120|dimensions:min_width=1200,min_height=450',
            'background'  => 'nullable|string|max:255',
            'order'       => 'nullable|integer|min:0',
            'is_active'   => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->has('is_active');
        $data['title'] = $data['title'] ?? 'Dashboard Slide';

        if ($request->hasFile('image')) {
            $data['image'] = $this->storeBannerImage($request->file('image'));
        }

        DashboardBanner::create($data);

        return redirect()->route('admin.dashboard-banners.index')
            ->with('success', 'Banner dashboard berhasil ditambahkan!');
    }

    public function edit(DashboardBanner $dashboardBanner)
    {
        return view('admin.dashboard_banners.edit', compact('dashboardBanner'));
    }

    public function update(Request $request, DashboardBanner $dashboardBanner)
    {
        $data = $request->validate([
            'title'       => 'nullable|string|max:255',
            'subtitle'    => 'nullable|string|max:500',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:500',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120|dimensions:min_width=1200,min_height=450',
            'background'  => 'nullable|string|max:255',
            'order'       => 'nullable|integer|min:0',
            'is_active'   => 'nullable|boolean',
        ]);

        if (!$request->hasFile('image') && !$dashboardBanner->image) {
            return back()
                ->withErrors(['image' => 'Gambar banner wajib diisi.'])
                ->withInput();
        }

        $data['is_active'] = $request->has('is_active');
        $data['title'] = $data['title'] ?? ($dashboardBanner->title ?: 'Dashboard Slide');

        if ($request->hasFile('image')) {
            if ($dashboardBanner->image) {
                Storage::disk('public')->delete($dashboardBanner->image);
            }
            $data['image'] = $this->storeBannerImage($request->file('image'));
        }

        $dashboardBanner->update($data);

        return redirect()->route('admin.dashboard-banners.index')
            ->with('success', 'Banner dashboard berhasil diperbarui!');
    }

    public function destroy(DashboardBanner $dashboardBanner)
    {
        if ($dashboardBanner->image) {
            Storage::disk('public')->delete($dashboardBanner->image);
        }
        $dashboardBanner->delete();

        return redirect()->route('admin.dashboard-banners.index')
            ->with('success', 'Banner dashboard berhasil dihapus!');
    }

    public function toggle(DashboardBanner $dashboardBanner)
    {
        $dashboardBanner->update(['is_active' => !$dashboardBanner->is_active]);

        return back()->with('success', 'Status banner berhasil diperbarui!');
    }

    private function storeBannerImage(UploadedFile $file): string
    {
        $rawContent = file_get_contents($file->getRealPath());
        $sourceImage = $rawContent ? imagecreatefromstring($rawContent) : false;

        if (! $sourceImage) {
            throw ValidationException::withMessages([
                'image' => 'File gambar tidak valid.',
            ]);
        }

        $sourceWidth = imagesx($sourceImage);
        $sourceHeight = imagesy($sourceImage);
        $targetRatio = 8 / 3;
        $sourceRatio = $sourceWidth / $sourceHeight;

        if ($sourceRatio > $targetRatio) {
            $cropHeight = $sourceHeight;
            $cropWidth = (int) round($cropHeight * $targetRatio);
            $cropX = (int) floor(($sourceWidth - $cropWidth) / 2);
            $cropY = 0;
        } else {
            $cropWidth = $sourceWidth;
            $cropHeight = (int) round($cropWidth / $targetRatio);
            $cropX = 0;
            $cropY = (int) floor(($sourceHeight - $cropHeight) / 2);
        }

        $targetWidth = 1600;
        $targetHeight = 600;
        $targetImage = imagecreatetruecolor($targetWidth, $targetHeight);

        imagecopyresampled(
            $targetImage,
            $sourceImage,
            0,
            0,
            $cropX,
            $cropY,
            $targetWidth,
            $targetHeight,
            $cropWidth,
            $cropHeight
        );

        ob_start();
        imagejpeg($targetImage, null, 86);
        $processedImage = ob_get_clean();

        imagedestroy($sourceImage);
        imagedestroy($targetImage);

        if ($processedImage === false) {
            throw ValidationException::withMessages([
                'image' => 'Gagal memproses gambar.',
            ]);
        }

        $path = 'dashboard-banners/' . Str::uuid() . '.jpg';
        Storage::disk('public')->put($path, $processedImage);

        return $path;
    }
}
