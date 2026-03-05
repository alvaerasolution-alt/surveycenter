<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Dashboard — SurveyCenter</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-xl" x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)">

        {{-- Logo --}}
        <div class="text-center mb-8" x-show="show" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-orange-600 mb-4">
                <span class="text-white font-bold text-xl">SC</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Hai, Admin! 👋</h1>
            <p class="text-gray-500 mt-1 text-sm">Pilih dashboard yang ingin kamu akses</p>
        </div>

        {{-- Dashboard Options --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4"
             x-show="show"
             x-transition:enter="transition ease-out duration-500 delay-200"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0">

            {{-- Admin Dashboard --}}
            <a href="/admin/dashboard"
               class="group relative overflow-hidden bg-white rounded-2xl border border-gray-200 p-6 hover:border-orange-300 hover:shadow-xl hover:shadow-orange-100/50 transition-all duration-300">
                <div class="absolute top-0 right-0 w-24 h-24 bg-orange-50 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:bg-orange-100 transition"></div>
                <div class="relative z-10">
                    <div class="w-12 h-12 rounded-xl bg-orange-50 group-hover:bg-orange-100 flex items-center justify-center mb-4 transition">
                        <i data-lucide="layout-dashboard" class="w-6 h-6 text-orange-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">Dashboard Admin</h3>
                    <p class="text-sm text-gray-500">Kelola konten, layanan, dan pengaturan website</p>
                    <div class="mt-4 flex items-center gap-1 text-orange-600 text-sm font-medium group-hover:gap-2 transition-all">
                        <span>Masuk</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </div>
                </div>
            </a>

            {{-- CRM Dashboard --}}
            <a href="{{ route('crm.dashboard') }}"
               class="group relative overflow-hidden bg-white rounded-2xl border border-gray-200 p-6 hover:border-blue-300 hover:shadow-xl hover:shadow-blue-100/50 transition-all duration-300">
                <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:bg-blue-100 transition"></div>
                <div class="relative z-10">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 group-hover:bg-blue-100 flex items-center justify-center mb-4 transition">
                        <i data-lucide="bar-chart-3" class="w-6 h-6 text-blue-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">Dashboard CRM</h3>
                    <p class="text-sm text-gray-500">Kelola pipeline, follow-up, dan customer</p>
                    <div class="mt-4 flex items-center gap-1 text-blue-600 text-sm font-medium group-hover:gap-2 transition-all">
                        <span>Masuk</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </div>
                </div>
            </a>
        </div>

        {{-- Footer --}}
        <p class="text-center text-xs text-gray-400 mt-8" x-show="show" x-transition:enter="transition ease-out duration-500 delay-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            © {{ date('Y') }} SurveyCenter. All rights reserved.
        </p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>
