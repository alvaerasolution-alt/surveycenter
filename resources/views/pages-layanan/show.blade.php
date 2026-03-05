@extends('layouts.app')

@section('content')
<div class="bg-gray-50 py-12">
    <div class="max-w-5xl mx-auto bg-white shadow-lg rounded-xl px-8 py-10">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-8 text-center border-b pb-4">
            {{ $layanan->title }}
        </h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
            <!-- Deskripsi -->
            <div class="space-y-4 text-gray-700 leading-relaxed text-justify">
                {!! nl2br(e($layanan->description)) !!}
            </div>

            <!-- Gambar & Tombol -->
            <div class="flex flex-col items-center w-full">
                @if($layanan->image)
                    <img src="{{ asset('storage/'.$layanan->image) }}" alt="{{ $layanan->title }}" 
                         class="rounded-lg shadow-md w-full">
                @endif

                <!-- Tombol Pesan -->
                <a href="{{ route('surveys.create') }}"
                   class="mt-6 flex items-center justify-center gap-2 px-6 py-3 bg-yellow-500 text-white font-semibold rounded-md shadow hover:bg-yellow-600 transition w-full">
                    <!-- Icon Keranjang -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9m12-9l2 9m-6-9v9" />
                    </svg>
                    Pesan Sekarang
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
