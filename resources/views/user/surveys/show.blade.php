@extends('layouts.user')

@section('title', 'Detail Survey')
@section('page-title', 'Detail Survey')
@section('page-description', $survey->title)

@section('content')
<div class="space-y-6">

    {{-- Back Button --}}
    <div>
        <a href="{{ route('user.surveys.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Kembali ke Daftar Survey
        </a>
    </div>

    {{-- Survey Header --}}
    <div class="bg-white rounded-xl border border-gray-200/80 p-6">
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-orange-100 to-amber-100 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="clipboard-list" class="w-7 h-7 text-orange-600"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900">{{ $survey->title }}</h1>
                    <p class="text-sm text-gray-500 mt-1">Dibuat pada {{ $survey->created_at->format('d F Y, H:i') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                @if($latestTransaction && $latestTransaction->status === 'pending')
                    <a href="{{ route('user.payments.show', $latestTransaction->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-orange-600 text-white rounded-lg font-medium text-sm hover:bg-orange-700 transition">
                        <i data-lucide="credit-card" class="w-4 h-4"></i>
                        Bayar Sekarang
                    </a>
                @endif
                <button class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition">
                    <i data-lucide="more-vertical" class="w-5 h-5"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-gray-200/80 p-5">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                    <i data-lucide="help-circle" class="w-4 h-4 text-blue-600"></i>
                </div>
                <span class="text-xs font-medium text-gray-400">Pertanyaan</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $survey->question_count }}</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200/80 p-5">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                    <i data-lucide="users" class="w-4 h-4 text-emerald-600"></i>
                </div>
                <span class="text-xs font-medium text-gray-400">Responden</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $survey->responses->count() }}</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200/80 p-5">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center">
                    <i data-lucide="wallet" class="w-4 h-4 text-purple-600"></i>
                </div>
                <span class="text-xs font-medium text-gray-400">Total Biaya</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($latestTransaction->amount ?? 0, 0, ',', '.') }}</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200/80 p-5">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center">
                    <i data-lucide="clock" class="w-4 h-4 text-amber-600"></i>
                </div>
                <span class="text-xs font-medium text-gray-400">Status</span>
            </div>
            @php $status = $latestTransaction->status ?? 'pending'; @endphp
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-sm font-medium
                @if($status === 'paid') bg-emerald-100 text-emerald-700
                @elseif($status === 'pending') bg-amber-100 text-amber-700
                @else bg-red-100 text-red-700
                @endif">
                @if($status === 'paid') Dibayar
                @elseif($status === 'pending') Menunggu Pembayaran
                @else {{ ucfirst($status) }}
                @endif
            </span>
        </div>
    </div>

    {{-- Progress Section --}}
    <div class="bg-white rounded-xl border border-gray-200/80 p-6">
        <h2 class="text-sm font-semibold text-gray-900 mb-4">Progress Pengerjaan</h2>
        
        @php $progress = $latestTransaction->progress ?? 0; @endphp
        
        <div class="mb-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-gray-600">Pengumpulan Data</span>
                <span class="text-sm font-semibold text-gray-900">{{ $progress }}%</span>
            </div>
            <div class="w-full h-3 bg-gray-200 rounded-full overflow-hidden">
                <div class="h-full rounded-full transition-all duration-500
                    @if($progress >= 100) bg-gradient-to-r from-emerald-500 to-emerald-400
                    @elseif($progress > 0) bg-gradient-to-r from-blue-500 to-blue-400
                    @else bg-gray-300
                    @endif"
                    style="width: {{ $progress }}%"></div>
            </div>
        </div>

        {{-- Progress Steps --}}
        <div class="grid grid-cols-4 gap-2 mt-6">
            @php
                $steps = [
                    ['label' => 'Pembayaran', 'threshold' => 0],
                    ['label' => 'Persiapan', 'threshold' => 25],
                    ['label' => 'Pengumpulan', 'threshold' => 50],
                    ['label' => 'Selesai', 'threshold' => 100],
                ];
            @endphp
            @foreach($steps as $index => $step)
                @php
                    $isCompleted = $progress >= $step['threshold'];
                    $isCurrent = $progress >= $step['threshold'] && ($index === count($steps) - 1 || $progress < $steps[$index + 1]['threshold']);
                @endphp
                <div class="text-center">
                    <div class="w-10 h-10 rounded-full mx-auto mb-2 flex items-center justify-center
                        @if($isCompleted) bg-orange-600 text-white
                        @else bg-gray-100 text-gray-400
                        @endif">
                        @if($isCompleted && $progress >= 100 && $index === count($steps) - 1)
                            <i data-lucide="check" class="w-5 h-5"></i>
                        @else
                            <span class="text-sm font-semibold">{{ $index + 1 }}</span>
                        @endif
                    </div>
                    <p class="text-xs font-medium @if($isCurrent) text-orange-600 @else text-gray-500 @endif">{{ $step['label'] }}</p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Export Section --}}
    <div class="bg-white rounded-xl border border-gray-200/80 p-6">
        <h2 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
            <i data-lucide="download" class="w-4 h-4"></i>
            Export Data
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <a href="{{ route('user.surveys.export-pdf', $survey) }}" class="flex items-center justify-center gap-2 px-4 py-3 bg-orange-50 text-orange-600 rounded-lg font-medium text-sm hover:bg-orange-100 transition border border-orange-200">
                <i data-lucide="file-pdf" class="w-4 h-4"></i>
                Laporan Survey (PDF)
            </a>
            @if($survey->responses->isNotEmpty())
                <a href="{{ route('user.surveys.export-responses-pdf', $survey) }}" class="flex items-center justify-center gap-2 px-4 py-3 bg-blue-50 text-blue-600 rounded-lg font-medium text-sm hover:bg-blue-100 transition border border-blue-200">
                    <i data-lucide="file-text" class="w-4 h-4"></i>
                    Data Respons (PDF)
                </a>
            @endif
        </div>
    </div>

    {{-- Transaction History --}}
    <div class="bg-white rounded-xl border border-gray-200/80 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-900">Riwayat Transaksi</h2>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($survey->transactions as $transaction)
                <div class="flex items-center gap-4 px-6 py-4">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center
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
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">
                            @if($transaction->status === 'paid') Pembayaran Berhasil
                            @elseif($transaction->status === 'pending') Menunggu Pembayaran
                            @else Pembayaran Gagal
                            @endif
                        </p>
                        <p class="text-xs text-gray-500">{{ $transaction->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="text-right">
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
                <div class="px-6 py-8 text-center text-sm text-gray-500">
                    Belum ada transaksi
                </div>
            @endforelse
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
