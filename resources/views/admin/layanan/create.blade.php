@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto bg-white shadow-lg rounded-xl p-8 mt-10">
    <h1 class="text-2xl font-extrabold text-gray-800 mb-6">➕ Tambah Layanan Baru</h1>

    <!-- Pesan Error -->
    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
            <ul class="list-disc pl-5 space-y-1 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.layanan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Judul -->
        <div>
            <label class="block mb-2 font-semibold text-gray-700">Judul Layanan <span class="text-red-500">*</span></label>
            <input type="text" name="title" value="{{ old('title') }}"
                class="w-full border border-gray-300 rounded-lg p-3 focus:ring focus:ring-orange-300 focus:border-orange-500 text-sm"
                placeholder="Contoh: Survei Brand Awareness" required>
        </div>

        <!-- Deskripsi -->
        <div>
            <label class="block mb-2 font-semibold text-gray-700">Deskripsi <span class="text-red-500">*</span></label>
            <textarea name="description" rows="6"
                class="w-full border border-gray-300 rounded-lg p-3 focus:ring focus:ring-orange-300 focus:border-orange-500 text-sm"
                placeholder="Tuliskan deskripsi lengkap layanan..." required>{{ old('description') }}</textarea>
        </div>

        <!-- Kategori -->
        <div>
            <label class="block mb-2 font-semibold text-gray-700">Kategori <span class="text-red-500">*</span></label>
            <select name="category" class="w-full border border-gray-300 rounded-lg p-3 focus:ring focus:ring-orange-300 focus:border-orange-500 text-sm" required>
                <option value="">-- Pilih Kategori --</option>
                <option value="jenis" {{ old('category') == 'jenis' ? 'selected' : '' }}>Jenis Survei</option>
                <option value="tambahan" {{ old('category') == 'tambahan' ? 'selected' : '' }}>Layanan Tambahan</option>
            </select>
        </div>

        <!-- Upload Gambar -->
        <div>
            <label class="block mb-2 font-semibold text-gray-700">Gambar</label>
            <input type="file" name="image"
                class="w-full border border-gray-300 rounded-lg p-3 focus:ring focus:ring-orange-300 focus:border-orange-500 text-sm">
            <p class="text-xs text-gray-500 mt-1">Format yang didukung: JPG, PNG, WEBP. Max 2MB</p>
        </div>

        <!-- Tombol Simpan -->
        <div class="flex justify-end">
            <button type="submit"
                class="px-6 py-2 bg-orange-500 text-white font-semibold rounded-lg shadow hover:bg-orange-600 transition">
                💾 Simpan Layanan
            </button>
        </div>
    </form>
</div>
@endsection
