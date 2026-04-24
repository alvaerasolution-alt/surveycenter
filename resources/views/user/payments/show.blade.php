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

                $methodLogos = [
                    'QRIS' => '<svg viewBox="0 0 24 24" class="w-4 h-4 flex-shrink-0" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="3" y="3" width="8" height="8" rx="1" stroke="currentColor" stroke-width="1.5"/><rect x="5" y="5" width="4" height="4" fill="currentColor"/><rect x="13" y="3" width="8" height="8" rx="1" stroke="currentColor" stroke-width="1.5"/><rect x="15" y="5" width="4" height="4" fill="currentColor"/><rect x="3" y="13" width="8" height="8" rx="1" stroke="currentColor" stroke-width="1.5"/><rect x="5" y="15" width="4" height="4" fill="currentColor"/><path d="M13 13h2v2h-2zM17 13h2v2h-2zM13 17h2v2h-2zM17 17h4v4h-4z" fill="currentColor"/></svg>',
                    'VA BCA' => '<svg viewBox="0 0 40 16" class="h-4 w-auto flex-shrink-0" xmlns="http://www.w3.org/2000/svg"><text x="0" y="13" font-size="11" font-weight="700" font-family="Arial,sans-serif" fill="#003478">BCA</text></svg>',
                    'VA BNI' => '<svg viewBox="0 0 40 16" class="h-4 w-auto flex-shrink-0" xmlns="http://www.w3.org/2000/svg"><text x="0" y="13" font-size="11" font-weight="700" font-family="Arial,sans-serif" fill="#f58220">BNI</text></svg>',
                    'VA BRI' => '<svg viewBox="0 0 40 16" class="h-4 w-auto flex-shrink-0" xmlns="http://www.w3.org/2000/svg"><text x="0" y="13" font-size="11" font-weight="700" font-family="Arial,sans-serif" fill="#00529b">BRI</text></svg>',
                    'VA Danamon' => '<svg viewBox="0 0 56 16" class="h-4 w-auto flex-shrink-0" xmlns="http://www.w3.org/2000/svg"><text x="0" y="13" font-size="10" font-weight="700" font-family="Arial,sans-serif" fill="#e4002b">Danamon</text></svg>',
                    'VA Maybank' => '<svg viewBox="0 0 52 16" class="h-4 w-auto flex-shrink-0" xmlns="http://www.w3.org/2000/svg"><text x="0" y="13" font-size="10" font-weight="700" font-family="Arial,sans-serif" fill="#ffde00" stroke="#b8970a" stroke-width="0.3">Maybank</text></svg>',
                    'GoPay' => '<svg viewBox="0 0 48 16" class="h-4 w-auto flex-shrink-0" xmlns="http://www.w3.org/2000/svg"><text x="0" y="13" font-size="11" font-weight="700" font-family="Arial,sans-serif" fill="#00aed6">GoPay</text></svg>',
                    'OVO' => '<svg viewBox="0 0 32 16" class="h-4 w-auto flex-shrink-0" xmlns="http://www.w3.org/2000/svg"><text x="0" y="13" font-size="11" font-weight="700" font-family="Arial,sans-serif" fill="#4c3494">OVO</text></svg>',
                    'Dana' => '<svg viewBox="0 0 36 16" class="h-4 w-auto flex-shrink-0" xmlns="http://www.w3.org/2000/svg"><text x="0" y="13" font-size="11" font-weight="700" font-family="Arial,sans-serif" fill="#1181d1">DANA</text></svg>',
                ];

                $gatewayMeta = [
                    'singapay' => [
                        'description' => 'Gateway utama dengan proses cepat, terverifikasi, dan aman.',
                        'badgeClass' => 'bg-orange-100 text-orange-700',
                        'badgeText' => 'Utama',
                        'verified' => true,
                        'methods' => [
                            ['label' => 'QRIS',      'color' => 'bg-orange-50 text-orange-700 border border-orange-200'],
                            ['label' => 'VA BCA',    'color' => 'bg-blue-50 text-blue-900 border border-blue-200'],
                            ['label' => 'VA BNI',    'color' => 'bg-orange-50 text-orange-600 border border-orange-200'],
                            ['label' => 'VA BRI',    'color' => 'bg-blue-50 text-blue-700 border border-blue-200'],
                            ['label' => 'VA Danamon','color' => 'bg-red-50 text-red-700 border border-red-200'],
                            ['label' => 'VA Maybank','color' => 'bg-yellow-50 text-yellow-700 border border-yellow-300'],
                        ],
                    ],
                    'faspay' => [
                        'description' => 'Gateway alternatif untuk QRIS, VA, dan e-wallet.',
                        'badgeClass' => 'bg-blue-100 text-blue-700',
                        'badgeText' => 'Alternatif',
                        'verified' => false,
                        'methods' => [
                            ['label' => 'QRIS',   'color' => 'bg-orange-50 text-orange-700 border border-orange-200'],
                            ['label' => 'VA BCA', 'color' => 'bg-blue-50 text-blue-900 border border-blue-200'],
                            ['label' => 'VA BNI', 'color' => 'bg-orange-50 text-orange-600 border border-orange-200'],
                            ['label' => 'VA BRI', 'color' => 'bg-blue-50 text-blue-700 border border-blue-200'],
                            ['label' => 'GoPay',  'color' => 'bg-sky-50 text-sky-600 border border-sky-200'],
                            ['label' => 'OVO',    'color' => 'bg-purple-50 text-purple-700 border border-purple-200'],
                            ['label' => 'Dana',   'color' => 'bg-blue-50 text-blue-600 border border-blue-200'],
                        ],
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
                                'verified' => false,
                                'methods' => [],
                            ];
                            $isVerified = $meta['verified'] ?? false;
                            $methods = $meta['methods'] ?? [];
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
                                        <div class="flex items-center justify-between gap-3 flex-wrap">
                                            <div class="flex items-center gap-2">
                                                <h4 class="font-semibold text-gray-900">{{ $label }}</h4>
                                                @if($isVerified)
                                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 bg-green-100 text-green-700 rounded text-xs font-medium" title="Gateway terverifikasi">
                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                                        Terverifikasi
                                                    </span>
                                                @endif
                                            </div>
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ $meta['badgeClass'] }}">{{ $meta['badgeText'] }}</span>
                                        </div>
                                        <p class="text-sm text-gray-600 mt-1">{{ $meta['description'] }}</p>
                                        @if(!empty($methods))
                                            <div class="flex flex-wrap gap-1.5 mt-2">
                                                @foreach($methods as $method)
                                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-medium {{ $method['color'] }}">
                                                        {!! $methodLogos[$method['label']] ?? '' !!}
                                                        {{ $method['label'] }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
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

            <input type="hidden" name="payment_method" value="qris">

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
