@extends('layouts.user')

@section('title', 'Pembayaran Berhasil')
@section('page-title', 'Pembayaran Berhasil')
@section('page-description', 'Transaksi Anda telah berhasil diproses')

@section('content')
<div class="max-w-2xl mx-auto">
    {{-- Success Card --}}
    <div class="bg-white rounded-xl border border-gray-200/80 overflow-hidden">
        <div class="bg-gradient-to-r from-emerald-50 to-teal-50 px-6 py-12 text-center">
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 rounded-full bg-emerald-100 flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-8 h-8 text-emerald-600"></i>
                </div>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Pembayaran Berhasil!</h2>
            <p class="text-gray-600">Transaksi Anda telah dikonfirmasi dan kami segera memproses survey Anda</p>
        </div>

        <div class="p-6 space-y-6">
            {{-- Transaction Details --}}
            <div class="border-b border-gray-100 pb-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i data-lucide="receipt" class="w-4 h-4"></i>
                    Rincian Transaksi
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase mb-1">No. Referensi</p>
                        <p class="font-mono text-sm font-semibold text-gray-900">{{ $transaction->singapay_ref ?? '#' . $transaction->id }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase mb-1">Tanggal & Waktu</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $transaction->created_at->format('d F Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase mb-1">Jumlah Pembayaran</p>
                        <p class="text-sm font-semibold text-gray-900">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase mb-1">Metode Pembayaran</p>
                        <p class="text-sm font-semibold text-gray-900">
                            @php
                                $methods = [
                                    'qris' => 'QRIS',
                                    'virtual_account' => 'Rekening Virtual',
                                    'e_wallet' => 'E-Wallet'
                                ];
                            @endphp
                            {{ $methods[$transaction->payment_method] ?? ucfirst($transaction->payment_method) }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Survey Info --}}
            <div class="border-b border-gray-100 pb-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i data-lucide="file-text" class="w-4 h-4"></i>
                    Informasi Survey
                </h3>
                <div class="flex items-start gap-4 p-4 bg-orange-50 rounded-lg">
                    <div class="w-12 h-12 rounded-lg bg-orange-100 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="file-text" class="w-6 h-6 text-orange-600"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900">{{ $transaction->survey->title }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $transaction->survey->question_count }} Pertanyaan</p>
                        <p class="text-xs text-gray-500">{{ $transaction->survey->description }}</p>
                    </div>
                </div>
            </div>

            {{-- Next Steps --}}
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-blue-900 mb-3 flex items-center gap-2">
                    <i data-lucide="info" class="w-4 h-4"></i>
                    Langkah Selanjutnya
                </h4>
                <ul class="space-y-2 text-sm text-blue-700">
                    <li class="flex gap-2">
                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-blue-200 text-blue-900 text-xs font-bold flex-shrink-0">1</span>
                        <span>Kami akan segera memproses survey Anda</span>
                    </li>
                    <li class="flex gap-2">
                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-blue-200 text-blue-900 text-xs font-bold flex-shrink-0">2</span>
                        <span>Anda akan menerima email konfirmasi dalam beberapa menit</span>
                    </li>
                    <li class="flex gap-2">
                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-blue-200 text-blue-900 text-xs font-bold flex-shrink-0">3</span>
                        <span>Pantau progres survey Anda di halaman "Survey Saya"</span>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="px-6 py-6 bg-gray-50 border-t border-gray-100 flex gap-3">
            <a href="{{ route('user.surveys.show', $transaction->survey) }}" class="flex-1 px-4 py-3 bg-orange-600 text-white rounded-lg font-medium text-sm hover:bg-orange-700 transition text-center flex items-center justify-center gap-2">
                <i data-lucide="eye" class="w-4 h-4"></i>
                Lihat Survey
            </a>
            <a href="{{ route('user.dashboard') }}" class="flex-1 px-4 py-3 bg-gray-200 text-gray-900 rounded-lg font-medium text-sm hover:bg-gray-300 transition text-center flex items-center justify-center gap-2">
                <i data-lucide="home" class="w-4 h-4"></i>
                Ke Dashboard
            </a>
        </div>
    </div>

    {{-- Help Card --}}
    <div class="mt-6 bg-white rounded-xl border border-gray-200/80 p-6">
        <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
            <i data-lucide="help-circle" class="w-4 h-4"></i>
            Perlu Bantuan?
        </h3>
        <p class="text-sm text-gray-600 mb-3">
            Jika Anda memiliki pertanyaan atau mengalami masalah, hubungi tim support kami.
        </p>
        <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-900 rounded-lg font-medium text-sm hover:bg-gray-200 transition">
            <i data-lucide="mail" class="w-4 h-4"></i>
            Hubungi Support
        </a>
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
