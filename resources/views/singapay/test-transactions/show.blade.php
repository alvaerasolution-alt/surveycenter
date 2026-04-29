@extends('layouts.admin')

@section('title', 'Detail Test Transaction')
@section('page-title', 'Detail Test Transaction')

@section('content')
<div class="max-w-3xl">

    <div class="mb-4">
        <a href="{{ route('singapay.test.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke daftar
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Test Transaction #{{ $transaction->id }}</h2>
                <p class="font-mono text-sm text-gray-500 mt-0.5">{{ $transaction->bill_no }}</p>
            </div>
            @php
                $badgeClass = match($transaction->status) {
                    'paid' => 'bg-emerald-100 text-emerald-700',
                    'processing' => 'bg-blue-100 text-blue-700',
                    'failed' => 'bg-red-100 text-red-700',
                    'expired' => 'bg-gray-100 text-gray-500',
                    default => 'bg-amber-100 text-amber-700',
                };
            @endphp
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
                {{ ucfirst($transaction->status) }}
            </span>
        </div>

        <div class="p-6 space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-xs text-gray-500 mb-1">Amount</p>
                    <p class="text-xl font-bold text-gray-900">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-xs text-gray-500 mb-1">SingaPay Ref</p>
                    <p class="font-mono text-sm text-gray-700">{{ $transaction->singapay_ref ?? '-' }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <p class="text-xs text-gray-500 mb-1">Customer</p>
                    <p class="text-sm font-medium text-gray-800">{{ $transaction->customer_name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Email</p>
                    <p class="text-sm text-gray-700">{{ $transaction->customer_email }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Phone</p>
                    <p class="text-sm text-gray-700">{{ $transaction->customer_phone }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <p class="text-xs text-gray-500 mb-1">Created</p>
                    <p class="text-sm text-gray-700">{{ $transaction->created_at->format('d M Y H:i:s') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Expires</p>
                    <p class="text-sm text-gray-700">{{ $transaction->expires_at?->format('d M Y H:i:s') ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Paid At</p>
                    <p class="text-sm text-gray-700">{{ $transaction->paid_at?->format('d M Y H:i:s') ?? '-' }}</p>
                </div>
            </div>

            @if($transaction->payment_url)
            <div>
                <p class="text-xs text-gray-500 mb-1">Payment URL</p>
                <a href="{{ $transaction->payment_url }}" target="_blank" class="text-sm text-orange-600 hover:underline break-all">{{ $transaction->payment_url }}</a>
            </div>
            @endif

            @if($transaction->notes)
            <div>
                <p class="text-xs text-gray-500 mb-1">Notes</p>
                <p class="text-sm text-gray-700">{{ $transaction->notes }}</p>
            </div>
            @endif

            @if($transaction->webhook_payload)
            <div>
                <p class="text-xs text-gray-500 mb-1">Webhook Payload</p>
                <pre class="bg-gray-900 text-green-400 text-xs rounded-lg p-4 overflow-x-auto max-h-64">{{ json_encode($transaction->webhook_payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
            </div>
            @endif
        </div>

        <div class="px-6 py-4 border-t border-gray-200 flex items-center gap-3">
            @if(!$transaction->isPaid() && !$transaction->isExpired())
            <a href="{{ route('singapay.test.payment', $transaction) }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition">
                <i data-lucide="credit-card" class="w-4 h-4"></i> Bayar
            </a>
            @endif

            @if($transaction->isPaid())
            <span class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-100 text-emerald-700 text-sm font-medium rounded-lg">
                <i data-lucide="check" class="w-4 h-4"></i> Sudah Dibayar
            </span>
            @endif

            {{-- Auto-refresh status for processing --}}
            @if($transaction->status === 'processing')
            <span id="statusPolling" class="text-xs text-blue-600 flex items-center gap-1">
                <i data-lucide="loader" class="w-3 h-3 animate-spin"></i> Menunggu callback...
            </span>
            @endif
        </div>
    </div>
</div>

@if($transaction->status === 'processing')
@push('scripts')
<script>
    // Poll status every 5 seconds
    const checkInterval = setInterval(async () => {
        try {
            const res = await fetch('{{ route("singapay.test.check-status", $transaction) }}');
            const data = await res.json();
            if (data.is_paid) {
                clearInterval(checkInterval);
                window.location.href = '{{ route("singapay.test.success", $transaction) }}';
            } else if (data.status === 'failed' || data.status === 'expired') {
                clearInterval(checkInterval);
                window.location.reload();
            }
        } catch (e) {}
    }, 5000);
</script>
@endpush
@endif
@endsection
