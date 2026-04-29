@extends('layouts.admin')

@section('title', 'Pembayaran Berhasil')
@section('page-title', 'Pembayaran Berhasil')

@section('content')
<div class="max-w-lg mx-auto text-center">

    <div class="bg-white rounded-xl border border-gray-200 p-8">
        <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i data-lucide="check-circle" class="w-8 h-8 text-emerald-600"></i>
        </div>

        <h2 class="text-xl font-bold text-gray-900 mb-2">Pembayaran Berhasil!</h2>
        <p class="text-sm text-gray-600 mb-6">Test transaction telah dibayar dan webhook callback berhasil diproses.</p>

        <div class="bg-gray-50 rounded-lg p-4 text-left space-y-2 mb-6">
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Bill No</span>
                <span class="font-mono text-gray-700">{{ $transaction->bill_no }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Amount</span>
                <span class="font-semibold text-gray-900">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Status</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-700">
                    {{ ucfirst($transaction->status) }}
                </span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">SingaPay Ref</span>
                <span class="font-mono text-xs text-gray-600">{{ $transaction->singapay_ref ?? '-' }}</span>
            </div>
            @if($transaction->paid_at)
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Paid At</span>
                <span class="text-gray-700">{{ $transaction->paid_at->format('d M Y H:i:s') }}</span>
            </div>
            @endif
        </div>

        <div class="flex gap-3">
            <a href="{{ route('singapay.test.show', $transaction) }}"
                class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
                Lihat Detail
            </a>
            <a href="{{ route('singapay.test.index') }}"
                class="flex-1 px-4 py-2.5 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition">
                Kembali ke Daftar
            </a>
        </div>
    </div>
</div>
@endsection
