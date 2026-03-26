@extends('layouts.user')

@section('title', 'Detail Transaksi')
@section('page-title', 'Detail Transaksi')
@section('page-description', 'Informasi lengkap transaksi survey')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Back Button --}}
    <div>
        <a href="{{ route('user.transactions.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-orange-600 transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Kembali ke Riwayat Transaksi
        </a>
    </div>

    {{-- Transaction Header --}}
    <div class="bg-white rounded-xl border border-gray-200/80 overflow-hidden">
        <div class="bg-gradient-to-r from-orange-50 to-amber-50 px-6 py-5 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-lg bg-white border border-gray-200 flex items-center justify-center">
                        <i data-lucide="receipt" class="w-7 h-7 text-orange-600"></i>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase">Nomor Transaksi</p>
                        <p class="text-xl font-bold text-gray-900">#{{ $transaction->id }}</p>
                    </div>
                </div>
                <div class="text-right">
                    @php
                        $statusClass = match($transaction->status) {
                            'paid' => 'bg-emerald-100 text-emerald-700',
                            'pending' => 'bg-amber-100 text-amber-700',
                            'failed' => 'bg-red-100 text-red-700',
                            default => 'bg-gray-100 text-gray-700'
                        };
                        $statusLabel = match($transaction->status) {
                            'paid' => 'Dibayar',
                            'pending' => 'Pending',
                            'failed' => 'Gagal',
                            default => ucfirst($transaction->status)
                        };
                    @endphp
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold {{ $statusClass }}">
                        {{ $statusLabel }}
                    </span>
                </div>
            </div>
        </div>

        <div class="p-6 space-y-6">
            {{-- Amount Section --}}
            <div class="border-b border-gray-100 pb-6">
                <p class="text-xs font-medium text-gray-500 uppercase mb-2">Jumlah Pembayaran</p>
                <p class="text-4xl font-bold text-gray-900">
                    Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                </p>
            </div>

            {{-- Survey Info --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase mb-3">Survey</p>
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-lg bg-orange-100 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="file-text" class="w-6 h-6 text-orange-600"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">
                                {{ $transaction->survey?->title ?? 'Survey Tidak Ditemukan' }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $transaction->survey?->question_count ?? 0 }} Pertanyaan
                            </p>
                        </div>
                    </div>
                </div>

                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase mb-3">Tanggal Transaksi</p>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ $transaction->created_at->format('d F Y') }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $transaction->created_at->format('H:i:s') }}</p>
                    </div>
                </div>
            </div>

            {{-- Progress Section --}}
            <div class="border-t border-gray-100 pt-6">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-medium text-gray-500 uppercase">Progress Pengerjaan</p>
                    <p class="text-sm font-bold text-gray-900">{{ $transaction->progress }}%</p>
                </div>
                <div class="w-full h-3 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full rounded-full transition-all
                        @if($transaction->progress >= 100) bg-emerald-500
                        @elseif($transaction->progress > 0) bg-blue-500
                        @else bg-gray-300
                        @endif"
                        style="width: {{ $transaction->progress }}%"></div>
                </div>
                <p class="text-xs text-gray-500 mt-3">
                    @if($transaction->progress >= 100)
                        <i data-lucide="check-circle" class="w-4 h-4 inline mr-1 text-emerald-600"></i>
                        Pengerjaan survey telah selesai
                    @elseif($transaction->progress > 0)
                        <i data-lucide="loader" class="w-4 h-4 inline mr-1 text-blue-600"></i>
                        Survey sedang dikerjakan
                    @else
                        <i data-lucide="clock" class="w-4 h-4 inline mr-1 text-amber-600"></i>
                        Survey menunggu untuk dikerjakan
                    @endif
                </p>
            </div>

            {{-- Additional Info --}}
            <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                <h4 class="text-sm font-semibold text-gray-900">Informasi Transaksi</h4>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Metode Pembayaran</p>
                        <p class="font-medium text-gray-900">
                            {{ $transaction->payment_method ? ucfirst($transaction->payment_method) : 'Belum ditentukan' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Referensi Pembayaran</p>
                        <p class="font-medium text-gray-900">
                            {{ $transaction->payment_ref ?? '-' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Nomor Bill</p>
                        <p class="font-medium text-gray-900">
                            {{ $transaction->bill_no ?? '-' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">ID Transaksi</p>
                        <p class="font-medium text-gray-900">
                            {{ $transaction->trx_id ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="flex gap-3">
        <a href="{{ route('user.surveys.show', $transaction->survey) }}" class="flex-1 px-4 py-3 bg-orange-600 text-white rounded-lg font-medium text-sm hover:bg-orange-700 transition text-center">
            <i data-lucide="eye" class="w-4 h-4 inline mr-2"></i>
            Lihat Survey
        </a>
        @if($transaction->status === 'pending')
            <button onclick="alert('Fitur pembayaran akan segera tersedia')" class="flex-1 px-4 py-3 bg-blue-600 text-white rounded-lg font-medium text-sm hover:bg-blue-700 transition">
                <i data-lucide="credit-card" class="w-4 h-4 inline mr-2"></i>
                Bayar Sekarang
            </button>
        @endif
    </div>

    {{-- Status Info Card --}}
    @if($transaction->status === 'pending')
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-5">
            <div class="flex gap-3">
                <div class="flex-shrink-0">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-amber-600"></i>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-amber-900">Pembayaran Tertunda</h4>
                    <p class="text-sm text-amber-700 mt-1">
                        Transaksi ini masih menunggu pembayaran. Silakan lakukan pembayaran untuk memulai proses survey.
                    </p>
                </div>
            </div>
        </div>
    @elseif($transaction->status === 'failed')
        <div class="bg-red-50 border border-red-200 rounded-xl p-5">
            <div class="flex gap-3">
                <div class="flex-shrink-0">
                    <i data-lucide="x-circle" class="w-5 h-5 text-red-600"></i>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-red-900">Pembayaran Gagal</h4>
                    <p class="text-sm text-red-700 mt-1">
                        Transaksi ini gagal. Silakan hubungi dukungan pelanggan untuk bantuan lebih lanjut.
                    </p>
                </div>
            </div>
        </div>
    @elseif($transaction->status === 'paid')
        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-5">
            <div class="flex gap-3">
                <div class="flex-shrink-0">
                    <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-emerald-900">Pembayaran Berhasil</h4>
                    <p class="text-sm text-emerald-700 mt-1">
                        Pembayaran telah dikonfirmasi. Survey sedang diproses oleh tim kami.
                    </p>
                </div>
            </div>
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
