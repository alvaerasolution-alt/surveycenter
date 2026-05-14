@extends('layouts.user')

@section('title', 'Pembayaran Transaksi')
@section('page-title', 'Proses Pembayaran')
@section('page-description', 'Pilih metode pembayaran untuk melanjutkan survey')

@section('content')
<div class="max-w-2xl mx-auto">
    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route('user.transactions.show', $transaction) }}" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-orange-600 transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Kembali ke Detail Transaksi
        </a>
    </div>

    {{-- Transaction Summary --}}
    <div class="bg-white rounded-xl border border-gray-200/80 p-6 mb-6">
        <div class="flex items-start justify-between mb-6">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase mb-2">Jumlah Pembayaran</p>
                <p class="text-4xl font-bold text-gray-900">
                    Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                </p>
            </div>
            <div class="text-right">
                <p class="text-xs font-medium text-gray-500 uppercase mb-2">Survey</p>
                <p class="text-sm font-semibold text-gray-900">{{ $transaction->survey->title ?? 'Survey' }}</p>
                <p class="text-xs text-gray-500 mt-1">ID: #{{ $transaction->id }}</p>
            </div>
        </div>

        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 flex gap-3">
            <i data-lucide="info" class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5"></i>
            <div class="text-sm text-amber-700">
                <p class="font-medium mb-1">Pembayaran valid selama 30 menit</p>
                <p>Transaksi akan otomatis dibatalkan jika tidak diselesaikan dalam waktu tersebut.</p>
            </div>
        </div>
    </div>

    {{-- Payment Method Selection --}}
    <div class="bg-white rounded-xl border border-gray-200/80 overflow-hidden">
        <div class="bg-gradient-to-r from-orange-50 to-amber-50 px-6 py-5 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900">Pembayaran dengan Saldo</h3>
            <p class="text-sm text-gray-600 mt-1">Gunakan saldo akun Anda untuk membayar transaksi ini.</p>
        </div>

        <div class="p-6">
            <div class="mb-6 bg-gray-50 border border-gray-200 rounded-lg p-4 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Saldo Anda Saat Ini</p>
                    <p class="text-2xl font-bold {{ $depositBalance >= $transaction->amount ? 'text-emerald-600' : 'text-red-600' }}">
                        Rp {{ number_format($depositBalance, 0, ',', '.') }}
                    </p>
                </div>
                @if($depositBalance < $transaction->amount)
                    <div class="text-right">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            Saldo Kurang
                        </span>
                    </div>
                @else
                    <div class="text-right">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                            Saldo Cukup
                        </span>
                    </div>
                @endif
            </div>

            @if($depositBalance >= $transaction->amount)
                <form action="{{ route('user.payments.process', $transaction) }}" method="POST">
                    @csrf
                    
                    {{-- Submit Button --}}
                    <div class="flex gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('user.transactions.show', $transaction) }}" class="flex-1 px-4 py-3 bg-gray-100 text-gray-900 rounded-lg font-medium text-sm hover:bg-gray-200 transition text-center">
                            Batal
                        </a>
                        <button type="submit" class="flex-1 px-4 py-3 bg-orange-600 text-white rounded-lg font-medium text-sm hover:bg-orange-700 transition flex items-center justify-center gap-2">
                            <i data-lucide="credit-card" class="w-4 h-4"></i>
                            Bayar Sekarang (Rp {{ number_format($transaction->amount, 0, ',', '.') }})
                        </button>
                    </div>
                </form>
            @else
                <div class="text-center py-4">
                    <p class="text-gray-600 mb-4">Saldo Anda tidak mencukupi untuk melakukan pembayaran ini. Silakan lakukan Top Up saldo terlebih dahulu sebesar minimal <strong>Rp {{ number_format($transaction->amount - $depositBalance, 0, ',', '.') }}</strong>.</p>
                    
                    <div class="flex gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('user.transactions.show', $transaction) }}" class="flex-1 px-4 py-3 bg-gray-100 text-gray-900 rounded-lg font-medium text-sm hover:bg-gray-200 transition text-center">
                            Kembali
                        </a>
                        <a href="{{ route('user.topups.create') }}" class="flex-1 px-4 py-3 bg-emerald-600 text-white rounded-lg font-medium text-sm hover:bg-emerald-700 transition flex items-center justify-center gap-2">
                            <i data-lucide="plus-circle" class="w-4 h-4"></i>
                            Top Up Saldo
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Security Notice --}}
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4 flex gap-3">
        <i data-lucide="lock" class="w-5 h-5 text-blue-600 flex-shrink-0"></i>
        <div class="text-sm text-blue-700">
            <p class="font-medium mb-1">Transaksi Aman & Terenkripsi</p>
            <p>Pembayaran diproses melalui gateway pembayaran tersertifikasi dengan enkripsi SSL 256-bit</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();
    });
</script>
@endpush
@endsection
