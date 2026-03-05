@extends('layouts.admin')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-bold mb-6">Tambah Customer Story</h2>

    {{-- Notifikasi error --}}
    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Tambah Story --}}
    <form action="{{ route('customer-stories.store') }}" method="POST" enctype="multipart/form-data"
        class="space-y-5 bg-white p-6 rounded shadow-md">
        @csrf

        <div>
            <label class="block font-semibold mb-1">Judul</label>
            <input type="text" name="title" value="{{ old('title') }}"
                class="w-full border rounded p-2" required>
        </div>

        <div>
            <label class="block font-semibold mb-1">Highlight</label>
            <input type="text" name="highlight" value="{{ old('highlight') }}"
                class="w-full border rounded p-2" required>
        </div>

        <div>
            <label class="block font-semibold mb-1">Warna Highlight</label>
            <input type="color" name="highlight_color" value="{{ old('highlight_color', '#FFD700') }}"
                class="w-16 h-10 border rounded">
        </div>

        <div>
            <label class="block font-semibold mb-1">Subheading</label>
            <input type="text" name="subheading" value="{{ old('subheading') }}"
                class="w-full border rounded p-2" required>
        </div>

        <div>
            <label class="block font-semibold mb-1">Deskripsi</label>
            <textarea name="description" rows="5"
                class="w-full border rounded p-2" required>{{ old('description') }}</textarea>
        </div>

        <div>
            <label class="block font-semibold mb-1">Gambar</label>
            <input type="file" name="image" accept="image/*"
                class="w-full border rounded p-2" required>
        </div>

        <div>
            <label class="block font-semibold mb-1">Button Text (Opsional)</label>
            <input type="text" name="button_text" value="{{ old('button_text') }}"
                class="w-full border rounded p-2">
        </div>

        <div>
            <label class="block font-semibold mb-1">Button Link (Opsional)</label>
            <input type="url" name="button_link" value="{{ old('button_link') }}"
                class="w-full border rounded p-2">
        </div>

        <div class="flex items-center gap-4">
            <button type="submit"
                class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 transition">
                Simpan Story
            </button>
            <a href="{{ route('customer-stories.index') }}"
                class="text-gray-600 hover:text-gray-800">Batal</a>
        </div>
    </form>
</div>
@endsection
