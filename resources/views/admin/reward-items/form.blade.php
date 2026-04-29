@extends('layouts.admin')

@section('title', $item ? 'Edit Reward Item' : 'Tambah Reward Item')
@section('page-title', $item ? 'Edit Reward Item' : 'Tambah Reward Item')

@section('content')
<div class="max-w-2xl">

    <div class="mb-4">
        <a href="{{ route('admin.reward-items.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke daftar
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">{{ $item ? 'Edit' : 'Tambah' }} Reward Item</h2>
            <p class="text-sm text-gray-500 mt-1">{{ $item ? 'Perbarui informasi reward item' : 'Buat item reward baru untuk ditukar user dengan poin' }}</p>
        </div>

        <form method="POST" action="{{ $item ? route('admin.reward-items.update', $item) : route('admin.reward-items.store') }}" class="p-6 space-y-5">
            @csrf
            @if($item) @method('PUT') @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Item <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $item->name ?? '') }}" required
                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('name') border-red-300 @enderror"
                    placeholder="Contoh: Pulsa 25.000">
                @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi</label>
                <textarea name="description" rows="2"
                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                    placeholder="Deskripsi singkat (opsional)">{{ old('description', $item->description ?? '') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Kategori <span class="text-red-500">*</span></label>
                    <select name="category" required
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        <option value="tunai" {{ old('category', $item->category ?? '') === 'tunai' ? 'selected' : '' }}>Uang Tunai</option>
                        <option value="lainnya" {{ old('category', $item->category ?? '') === 'lainnya' ? 'selected' : '' }}>Lain-lain</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Harga Poin <span class="text-red-500">*</span></label>
                    <input type="number" name="points_cost" value="{{ old('points_cost', $item->points_cost ?? '') }}" required min="1"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('points_cost') border-red-300 @enderror"
                        placeholder="Contoh: 50">
                    @error('points_cost')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nilai / Label</label>
                    <input type="text" name="value" value="{{ old('value', $item->value ?? '') }}"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                        placeholder="Contoh: Rp 25.000">
                    <p class="text-xs text-gray-400 mt-1">Label yang ditampilkan ke user (opsional)</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Stok <span class="text-red-500">*</span></label>
                    <input type="number" name="stock" value="{{ old('stock', $item->stock ?? -1) }}" required min="-1"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('stock') border-red-300 @enderror"
                        placeholder="-1 = unlimited">
                    @error('stock')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    <p class="text-xs text-gray-400 mt-1">Set <strong>-1</strong> untuk stok tak terbatas</p>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t">
                <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition shadow-sm">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    {{ $item ? 'Simpan Perubahan' : 'Tambah Item' }}
                </button>
                <a href="{{ route('admin.reward-items.index') }}"
                    class="px-5 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-800 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
