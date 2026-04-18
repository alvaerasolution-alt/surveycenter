@extends('layouts.admin')

@section('title', 'Tambah Slide Dashboard')
@section('page-title', 'Tambah Slide')

@section('content')
<div class="max-w-2xl space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.dashboard-banners.index') }}"
           class="p-2 rounded-lg hover:bg-gray-100 text-gray-500 transition">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h2 class="text-xl font-semibold text-gray-900">Tambah Slide Dashboard</h2>
            <p class="text-sm text-gray-500 mt-0.5">Buat slide baru untuk slider di dashboard user</p>
        </div>
    </div>

    <form action="{{ route('admin.dashboard-banners.store') }}"
          method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
            <input type="hidden" name="title" value="{{ old('title', 'Dashboard Slide') }}">

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Urutan</label>
                <input type="number" name="order" min="0" value="{{ old('order', 0) }}"
                       class="border border-gray-300 p-2.5 w-full rounded-lg text-sm focus:ring-2 focus:ring-orange-400 focus:border-transparent outline-none transition @error('order') border-red-400 @enderror">
                @error('order')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Status Aktif --}}
            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_active" id="is_active" value="1"
                       {{ old('is_active', '1') ? 'checked' : '' }}
                       class="w-4 h-4 rounded border-gray-300 text-orange-500 focus:ring-orange-400 cursor-pointer">
                <label for="is_active" class="text-sm font-medium text-gray-700 cursor-pointer">
                    Tampilkan di dashboard (aktif)
                </label>
            </div>
        </div>

        <input type="hidden" name="background" value="{{ old('background') }}">

        {{-- Gambar --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                Gambar Slide <span class="text-red-500">*</span>
            </label>
            <input type="file" name="image" accept="image/*"
                   class="border border-gray-300 p-2.5 w-full rounded-lg text-sm @error('image') border-red-400 @enderror" required>
            <p class="text-xs text-gray-400 mt-1.5">Format: JPG, PNG, WebP. Maks 5MB. Minimal 1200x450. Rasio akan otomatis di-crop ke 8:3 (1600x600).</p>
            @error('image')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Buttons --}}
        <div class="flex items-center gap-3">
            <button type="submit"
                    class="bg-orange-500 hover:bg-orange-600 text-white font-bold px-6 py-2.5 rounded-lg transition shadow-sm">
                Simpan Slide
            </button>
            <a href="{{ route('admin.dashboard-banners.index') }}"
               class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
