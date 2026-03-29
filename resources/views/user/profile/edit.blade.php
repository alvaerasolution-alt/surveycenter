@extends('layouts.user')

@section('title', 'Edit Profil')
@section('page-title', 'Edit Profil Saya')
@section('page-description', 'Perbarui informasi pribadi dan password akun Anda')

@section('content')
<div class="max-w-2xl mx-auto">

    {{-- Tabs --}}
    <div class="bg-white rounded-xl border border-gray-200/80 mb-6">
        <div class="flex border-b border-gray-100">
            <a href="{{ route('user.profile.edit') }}" 
                class="flex-1 px-6 py-4 text-sm font-medium text-center border-b-2 {{ request()->routeIs('user.profile.edit') ? 'border-orange-600 text-orange-600' : 'border-transparent text-gray-600 hover:text-gray-900' }} transition">
                <i data-lucide="user" class="w-4 h-4 inline mr-2"></i>
                Informasi Profil
            </a>
            <a href="{{ route('user.profile.show') }}" 
                class="flex-1 px-6 py-4 text-sm font-medium text-center border-b-2 {{ request()->routeIs('user.profile.show') ? 'border-orange-600 text-orange-600' : 'border-transparent text-gray-600 hover:text-gray-900' }} transition">
                <i data-lucide="eye" class="w-4 h-4 inline mr-2"></i>
                Lihat Profil
            </a>
        </div>
    </div>

    {{-- Success Message --}}
    @if (session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0"></i>
            <div>
                <p class="font-medium">Sukses!</p>
                <p class="text-sm">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    {{-- Edit Form --}}
    <div class="bg-white rounded-xl border border-gray-200/80 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">Informasi Pribadi</h2>
            <p class="text-sm text-gray-500 mt-1">Update nama dan email akun Anda</p>
        </div>

        <form action="{{ route('user.profile.update') }}" method="POST" class="p-6 space-y-6">
            @csrf

            {{-- Name --}}
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition @error('name') border-red-500 @enderror"
                    placeholder="Masukkan nama lengkap Anda">
                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition @error('email') border-red-500 @enderror"
                    placeholder="Masukkan email Anda">
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- WhatsApp (Phone) --}}
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor WhatsApp <span class="text-red-500">*</span></label>
                <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" required pattern="^08[0-9]{8,13}$"
                    class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition @error('phone') border-red-500 @enderror"
                    placeholder="08xxxxxxxxxx">
                <p class="mt-1 text-xs text-gray-400">Gunakan format 08... (contoh: 081234567890)</p>
                @error('phone')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Divider --}}
            <div class="border-t border-gray-100 pt-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Ubah Password</h3>
                <p class="text-xs text-gray-500 mb-4">Biarkan kosong jika tidak ingin mengubah password</p>
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                <input type="password" name="password" id="password"
                    class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition @error('password') border-red-500 @enderror"
                    placeholder="Minimal 6 karakter">
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password Confirmation --}}
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition"
                    placeholder="Ulangi password baru">
            </div>

            {{-- Submit Buttons --}}
            <div class="flex gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('user.profile.show') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                    Batal
                </a>
                <button type="submit" class="flex-1 px-5 py-2.5 text-sm font-medium text-white bg-orange-600 rounded-lg hover:bg-orange-700 transition flex items-center justify-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    {{-- Security Info --}}
    <div class="mt-6 bg-blue-50 rounded-xl p-5 border border-blue-100">
        <div class="flex gap-3">
            <div class="flex-shrink-0">
                <i data-lucide="shield" class="w-5 h-5 text-blue-600"></i>
            </div>
            <div>
                <h4 class="text-sm font-medium text-blue-900">Keamanan Akun</h4>
                <p class="text-sm text-blue-700 mt-1">
                    Jaga password Anda tetap aman. Kami tidak akan pernah meminta password Anda melalui email atau pesan lain.
                </p>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();
    });
</script>
@endpush
