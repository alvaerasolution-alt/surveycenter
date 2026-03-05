@extends('layouts.app')
@section('seo_slug', 'login')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100 font-sans px-4">
    <div class="bg-white rounded-xl shadow-2xl overflow-hidden w-full max-w-4xl flex flex-col md:flex-row">
        <div class="w-full md:w-1/2 p-8 sm:p-10 flex flex-col justify-center">
            <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 text-center">Login User</h2>
            <p class="text-gray-500 text-sm text-center mt-3">Masukkan akun Anda untuk mengakses SurveyCenter</p>

            @if ($errors->any())
                <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 mt-4">
                    <ul class="list-disc ml-4 text-left">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST" class="mt-4 space-y-4">
                @csrf
                <input type="email" name="email" placeholder="Email"
                    class="block w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                    value="{{ old('email') }}" required>

                <input type="password" name="password" placeholder="Password"
                    class="block w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                    required>

                <div class="flex flex-col sm:flex-row justify-between items-center text-sm gap-2">
                    <p class="text-gray-500 hover:text-yellow-600 cursor-pointer">Lupa kata sandi?</p>
                    <p class="text-gray-500 hover:text-yellow-600 cursor-pointer">
                        <a href="{{ route('register') }}">Daftar akun baru</a>
                    </p>
                </div>

                <button type="submit"
                    class="w-full bg-orange-700 text-white py-3 rounded-full font-semibold shadow hover:bg-yellow-600 transition-all duration-300 text-sm sm:text-base">
                    SIGN IN
                </button>
            </form>
        </div>

        <div class="w-full md:w-1/2 order-first md:order-last bg-gradient-to-r from-orange-700 via-orange-600 to-orange-500 flex flex-col items-center justify-center p-8 md:p-10 text-white text-center">
            <h2 class="text-3xl font-extrabold mb-4 leading-snug">
                Selamat Datang di <span class="block">SurveyCenter.co.id</span>
            </h2>
            <p class="text-base leading-relaxed max-w-xs mb-6">
                Bergabunglah dengan kami untuk mengikuti survei, melihat progress, dan mendapatkan insight dari hasil
                analisis data dengan mudah.
            </p>
            <span class="text-yellow-200 text-sm">Akses cepat dan aman untuk semua pengguna</span>
        </div>
    </div>
</div>
@endsection
