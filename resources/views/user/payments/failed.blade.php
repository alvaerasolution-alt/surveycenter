@extends('layouts.user')

@section('title', 'Pembayaran Gagal')
@section('page-title', 'Pembayaran Gagal')
@section('page-description', 'Terjadi kesalahan saat memproses pembayaran Anda')

@section('content')
<div class="max-w-2xl mx-auto">
    {{-- Error Card --}}
    <div class="bg-white rounded-xl border border-gray-200/80 overflow-hidden">
        <div class="bg-gradient-to-r from-red-50 to-rose-50 px-6 py-12 text-center">
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center">
                    <i data-lucide="x-circle" class="w-8 h-8 text-red-600"></i>
                </div>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Pembayaran Gagal</h2>
            <p class="text-gray-600">Transaksi pembayaran Anda tidak dapat diproses</p>
        </div>

        <div class="p-6 space-y-6">
            {{-- Error Details --}}
            <div class="border-l-4 border-red-500 bg-red-50 p-4 rounded">
                <h3 class="font-semibold text-red-900 mb-2">Penyebab Kemungkinan</h3>
                <ul class="space-y-2 text-sm text-red-700">
                    <li class="flex gap-2">
                        <i data-lucide="check" class="w-4 h-4 flex-shrink-0 mt-0.5"></i>
                        <span>Saldo tidak mencukupi di rekening atau e-wallet Anda</span>
                    </li>
                    <li class="flex gap-2">
                        <i data-lucide="check" class="w-4 h-4 flex-shrink-0 mt-0.5"></i>
                        <span>Transaksi dibatalkan atau timeout</span>
                    </li>
                    <li class="flex gap-2">
                        <i data-lucide="check" class="w-4 h-4 flex-shrink-0 mt-0.5"></i>
                        <span>Metode pembayaran tidak didukung oleh bank/provider Anda</span>
                    </li>
                    <li class="flex gap-2">
                        <i data-lucide="check" class="w-4 h-4 flex-shrink-0 mt-0.5"></i>
                        <span>Batas transaksi harian sudah tercapai</span>
                    </li>
                    <li class="flex gap-2">
                        <i data-lucide="check" class="w-4 h-4 flex-shrink-0 mt-0.5"></i>
                        <span>Koneksi internet terputus saat pemrosesan</span>
                    </li>
                </ul>
            </div>

            {{-- Transaction Details --}}
            <div class="border-b border-gray-100 pb-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i data-lucide="receipt" class="w-4 h-4"></i>
                    Rincian Transaksi
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase mb-1">No. Transaksi</p>
                        <p class="font-mono text-sm font-semibold text-gray-900">#{{ $transaction->id }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase mb-1">Tanggal</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $transaction->created_at->format('d F Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase mb-1">Jumlah</p>
                        <p class="text-sm font-semibold text-gray-900">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase mb-1">Status</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            Gagal
                        </span>
                    </div>
                </div>
            </div>

            {{-- What to Do Next --}}
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-blue-900 mb-3 flex items-center gap-2">
                    <i data-lucide="info" class="w-4 h-4"></i>
                    Apa yang Bisa Anda Lakukan
                </h4>
                <ul class="space-y-3 text-sm text-blue-700">
                    <li class="flex gap-2">
                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-blue-200 text-blue-900 text-xs font-bold flex-shrink-0">1</span>
                        <span>Cek saldo Anda dan pastikan cukup untuk pembayaran</span>
                    </li>
                    <li class="flex gap-2">
                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-blue-200 text-blue-900 text-xs font-bold flex-shrink-0">2</span>
                        <span>Coba gunakan metode pembayaran lain</span>
                    </li>
                    <li class="flex gap-2">
                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-blue-200 text-blue-900 text-xs font-bold flex-shrink-0">3</span>
                        <span>Hubungi bank atau provider e-wallet untuk bantuan</span>
                    </li>
                    <li class="flex gap-2">
                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-blue-200 text-blue-900 text-xs font-bold flex-shrink-0">4</span>
                        <span>Hubungi tim support kami jika masalah berlanjut</span>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="px-6 py-6 bg-gray-50 border-t border-gray-100 flex gap-3 flex-col sm:flex-row">
            <a href="{{ route('user.payments.show', $transaction) }}" class="flex-1 px-4 py-3 bg-orange-600 text-white rounded-lg font-medium text-sm hover:bg-orange-700 transition text-center flex items-center justify-center gap-2">
                <i data-lucide="credit-card" class="w-4 h-4"></i>
                Coba Lagi
            </a>
            <a href="{{ route('user.transactions.show', $transaction) }}" class="flex-1 px-4 py-3 bg-gray-200 text-gray-900 rounded-lg font-medium text-sm hover:bg-gray-300 transition text-center flex items-center justify-center gap-2">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Kembali
            </a>
        </div>
    </div>

    {{-- Support Card --}}
    <div class="mt-6 bg-white rounded-xl border border-gray-200/80 p-6">
        <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
            <i data-lucide="headset" class="w-4 h-4"></i>
            Hubungi Tim Support Kami
        </h3>
        <p class="text-sm text-gray-600 mb-4">
            Jika Anda terus mengalami masalah atau membutuhkan bantuan lebih lanjut, jangan ragu untuk menghubungi tim support kami. Kami siap membantu!
        </p>
        <div class="flex flex-col sm:flex-row gap-2">
            <a href="mailto:support@surveycenter.co.id" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-100 text-blue-700 rounded-lg font-medium text-sm hover:bg-blue-200 transition">
                <i data-lucide="mail" class="w-4 h-4"></i>
                Email Support
            </a>
            <a href="{{ route('contact') }}" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 bg-gray-100 text-gray-900 rounded-lg font-medium text-sm hover:bg-gray-200 transition">
                <i data-lucide="message-square" class="w-4 h-4"></i>
                Hubungi Kami
            </a>
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
