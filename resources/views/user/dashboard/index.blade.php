@extends('layouts.user')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'Selamat datang di dashboard SurveyCenter')

@section('content')
<div class="space-y-6">

    {{-- Welcome Banner --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-orange-500 via-orange-600 to-amber-600 text-white p-6 sm:p-8">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/4 blur-2xl"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-amber-400/20 rounded-full translate-y-1/2 -translate-x-1/4 blur-xl"></div>
        <div class="relative z-10">
            <p class="text-orange-200 text-xs font-medium uppercase tracking-widest mb-2">{{ now()->translatedFormat('l, d F Y') }}</p>
            <h1 class="text-2xl sm:text-3xl font-bold leading-tight">Selamat Datang, {{ $user->name }}!</h1>
            <p class="mt-2 text-orange-100 text-sm max-w-lg">Kelola survey Anda dengan mudah. Buat survey baru dengan harga mulai Rp 1.000 per pertanyaan.</p>
            <a href="{{ route('user.surveys.create') }}" class="inline-flex items-center gap-2 mt-4 px-5 py-2.5 bg-white text-orange-600 rounded-lg font-semibold text-sm hover:bg-orange-50 transition shadow-lg shadow-orange-700/20">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Buat Survey Baru
            </a>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Total Survey --}}
        <div class="bg-white rounded-xl border border-gray-200/80 p-5 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center">
                    <i data-lucide="clipboard-list" class="w-5 h-5 text-orange-600"></i>
                </div>
                <span class="text-xs font-medium text-gray-400">Total</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $totalSurveys }}</p>
            <p class="text-xs text-gray-500 mt-1">Survey dibuat</p>
        </div>

        {{-- Total Pertanyaan --}}
        <div class="bg-white rounded-xl border border-gray-200/80 p-5 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                    <i data-lucide="help-circle" class="w-5 h-5 text-blue-600"></i>
                </div>
                <span class="text-xs font-medium text-gray-400">Total</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $totalQuestions }}</p>
            <p class="text-xs text-gray-500 mt-1">Pertanyaan</p>
        </div>

        {{-- Total Responden --}}
        <div class="bg-white rounded-xl border border-gray-200/80 p-5 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center">
                    <i data-lucide="users" class="w-5 h-5 text-emerald-600"></i>
                </div>
                <span class="text-xs font-medium text-gray-400">Total</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $totalResponden }}</p>
            <p class="text-xs text-gray-500 mt-1">Responden</p>
        </div>

        {{-- Total Pengeluaran --}}
        <div class="bg-white rounded-xl border border-gray-200/80 p-5 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                    <i data-lucide="wallet" class="w-5 h-5 text-purple-600"></i>
                </div>
                <span class="text-xs font-medium text-gray-400">Dibayar</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalSpent, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500 mt-1">Total transaksi</p>
        </div>
    </div>

    {{-- Content Grid --}}
    <div class="grid lg:grid-cols-2 gap-6">

        {{-- Survey Terbaru --}}
        <div class="bg-white rounded-xl border border-gray-200/80 overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-900">Survey Terbaru</h2>
                <a href="{{ route('user.surveys.index') }}" class="text-xs font-medium text-orange-600 hover:text-orange-700">Lihat Semua</a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($recentSurveys as $survey)
                    <a href="{{ route('user.surveys.show', $survey) }}" class="flex items-center gap-4 px-5 py-4 hover:bg-gray-50 transition">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-orange-100 to-amber-100 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="file-text" class="w-5 h-5 text-orange-600"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $survey->title }}</p>
                            <p class="text-xs text-gray-500">{{ $survey->question_count }} pertanyaan &bull; {{ $survey->respondent_count }} responden</p>
                        </div>
                        @php
                            $latestTransaction = $survey->transactions->first();
                            $progress = $latestTransaction->progress ?? 0;
                        @endphp
                        <div class="text-right flex-shrink-0">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                @if($progress >= 100) bg-emerald-100 text-emerald-700
                                @elseif($progress > 0) bg-blue-100 text-blue-700
                                @else bg-gray-100 text-gray-600
                                @endif">
                                {{ $progress }}%
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="px-5 py-12 text-center">
                        <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-3">
                            <i data-lucide="clipboard-list" class="w-6 h-6 text-gray-400"></i>
                        </div>
                        <p class="text-sm text-gray-500 mb-3">Belum ada survey</p>
                        <a href="{{ route('user.surveys.create') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-orange-600 hover:text-orange-700">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            Buat Survey Pertama
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Transaksi Terakhir --}}
        <div class="bg-white rounded-xl border border-gray-200/80 overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-900">Transaksi Terakhir</h2>
                <a href="{{ route('user.transactions.index') }}" class="text-xs font-medium text-orange-600 hover:text-orange-700">Lihat Semua</a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($recentTransactions as $transaction)
                    <div class="flex items-center gap-4 px-5 py-4">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0
                            @if($transaction->status === 'paid') bg-emerald-100
                            @elseif($transaction->status === 'pending') bg-amber-100
                            @else bg-red-100
                            @endif">
                            <i data-lucide="receipt" class="w-5 h-5 
                                @if($transaction->status === 'paid') text-emerald-600
                                @elseif($transaction->status === 'pending') text-amber-600
                                @else text-red-600
                                @endif"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $transaction->survey->title ?? 'Survey' }}</p>
                            <p class="text-xs text-gray-500">{{ $transaction->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="text-sm font-semibold text-gray-900">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</p>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                @if($transaction->status === 'paid') bg-emerald-100 text-emerald-700
                                @elseif($transaction->status === 'pending') bg-amber-100 text-amber-700
                                @else bg-red-100 text-red-700
                                @endif">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-12 text-center">
                        <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-3">
                            <i data-lucide="receipt" class="w-6 h-6 text-gray-400"></i>
                        </div>
                        <p class="text-sm text-gray-500">Belum ada transaksi</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- Pending Payment Alert --}}
    @if($pendingPayments > 0)
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                <i data-lucide="alert-circle" class="w-5 h-5 text-amber-600"></i>
            </div>
            <div class="flex-1">
                <p class="text-sm font-medium text-amber-800">Anda memiliki pembayaran pending</p>
                <p class="text-xs text-amber-600">Total: Rp {{ number_format($pendingPayments, 0, ',', '.') }}</p>
            </div>
            <a href="{{ route('user.transactions.index') }}" class="px-4 py-2 bg-amber-600 text-white rounded-lg text-sm font-medium hover:bg-amber-700 transition">
                Bayar Sekarang
            </a>
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();
    });
</script>
@endpush
