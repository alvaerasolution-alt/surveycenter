@extends('layouts.app')
@section('seo_slug', 'register')

@section('content')
    <div class="flex items-center justify-center min-h-screen bg-gray-100 px-4">
        <div class="bg-white rounded-xl shadow-2xl overflow-hidden w-full max-w-4xl flex flex-col lg:flex-row">
            <!-- Left Side: Registration Form -->
            <div class="w-full lg:w-1/2 p-6 sm:p-8 lg:p-10 flex flex-col justify-center">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 text-center">Daftar Akun</h2>
                <p class="text-gray-500 text-xs sm:text-sm text-center mt-2">Buat akun untuk mengakses SurveyCenter</p>

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="bg-red-100 text-red-700 px-4 py-3 rounded mb-4 mt-4">
                        <ul class="list-disc ml-4">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register.post') }}" method="POST" class="mt-4 space-y-4">
                    @csrf
                    <input type="text" name="name" placeholder="Nama Lengkap" value="{{ old('name') }}" required
                        class="block w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-yellow-500 focus:border-yellow-500">
                    <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required
                        class="block w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-yellow-500 focus:border-yellow-500">
                    <input type="password" name="password" placeholder="Password" required
                        class="block w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-yellow-500 focus:border-yellow-500">
                    <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" required
                        class="block w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-yellow-500 focus:border-yellow-500">

                    <button type="submit"
                        class="w-full bg-orange-700 text-white py-3 rounded-full font-semibold shadow hover:bg-yellow-600 transition">
                        Daftar
                    </button>

                    <p class="text-center text-sm text-gray-600 mt-4">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="text-orange-700 hover:underline">Login di sini</a>
                    </p>
                </form>
            </div>

            <!-- Right Side: Informasi Tambahan -->
            <div
                class="w-full lg:w-1/2 bg-gradient-to-r from-orange-700 via-orange-600 to-orange-500 flex flex-col items-center justify-center p-6 sm:p-8 lg:p-10 text-white text-center">
                <h2 class="text-2xl sm:text-3xl font-extrabold mb-4 leading-snug">
                    Selamat Datang di <span class="block">SurveyCenter.co.id</span>
                </h2>
                <p class="text-sm sm:text-base leading-relaxed max-w-xs mb-6">
                    Bergabunglah dengan kami untuk mengelola survei, menganalisis data,
                    dan meningkatkan keputusan bisnis Anda secara mudah dan efisien.
                </p>
            </div>
        </div>
    </div>
@endsection
