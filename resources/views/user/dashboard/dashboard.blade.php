@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 flex flex-col items-center py-10 px-4">

        <!-- Hero Section -->
        <div
            class="bg-gradient-to-r from-yellow-400 via-yellow-300 to-yellow-200 w-full max-w-5xl rounded-3xl shadow-xl p-10 mb-10 text-center transform transition-all hover:scale-105 duration-300">
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4">
                Selamat Datang, {{ auth()->user()->name }} 🎉
            </h1>
            <p class="text-lg md:text-xl text-gray-800 mb-6">
                Buat survei dengan mudah hanya <strong>Rp 1.000</strong> per pertanyaan dan <strong>Rp 1.000</strong> per
                responden.
            </p>
            <a href="{{ route('pricing') }}"
                class="mt-4 inline-block bg-black text-white px-8 py-4 rounded-xl shadow-lg hover:bg-gray-800 transition font-semibold">
                + Buat Survey Baru
            </a>
        </div>
    </div>
@endsection
