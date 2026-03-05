@extends('layouts.admin')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Tambah Tab</h1>

    <form action="{{ route('tabs.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Judul Tab --}}
        <label class="block font-semibold mb-1">Judul</label>
        <input type="text" name="title" class="border w-full p-2 mb-3 rounded" required>

        {{-- Deskripsi Tab --}}
        <label class="block font-semibold mb-1">Deskripsi</label>
        <textarea name="description" class="border w-full p-2 mb-3 rounded" rows="4" required></textarea>

        {{-- Teks Tombol --}}
        <label class="block font-semibold mb-1">Teks Tombol</label>
        <input type="text" name="button_text" class="border w-full p-2 mb-3 rounded" placeholder="Contoh: Hubungi Kami">

        {{-- Link Tombol --}}
        <label class="block font-semibold mb-1">Link Tombol</label>
        <input type="url" name="button_link" class="border w-full p-2 mb-3 rounded" placeholder="https://wa.me/6281234567890 atau https://example.com">

        {{-- Gambar --}}
        <label class="block font-semibold mb-1">Gambar</label>
        <input type="file" name="image" class="border w-full p-2 mb-3 rounded" accept="image/*">

        {{-- Urutan --}}
        <label class="block font-semibold mb-1">Urutan</label>
        <input type="number" name="order" value="0" class="border w-full p-2 mb-3 rounded">

        <button class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded shadow-md">
            Simpan
        </button>
    </form>
</div>
@endsection
