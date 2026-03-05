@extends('layouts.admin')

@section('content')
<div class="p-6">
    <h1 class="text-xl font-bold mb-4">
        {{ isset($discountBanner) ? 'Edit Banner' : 'Tambah Banner' }}
    </h1>

    <form action="{{ isset($discountBanner) ? route('admin.discount-banners.update', $discountBanner) : route('admin.discount-banners.store') }}" 
          method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @if(isset($discountBanner)) @method('PUT') @endif

        <div>
            <label class="block font-semibold">Judul</label>
            <input type="text" name="title" value="{{ old('title', $discountBanner->title ?? '') }}" class="border p-2 w-full rounded">
        </div>

        <div>
            <label class="block font-semibold">Sub Judul</label>
            <input type="text" name="subtitle" value="{{ old('subtitle', $discountBanner->subtitle ?? '') }}" class="border p-2 w-full rounded">
        </div>

        <div>
            <label class="block font-semibold">Teks Tombol</label>
            <input type="text" name="button_text" value="{{ old('button_text', $discountBanner->button_text ?? '') }}" class="border p-2 w-full rounded">
        </div>

        <div>
            <label class="block font-semibold">Link Tombol</label>
            <input type="text" name="button_link" value="{{ old('button_link', $discountBanner->button_link ?? '') }}" class="border p-2 w-full rounded">
        </div>

        <div>
            <label class="block font-semibold">Background (CSS Gradient)</label>
            <input type="text" name="background" value="{{ old('background', $discountBanner->background ?? 'linear-gradient(90deg,#FDE68A 0%, #FB923C 100%)') }}" class="border p-2 w-full rounded">
        </div>

        <div>
            <label class="block font-semibold">Urutan</label>
            <input type="number" name="order" value="{{ old('order', $discountBanner->order ?? 0) }}" class="border p-2 w-full rounded">
        </div>

        <div>
            <label class="block font-semibold">Gambar</label>
            <input type="file" name="image" class="border p-2 w-full rounded">
            @if(isset($discountBanner) && $discountBanner->image)
                <img src="{{ asset('storage/'.$discountBanner->image) }}" class="w-24 mt-2">
            @endif
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
            Simpan
        </button>
    </form>
</div>
@endsection
