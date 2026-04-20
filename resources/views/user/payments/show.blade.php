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
            <h3 class="text-lg font-semibold text-gray-900">Pilih Metode Pembayaran</h3>
            <p class="text-sm text-gray-600 mt-1">Kami menyediakan berbagai metode pembayaran aman dan terpercaya</p>
        </div>

        <form action="{{ route('user.payments.process', $transaction) }}" method="POST" class="p-6">
            @csrf

            @php
                $selectedGateway = old('payment_gateway', $defaultGateway ?? 'singapay');
                $gatewayMeta = [
                    'singapay' => [
                        'description' => 'Gateway utama dengan proses cepat untuk QRIS dan VA.',
                        'badgeClass' => 'bg-orange-100 text-orange-700',
                        'badgeText' => 'Rekomendasi',
                    ],
                    'faspay' => [
                        'description' => 'Gateway alternatif untuk QRIS, VA, dan e-wallet.',
                        'badgeClass' => 'bg-blue-100 text-blue-700',
                        'badgeText' => 'Alternatif',
                    ],
                ];
            @endphp

            <div class="mb-6">
                <p class="text-sm font-semibold text-gray-900 mb-3">Pilih Payment Gateway</p>
                <div class="space-y-3">
                    @foreach (($gatewayOptions ?? []) as $gatewayKey => $gateway)
                        @php
                            $isEnabled = (bool) ($gateway['enabled'] ?? false);
                            $isConfigured = (bool) ($gateway['configured'] ?? false);
                            $isAvailable = $isEnabled && $isConfigured;
                            $label = $gateway['label'] ?? strtoupper($gatewayKey);
                            $meta = $gatewayMeta[$gatewayKey] ?? [
                                'description' => 'Gateway pembayaran.',
                                'badgeClass' => 'bg-gray-100 text-gray-700',
                                'badgeText' => 'Gateway',
                            ];
                        @endphp

                        <label class="group relative block {{ $isAvailable ? '' : 'opacity-70' }}">
                            <input
                                type="radio"
                                name="payment_gateway"
                                value="{{ $gatewayKey }}"
                                class="sr-only"
                                {{ $selectedGateway === $gatewayKey ? 'checked' : '' }}
                                {{ $isAvailable ? '' : 'disabled' }}
                            >
                            <div class="relative border-2 border-gray-200 rounded-lg p-4 transition {{ $isAvailable ? 'cursor-pointer group-has-[:checked]:border-orange-600 group-has-[:checked]:bg-orange-50' : 'cursor-not-allowed' }}">
                                <div class="flex items-start gap-4">
                                    <div class="mt-1">
                                        <div class="w-5 h-5 rounded-full border-2 border-gray-300 {{ $isAvailable ? 'group-has-[:checked]:border-orange-600 group-has-[:checked]:bg-orange-600' : 'bg-gray-200 border-gray-300' }} flex items-center justify-center flex-shrink-0">
                                            @if($isAvailable)
                                                <i class="w-3 h-3 text-white hidden group-has-[:checked]:block">
                                                    <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                                </i>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between gap-3">
                                            <h4 class="font-semibold text-gray-900">{{ $label }}</h4>
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ $meta['badgeClass'] }}">{{ $meta['badgeText'] }}</span>
                                        </div>
                                        <p class="text-sm text-gray-600 mt-1">{{ $meta['description'] }}</p>
                                        @if(!$isEnabled)
                                            <p class="text-xs text-red-600 mt-2">Gateway ini sedang dinonaktifkan.</p>
                                        @elseif(!$isConfigured)
                                            <p class="text-xs text-red-600 mt-2">Gateway belum dikonfigurasi, silakan pilih gateway lain.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>

                @error('payment_gateway')
                    <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-3 mb-6">
                {{-- QRIS Payment --}}
                <label class="group relative block">
                    <input type="radio" name="payment_method" value="qris" class="sr-only" checked>
                    <div class="relative border-2 border-gray-200 rounded-lg p-4 cursor-pointer group-has-[:checked]:border-orange-600 group-has-[:checked]:bg-orange-50 transition">
                        <div class="flex items-start gap-4">
                            <div class="mt-1">
                                <div class="w-5 h-5 rounded-full border-2 border-gray-300 group-has-[:checked]:border-orange-600 group-has-[:checked]:bg-orange-600 flex items-center justify-center flex-shrink-0">
                                    <i class="w-3 h-3 text-white hidden group-has-[:checked]:block">
                                        <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    </i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900">QRIS (Quick Response Code)</h4>
                                <p class="text-sm text-gray-600 mt-1">Pindai kode QR dengan aplikasi banking atau e-wallet Anda</p>
                                <div class="flex gap-2 mt-2 flex-wrap">
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-medium">
                                        <i data-lucide="smartphone" class="w-3 h-3"></i>
                                        GoPay
                                    </span>
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-purple-100 text-purple-700 rounded text-xs font-medium">
                                        <i data-lucide="smartphone" class="w-3 h-3"></i>
                                        OVO
                                    </span>
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-indigo-100 text-indigo-700 rounded text-xs font-medium">
                                        <i data-lucide="smartphone" class="w-3 h-3"></i>
                                        Dana
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </label>

                {{-- Virtual Account --}}
                <label class="group relative block">
                    <input type="radio" name="payment_method" value="virtual_account" class="sr-only">
                    <div class="relative border-2 border-gray-200 rounded-lg p-4 cursor-pointer group-has-[:checked]:border-orange-600 group-has-[:checked]:bg-orange-50 transition">
                        <div class="flex items-start gap-4">
                            <div class="mt-1">
                                <div class="w-5 h-5 rounded-full border-2 border-gray-300 group-has-[:checked]:border-orange-600 group-has-[:checked]:bg-orange-600 flex items-center justify-center flex-shrink-0">
                                    <i class="w-3 h-3 text-white hidden group-has-[:checked]:block">
                                        <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    </i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900">Rekening Virtual (VA)</h4>
                                <p class="text-sm text-gray-600 mt-1">Transfer langsung dari rekening bank Anda</p>
                                <div class="flex gap-2 mt-2 flex-wrap">
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-medium">
                                        <i data-lucide="bank" class="w-3 h-3"></i>
                                        BCA
                                    </span>
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs font-medium">
                                        <i data-lucide="bank" class="w-3 h-3"></i>
                                        BNI
                                    </span>
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-medium">
                                        <i data-lucide="bank" class="w-3 h-3"></i>
                                        BRI
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </label>

                {{-- E-Wallet --}}
                <label class="group relative block">
                    <input type="radio" name="payment_method" value="e_wallet" class="sr-only">
                    <div class="relative border-2 border-gray-200 rounded-lg p-4 cursor-pointer group-has-[:checked]:border-orange-600 group-has-[:checked]:bg-orange-50 transition">
                        <div class="flex items-start gap-4">
                            <div class="mt-1">
                                <div class="w-5 h-5 rounded-full border-2 border-gray-300 group-has-[:checked]:border-orange-600 group-has-[:checked]:bg-orange-600 flex items-center justify-center flex-shrink-0">
                                    <i class="w-3 h-3 text-white hidden group-has-[:checked]:block">
                                        <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    </i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900">E-Wallet</h4>
                                <p class="text-sm text-gray-600 mt-1">Gunakan saldo e-wallet Anda untuk pembayaran instan</p>
                                <div class="flex gap-2 mt-2 flex-wrap">
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-medium">
                                        <i data-lucide="wallet" class="w-3 h-3"></i>
                                        GoPay
                                    </span>
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-purple-100 text-purple-700 rounded text-xs font-medium">
                                        <i data-lucide="wallet" class="w-3 h-3"></i>
                                        OVO
                                    </span>
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-medium">
                                        <i data-lucide="wallet" class="w-3 h-3"></i>
                                        Dana
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </label>
            </div>

            {{-- Submit Button --}}
            <div class="flex gap-3 pt-6 border-t border-gray-100">
                <a href="{{ route('user.transactions.show', $transaction) }}" class="flex-1 px-4 py-3 bg-gray-100 text-gray-900 rounded-lg font-medium text-sm hover:bg-gray-200 transition text-center">
                    Batal
                </a>
                <button type="submit" class="flex-1 px-4 py-3 bg-orange-600 text-white rounded-lg font-medium text-sm hover:bg-orange-700 transition flex items-center justify-center gap-2">
                    <i data-lucide="credit-card" class="w-4 h-4"></i>
                    Lanjutkan ke Pembayaran
                </button>
            </div>
        </form>
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
