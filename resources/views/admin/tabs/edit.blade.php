@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Edit Tab</h1>

    <!-- Pesan Error -->
    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Edit -->
    <form action="{{ route('tabs.update', $tab) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PUT')

        <!-- Judul -->
        <div>
            <label for="title" class="block font-semibold mb-2">Judul</label>
            <input type="text" name="title" id="title" 
                   value="{{ old('title', $tab->title) }}" 
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200">
        </div>

        <!-- Deskripsi -->
        <div>
            <label for="description" class="block font-semibold mb-2">Deskripsi</label>
            <textarea name="description" id="description" rows="4"
                      class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200">{{ old('description', $tab->description) }}</textarea>
        </div>

        <!-- Teks Tombol -->
        <div>
            <label for="button_text" class="block font-semibold mb-2">Teks Tombol</label>
            <input type="text" name="button_text" id="button_text" 
                   value="{{ old('button_text', $tab->button_text) }}" 
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200">
        </div>

        <!-- Link Tombol -->
        <div>
            <label for="button_link" class="block font-semibold mb-2">Link Tombol</label>
            <input type="text" name="button_link" id="button_link" 
                   value="{{ old('button_link', $tab->button_link) }}" 
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200">
        </div>

        <!-- Urutan -->
        <div>
            <label for="order" class="block font-semibold mb-2">Urutan</label>
            <input type="number" name="order" id="order" 
                   value="{{ old('order', $tab->order) }}" 
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200">
        </div>

        <!-- Gambar -->
        <div>
            <label for="image" class="block font-semibold mb-2">Gambar</label>
            <div class="flex items-center space-x-4">
                @if($tab->image)
                    <img src="{{ asset('storage/' . $tab->image) }}" 
                         alt="{{ $tab->title }}" 
                         class="w-20 h-20 object-cover rounded shadow">
                @else
                    <span class="text-gray-500 italic">Tidak ada gambar</span>
                @endif
                <input type="file" name="image" id="image" 
                       class="border border-gray-300 rounded px-3 py-2">
            </div>
            <p class="text-gray-500 text-sm mt-1">Biarkan kosong jika tidak ingin mengubah gambar.</p>
        </div>

        <!-- Tombol Simpan -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('tabs.index') }}" 
               class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition">
                Batal
            </a>
            <button type="submit" 
                    class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
