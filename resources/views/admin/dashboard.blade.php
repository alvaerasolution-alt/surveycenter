@extends('layouts.admin')
@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')
@section('breadcrumb')
    <span class="text-gray-600">Dashboard</span>
@endsection

@section('content')
<div class="space-y-8">

    {{-- Welcome Banner --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-orange-600 via-orange-700 to-purple-700 text-white p-8" x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)">
        <div class="absolute top-0 right-0 w-80 h-80 bg-white/5 rounded-full -translate-y-1/3 translate-x-1/4 blur-2xl"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-purple-400/10 rounded-full translate-y-1/3 -translate-x-1/4 blur-xl"></div>
        <div class="absolute right-8 bottom-6 opacity-[0.06]">
            <svg viewBox="0 0 200 200" class="w-32 h-32" fill="currentColor">
                <rect x="20" y="20" width="60" height="60" rx="12"/>
                <rect x="100" y="20" width="80" height="60" rx="12"/>
                <rect x="20" y="100" width="80" height="80" rx="12"/>
                <rect x="120" y="100" width="60" height="80" rx="12"/>
            </svg>
        </div>
        <div class="relative z-10" x-show="show" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <p class="text-orange-200 text-xs font-medium uppercase tracking-widest mb-2">{{ now()->translatedFormat('l, d F Y') }}</p>
            <h1 class="text-2xl sm:text-3xl font-bold leading-tight">Selamat Datang, Admin 👋</h1>
            <p class="mt-2 text-orange-200 text-sm max-w-md">Kelola konten, layanan, dan transaksi dari dashboard ini.</p>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-semibold text-gray-900">Aksi Cepat</h2>
        </div>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
            @php
                $router = app('router');
                $actions = [
                    ['route' => 'admin.articles.create', 'fallbacks' => ['articles.create'], 'icon' => 'file-plus', 'label' => 'Artikel Baru', 'color' => 'orange', 'gradient' => 'from-orange-500 to-orange-600'],
                    ['route' => 'admin.layanan.create', 'fallbacks' => ['layanan.create'], 'icon' => 'briefcase', 'label' => 'Layanan Baru', 'color' => 'emerald', 'gradient' => 'from-emerald-500 to-emerald-600'],
                    ['route' => 'admin.transactions.index', 'fallbacks' => ['transactions.index'], 'icon' => 'credit-card', 'label' => 'Lihat Transaksi', 'color' => 'amber', 'gradient' => 'from-amber-500 to-amber-600'],
                    ['route' => 'crm.dashboard', 'fallbacks' => [], 'icon' => 'bar-chart-3', 'label' => 'Buka CRM', 'color' => 'blue', 'gradient' => 'from-blue-500 to-cyan-600'],
                    ['route' => 'admin.surveys.manage', 'fallbacks' => ['surveys.manage'], 'icon' => 'link-2', 'label' => 'Cek URL Form', 'color' => 'rose', 'gradient' => 'from-rose-500 to-pink-600'],
                ];
            @endphp

            @foreach($actions as $action)
                @php
                    $actionRouteName = $action['route'];
                    if (! $router->has($actionRouteName)) {
                        foreach ($action['fallbacks'] as $fallbackRoute) {
                            if ($router->has($fallbackRoute)) {
                                $actionRouteName = $fallbackRoute;
                                break;
                            }
                        }
                    }
                @endphp
                @if ($router->has($actionRouteName))
                    <a href="{{ route($actionRouteName) }}"
                       class="group flex items-center gap-3 bg-white rounded-xl border border-gray-200/80 p-3.5 hover:shadow-md hover:border-{{ $action['color'] }}-200 transition-all duration-200">
                        <div class="w-9 h-9 rounded-lg bg-gradient-to-br {{ $action['gradient'] }} flex items-center justify-center flex-shrink-0 shadow-sm group-hover:shadow-md transition">
                            <i data-lucide="{{ $action['icon'] }}" class="w-4 h-4 text-white"></i>
                        </div>
                        <span class="text-[13px] font-medium text-gray-700 group-hover:text-gray-900">{{ $action['label'] }}</span>
                    </a>
                @endif
            @endforeach
        </div>
    </div>

    {{-- Navigation Cards --}}
    <div>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-semibold text-gray-900">Menu Admin</h2>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
            @php
                $menuCards = [
                    ['route' => 'settings.edit', 'fallbacks' => [], 'icon' => 'settings', 'label' => 'Pengaturan', 'desc' => 'Konfigurasi website'],
                    ['route' => 'admin.surveys.manage', 'fallbacks' => ['surveys.manage'], 'icon' => 'link-2', 'label' => 'URL Form Survey', 'desc' => 'Cek link form user'],
                    ['route' => 'tabs.index', 'fallbacks' => [], 'icon' => 'layers', 'label' => 'Kelola Tabs', 'desc' => 'Tab halaman utama'],
                    ['route' => 'admin.discount-banners.index', 'fallbacks' => ['discount-banners.index'], 'icon' => 'image', 'label' => 'Banners', 'desc' => 'Banner promosi'],
                    ['route' => 'partner-logos.index', 'fallbacks' => [], 'icon' => 'award', 'label' => 'Partner Logos', 'desc' => 'Logo klien & partner'],
                    ['route' => 'customer-stories.index', 'fallbacks' => [], 'icon' => 'message-square-quote', 'label' => 'Customer Stories', 'desc' => 'Testimoni pelanggan'],
                    ['route' => 'admin.articles.index', 'fallbacks' => ['articles.index'], 'icon' => 'file-text', 'label' => 'Articles', 'desc' => 'Blog & artikel'],
                    ['route' => 'admin.layanan.index', 'fallbacks' => ['layanan.index'], 'icon' => 'briefcase', 'label' => 'Layanan', 'desc' => 'Daftar layanan'],
                    ['route' => 'admin.seo.index', 'fallbacks' => ['seo.index'], 'icon' => 'search', 'label' => 'SEO', 'desc' => 'Optimasi mesin pencari'],
                ];
            @endphp

            @foreach($menuCards as $card)
                @php
                    $menuRouteName = $card['route'];
                    if (! $router->has($menuRouteName)) {
                        foreach ($card['fallbacks'] as $fallbackRoute) {
                            if ($router->has($fallbackRoute)) {
                                $menuRouteName = $fallbackRoute;
                                break;
                            }
                        }
                    }
                @endphp
                @if ($router->has($menuRouteName))
                    <a href="{{ route($menuRouteName) }}"
                       class="group bg-white rounded-xl border border-gray-200/80 p-4 hover:shadow-md hover:border-orange-200 transition-all duration-200">
                        <div class="w-9 h-9 rounded-lg bg-gray-50 group-hover:bg-orange-50 flex items-center justify-center mb-3 transition">
                            <i data-lucide="{{ $card['icon'] }}" class="w-[18px] h-[18px] text-gray-400 group-hover:text-orange-600 transition"></i>
                        </div>
                        <h3 class="text-[13px] font-semibold text-gray-800 group-hover:text-gray-900">{{ $card['label'] }}</h3>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $card['desc'] }}</p>
                    </a>
                @endif
            @endforeach
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
