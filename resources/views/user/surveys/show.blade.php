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
                @if($latestTransaction && $latestTransaction->status === \App\Models\Transaction::STATUS_PENDING)
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
                <span class="text-xs font-medium text-gray-400">Target Responden</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $survey->respondent_count }}</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200/80 p-5">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-8 h-8 rounded-lg bg-cyan-100 flex items-center justify-center">
                    <i data-lucide="user-check" class="w-4 h-4 text-cyan-700"></i>
                </div>
                <span class="text-xs font-medium text-gray-400">Responden Diperoleh</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $survey->adminResponses->sum('respond_count') }}</p>
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
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-sm font-medium {{ $latestTransaction?->statusBadgeClass() ?? \App\Models\Transaction::getStatusBadgeClass($status) }}">
                {{ $latestTransaction?->statusLabel() ?? \App\Models\Transaction::getStatusLabel($status) }}
            </span>
        </div>
    </div>

    {{-- Link Survey --}}
    <div class="bg-white rounded-xl border border-gray-200/80 p-6">
        <h2 class="text-sm font-semibold text-gray-900 mb-4">Link Survey</h2>
        @if($survey->form_link)
            <a href="{{ $survey->form_link }}" target="_blank" rel="noopener noreferrer"
                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-700 rounded-lg text-sm font-medium hover:bg-blue-100 transition border border-blue-200">
                <i data-lucide="link" class="w-4 h-4"></i>
                Buka Link Survey
            </a>
            <p class="mt-2 text-xs text-gray-500 break-all">{{ $survey->form_link }}</p>
        @elseif(optional($survey->responses->first())->google_form_link)
            <a href="{{ $survey->responses->first()->google_form_link }}" target="_blank" rel="noopener noreferrer"
                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-700 rounded-lg text-sm font-medium hover:bg-blue-100 transition border border-blue-200">
                <i data-lucide="link" class="w-4 h-4"></i>
                Buka Link Survey
            </a>
            <p class="mt-2 text-xs text-gray-500 break-all">{{ $survey->responses->first()->google_form_link }}</p>
        @else
            <p class="text-sm text-gray-500">Belum ada link survey.</p>
        @endif
    </div>

    {{-- Progress Section --}}
    <div class="bg-white rounded-xl border border-gray-200/80 p-6">
        @php
            $status = $latestTransaction?->status ?? 'pending';
            $progress = $latestTransaction?->safeProgress() ?? 0;
            $createPaymentCompleted = $latestTransaction?->isStage1Completed() ?? false;
            $hasilCompleted = $latestTransaction?->isStage2Completed() ?? false;
        @endphp

        <h2 class="text-sm font-semibold text-gray-900 mb-4">Tahapan</h2>

        <div class="grid grid-cols-2 gap-3 mb-6">
            <div class="rounded-lg border px-4 py-3 {{ $createPaymentCompleted ? 'bg-emerald-50 border-emerald-200' : 'bg-amber-50 border-amber-200' }}">
                <p class="text-xs font-semibold uppercase {{ $createPaymentCompleted ? 'text-emerald-700' : 'text-amber-700' }}">Tahap 1</p>
                <p class="text-sm font-semibold text-gray-900 mt-1">Create Pembayaran</p>
                <p class="text-xs mt-1 {{ $createPaymentCompleted ? 'text-emerald-700' : 'text-amber-700' }}">
                    {{ $createPaymentCompleted ? 'Selesai' : 'Menunggu Pembayaran' }}
                </p>
            </div>

            <div class="rounded-lg border px-4 py-3 {{ $hasilCompleted ? 'bg-emerald-50 border-emerald-200' : 'bg-gray-50 border-gray-200' }}">
                <p class="text-xs font-semibold uppercase {{ $hasilCompleted ? 'text-emerald-700' : 'text-gray-600' }}">Tahap 2</p>
                <p class="text-sm font-semibold text-gray-900 mt-1">Hasil</p>
                <p class="text-xs mt-1 {{ $hasilCompleted ? 'text-emerald-700' : 'text-gray-600' }}">
                    {{ $hasilCompleted ? 'Selesai' : 'Dalam proses pengerjaan' }}
                </p>
            </div>
        </div>

        <h3 class="text-sm font-semibold text-gray-900 mb-4">Progress Pengerjaan</h3>
        
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
                    data-progress-width="{{ $progress }}"></div>
            </div>
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
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center {{ $transaction->statusIconBackgroundClass() }}">
                        <i data-lucide="receipt" class="w-5 h-5 {{ $transaction->statusIconColorClass() }}"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ $transaction->statusActivityLabel() }}</p>
                        <p class="text-xs text-gray-500">{{ $transaction->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="text-right flex flex-col items-end gap-2">
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</p>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $transaction->statusBadgeClass() }}">
                                {{ $transaction->statusLabel() }}
                            </span>
                        </div>
                        <a href="{{ route('transactions.download', $transaction->id) }}" class="text-xs text-blue-600 hover:text-blue-800 flex items-center gap-1">
                            <i data-lucide="download" class="w-3 h-3"></i> Unduh Invoice
                        </a>
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
        document.querySelectorAll('[data-progress-width]').forEach(function(el) {
            const value = parseInt(el.dataset.progressWidth || '0', 10);
            const safeValue = Math.min(Math.max(value, 0), 100);
            el.style.width = safeValue + '%';
        });

        if (typeof lucide !== 'undefined') lucide.createIcons();
    });
</script>
@endpush
