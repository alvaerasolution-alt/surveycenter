@extends('layouts.admin')

@section('title', 'SingaPay Test Transactions')
@section('page-title', 'SingaPay Test Transactions')

@section('content')
<div class="max-w-6xl">

    @if(session('success'))
    <div class="mb-4 flex items-start gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm">
        <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div class="mb-4 flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
        <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="clock" class="w-5 h-5 text-amber-600"></i>
                </div>
                <div>
                    <p class="text-xs text-amber-600 font-medium">Aktif</p>
                    <p class="text-2xl font-bold text-amber-900">{{ $activeCount }}</p>
                </div>
            </div>
        </div>
        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>
                </div>
                <div>
                    <p class="text-xs text-emerald-600 font-medium">Paid</p>
                    <p class="text-2xl font-bold text-emerald-900">{{ $paidCount }}</p>
                </div>
            </div>
        </div>
        <div class="bg-orange-50 border border-orange-200 rounded-xl p-5 flex items-center justify-center">
            <a href="{{ route('singapay.test.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition shadow-sm">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Buat Test Transaction
            </a>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Riwayat Test Transaction</h2>
            <p class="text-sm text-gray-500 mt-1">Test pembayaran melalui SingaPay — alur sama dengan payment asli</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">Bill No</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">Customer</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">Ref</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">Tanggal</th>
                        <th class="px-4 py-3 text-right text-xs font-bold text-gray-600 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($transactions as $trx)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 font-mono text-xs text-gray-700">{{ $trx->bill_no }}</td>
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-800">{{ $trx->customer_name }}</p>
                            <p class="text-[11px] text-gray-400">{{ $trx->customer_email }}</p>
                        </td>
                        <td class="px-4 py-3 font-semibold text-gray-900">Rp {{ number_format($trx->amount, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">
                            @php
                                $badgeClass = match($trx->status) {
                                    'paid' => 'bg-emerald-100 text-emerald-700',
                                    'processing' => 'bg-blue-100 text-blue-700',
                                    'failed' => 'bg-red-100 text-red-700',
                                    'expired' => 'bg-gray-100 text-gray-500',
                                    default => 'bg-amber-100 text-amber-700',
                                };
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold {{ $badgeClass }}">
                                {{ ucfirst($trx->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 font-mono text-[11px] text-gray-500">{{ $trx->singapay_ref ?? '-' }}</td>
                        <td class="px-4 py-3 text-xs text-gray-500">{{ $trx->created_at->format('d M Y H:i') }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('singapay.test.show', $trx) }}"
                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-gray-100 text-gray-700 text-xs font-medium rounded-lg hover:bg-gray-200 transition">
                                    <i data-lucide="eye" class="w-3 h-3"></i> Detail
                                </a>
                                @if(!$trx->isPaid() && !$trx->isExpired())
                                <a href="{{ route('singapay.test.payment', $trx) }}"
                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-orange-100 text-orange-700 text-xs font-medium rounded-lg hover:bg-orange-200 transition">
                                    <i data-lucide="credit-card" class="w-3 h-3"></i> Bayar
                                </a>
                                @endif
                                <form method="POST" action="{{ route('singapay.test.destroy', $trx) }}" onsubmit="return confirm('Hapus test transaction ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-red-100 text-red-700 text-xs font-medium rounded-lg hover:bg-red-200 transition">
                                        <i data-lucide="trash-2" class="w-3 h-3"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center">
                            <i data-lucide="inbox" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                            <p class="text-sm text-gray-400">Belum ada test transaction.</p>
                            <a href="{{ route('singapay.test.create') }}" class="inline-flex items-center gap-1 text-sm text-orange-600 font-medium mt-2 hover:underline">
                                <i data-lucide="plus" class="w-4 h-4"></i> Buat test transaction pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
