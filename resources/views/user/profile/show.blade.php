@extends('layouts.user')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')
@section('page-description', 'Lihat informasi akun Anda')

@section('content')
<div class="max-w-2xl mx-auto">

    {{-- Tabs --}}
    <div class="bg-white rounded-xl border border-gray-200/80 mb-6">
        <div class="flex border-b border-gray-100">
            <a href="{{ route('user.profile.edit') }}" 
                class="flex-1 px-6 py-4 text-sm font-medium text-center border-b-2 {{ request()->routeIs('user.profile.edit') ? 'border-orange-600 text-orange-600' : 'border-transparent text-gray-600 hover:text-gray-900' }} transition">
                <i data-lucide="user" class="w-4 h-4 inline mr-2"></i>
                Edit Profil
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

    {{-- Profile Card --}}
    <div class="bg-white rounded-xl border border-gray-200/80 overflow-hidden">
        <div class="h-32 bg-gradient-to-r from-orange-500 to-amber-500"></div>

        <div class="px-6 pb-6">
            {{-- Avatar & Name --}}
            <div class="flex items-end gap-4 -mt-16 mb-6">
                <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-orange-100 to-amber-100 border-4 border-white flex items-center justify-center flex-shrink-0 shadow-md">
                    <i data-lucide="user" class="w-12 h-12 text-orange-600"></i>
                </div>
                <div class="flex-1">
                    <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                </div>
                <a href="{{ route('user.profile.edit') }}" class="px-4 py-2 bg-orange-600 text-white rounded-lg text-sm font-medium hover:bg-orange-700 transition">
                    Edit
                </a>
            </div>

            {{-- Info Grid --}}
            <div class="grid grid-cols-2 gap-6 border-t border-gray-100 pt-6">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase mb-2">Nama Lengkap</p>
                    <p class="text-base font-semibold text-gray-900">{{ $user->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase mb-2">Email</p>
                    <p class="text-base font-semibold text-gray-900">{{ $user->email }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase mb-2">Status</p>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                        <span class="text-base font-semibold text-gray-900">Aktif</span>
                    </div>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase mb-2">Bergabung Sejak</p>
                    <p class="text-base font-semibold text-gray-900">{{ $user->created_at->format('d M Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Account Stats --}}
    <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl p-5 border border-blue-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-blue-600 uppercase">Survey Aktif</p>
                    <p class="text-2xl font-bold text-blue-900 mt-2">
                        {{ \App\Models\Survey::where('user_id', $user->id)->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-blue-200 flex items-center justify-center">
                    <i data-lucide="file-text" class="w-6 h-6 text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-50 to-amber-50 rounded-xl p-5 border border-orange-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-orange-600 uppercase">Total Transaksi</p>
                    <p class="text-2xl font-bold text-orange-900 mt-2">
                        {{ \App\Models\Transaction::where('user_id', $user->id)->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-orange-200 flex items-center justify-center">
                    <i data-lucide="receipt" class="w-6 h-6 text-orange-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-emerald-50 to-green-50 rounded-xl p-5 border border-emerald-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-emerald-600 uppercase">Total Dihabiskan</p>
                    <p class="text-2xl font-bold text-emerald-900 mt-2">
                        Rp {{ number_format(\App\Models\Transaction::where('user_id', $user->id)->sum('amount'), 0, ',', '.') }}
                    </p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-emerald-200 flex items-center justify-center">
                    <i data-lucide="trending-up" class="w-6 h-6 text-emerald-600"></i>
                </div>
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
