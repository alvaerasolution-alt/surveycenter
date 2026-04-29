@extends('layouts.admin')

@section('title', 'Bayar Test Transaction')
@section('page-title', 'Proses Pembayaran Test')

@section('content')
<div class="max-w-2xl mx-auto">

    <div class="mb-6">
        <a href="{{ route('singapay.test.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-orange-600 transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Kembali ke daftar
        </a>
    </div>

    @if(session('error'))
    <div class="mb-4 flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
        <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    {{-- Transaction Summary --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
        <div class="flex items-start justify-between mb-6">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase mb-2">Jumlah Pembayaran</p>
                <p class="text-4xl font-bold text-gray-900">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</p>
            </div>
            <div class="text-right">
                <p class="text-xs font-medium text-gray-500 uppercase mb-2">Test Transaction</p>
                <p class="text-sm font-semibold text-gray-900">{{ $transaction->bill_description }}</p>
                <p class="text-xs text-gray-500 mt-1">ID: #{{ $transaction->id }}</p>
                <p class="font-mono text-xs text-gray-400 mt-0.5">{{ $transaction->bill_no }}</p>
            </div>
        </div>

        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 flex gap-3">
            <i data-lucide="info" class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5"></i>
            <div class="text-sm text-amber-700">
                <p class="font-medium mb-1">Pembayaran valid selama 30 menit</p>
                <p>Setelah klik tombol, Anda akan diarahkan ke halaman pembayaran SingaPay.</p>
            </div>
        </div>
    </div>

    {{-- Payment Action --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-orange-50 to-amber-50 px-6 py-5 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900">Metode Pembayaran</h3>
            <p class="text-sm text-gray-600 mt-1">Pembayaran diproses melalui SingaPay (QRIS, VA BCA, VA BNI, VA BRI, dll)</p>
        </div>

        <div class="p-6">
            <div class="flex flex-wrap gap-1.5 mb-6">
                @foreach(['QRIS', 'VA BCA', 'VA BNI', 'VA BRI', 'VA Danamon', 'VA Maybank'] as $method)
                <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200">
                    {{ $method }}
                </span>
                @endforeach
            </div>

            <form method="POST" action="{{ route('singapay.test.process', $transaction) }}">
                @csrf
                <div class="flex gap-3">
                    <a href="{{ route('singapay.test.index') }}" class="flex-1 px-4 py-3 bg-gray-100 text-gray-900 rounded-lg font-medium text-sm hover:bg-gray-200 transition text-center">
                        Batal
                    </a>
                    <button type="submit" class="flex-1 px-4 py-3 bg-orange-600 text-white rounded-lg font-medium text-sm hover:bg-orange-700 transition flex items-center justify-center gap-2">
                        <i data-lucide="credit-card" class="w-4 h-4"></i>
                        Bayar via SingaPay
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Info --}}
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4 flex gap-3">
        <i data-lucide="test-tube" class="w-5 h-5 text-blue-600 flex-shrink-0"></i>
        <div class="text-sm text-blue-700">
            <p class="font-medium mb-1">Mode Test</p>
            <p>Transaksi ini menggunakan SingaPay {{ str_contains(config('singapay.base_url', ''), 'sandbox') ? 'Sandbox' : 'Production' }}. Webhook callback akan otomatis mengubah status setelah pembayaran.</p>
        </div>
    </div>
</div>
@endsection
