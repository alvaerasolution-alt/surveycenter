@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto bg-white shadow rounded-xl p-6 mt-8">
    <h1 class="text-2xl font-bold mb-6">Edit Layanan</h1>

    <form action="{{ route('admin.layanan.update', $layanan) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="block mb-1 font-semibold">Judul</label>
            <input type="text" name="title" class="w-full border rounded-lg p-2" value="{{ $layanan->title }}" required>
        </div>
        <div>
            <label class="block mb-1 font-semibold">Deskripsi</label>
            <textarea name="description" rows="6" class="w-full border rounded-lg p-2" required>{{ $layanan->description }}</textarea>
        </div>
        <div>
            <label class="block mb-1 font-semibold">Gambar</label>
            @if($layanan->image)
                <img src="{{ asset('storage/'.$layanan->image) }}" class="h-20 mb-2 rounded">
            @endif
            <input type="file" name="image" class="w-full border rounded-lg p-2">
        </div>
        <div class="flex justify-end">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Update
            </button>
        </div>
    </form>
</div>
@endsection
