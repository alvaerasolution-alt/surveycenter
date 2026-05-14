@extends('layouts.user')

@section('title', 'Riwayat Top Up')
@section('page-title', 'Riwayat Top Up Saldo')
@section('page-description', 'Kelola saldo akun dan lihat riwayat top up Anda')

@section('content')
<div class="mb-6 flex justify-between items-end">
    <div>
        <p class="text-sm font-medium text-gray-500 mb-1">Saldo Anda Saat Ini</p>
        <p class="text-3xl font-bold text-emerald-600">
            Rp {{ number_format(Auth::user()->deposit_balance, 0, ',', '.') }}
        </p>
    </div>
    <a href="{{ route('user.topups.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-orange-600 text-white rounded-lg font-medium text-sm hover:bg-orange-700 transition">
        <i data-lucide="plus-circle" class="w-4 h-4"></i>
        Top Up Baru
    </a>
</div>

<div class="bg-white rounded-xl border border-gray-200/80 overflow-hidden">
    @if($transactions->isEmpty())
        <div class="p-12 text-center">
            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="wallet" class="w-8 h-8 text-gray-400"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-1">Belum ada riwayat top up</h3>
            <p class="text-gray-500 mb-6">Mulai isi saldo Anda untuk kemudahan membayar survey.</p>
            <a href="{{ route('user.topups.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition">
                Top Up Sekarang
            </a>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-gray-50/50 border-b border-gray-200/80 text-gray-500">
                    <tr>
                        <th class="px-6 py-4 font-medium">Tanggal</th>
                        <th class="px-6 py-4 font-medium">Nominal</th>
                        <th class="px-6 py-4 font-medium">Metode Pembayaran</th>
                        <th class="px-6 py-4 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($transactions as $trx)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 text-gray-500">
                                {{ $trx->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">
                                Rp {{ number_format($trx->amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 uppercase">
                                {{ $trx->payment_method ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $trx->statusBadgeClass() }}">
                                    {{ $trx->statusLabel() }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($transactions->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $transactions->links() }}
            </div>
        @endif
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();
    });
</script>
@endpush
@endsection
