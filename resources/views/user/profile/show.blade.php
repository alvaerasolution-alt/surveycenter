@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 flex justify-center py-10 px-4">
        <div class="w-full max-w-5xl flex bg-white rounded-3xl shadow-lg overflow-hidden">

            <!-- Sidebar -->
            <div class="w-64 bg-gray-100 p-6 flex-shrink-0">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Menu</h2>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('profile.show') }}"
                            class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-yellow-200 transition
                       {{ request()->routeIs('profile.edit') ? 'bg-yellow-300 font-semibold' : '' }}">
                            Edit Profile
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('orders.index') }}"
                            class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-yellow-200 transition
                       {{ request()->routeIs('orders.*') ? 'bg-yellow-300 font-semibold' : '' }}">
                            My Orders
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Konten Profil -->
            <div class="flex-1 p-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-6">Profil Saya</h1>

                <!-- Alert sukses -->
                @if (session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-gray-700 font-medium mb-2">Nama</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-yellow-500 focus:border-yellow-500 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-yellow-500 focus:border-yellow-500 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-gray-700 font-medium mb-2">Password Baru (Opsional)</label>
                        <input type="password" name="password" id="password"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-yellow-500 focus:border-yellow-500 @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Confirm -->
                    <div>
                        <label for="password_confirmation" class="block text-gray-700 font-medium mb-2">Konfirmasi
                            Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-yellow-500 focus:border-yellow-500">
                    </div>

                    <!-- Submit -->
                    <div>
                        <button type="submit"
                            class="w-full bg-yellow-500 text-white font-semibold rounded-lg shadow hover:bg-yellow-600 transition px-4 py-3">
                            Update Profil
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
