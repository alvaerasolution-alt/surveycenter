@extends('layouts.admin')

@section('title', 'Transactions')
@section('page-title', 'History Transaksi')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Semua Transaksi</h2>
                <p class="text-sm text-gray-500 mt-1">Daftar seluruh riwayat transaksi</p>
            </div>
            <a href="{{ route('admin.transactions.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition shadow-sm">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Tambah Transaksi
            </a>
        </div>

        {{-- Table Card --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Survey</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($transactions as $transaction)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3.5 text-gray-500 font-mono text-xs">#{{ $transaction->id }}</td>
                                <td class="px-4 py-3.5 font-medium text-gray-900">{{ $transaction->survey->title ?? '-' }}</td>
                                <td class="px-4 py-3.5 text-gray-600">{{ $transaction->user->name ?? '-' }}</td>
                                <td class="px-4 py-3.5 font-semibold text-gray-900">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                                <td class="px-4 py-3.5">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'paid' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'completed' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'cancelled' => 'bg-red-50 text-red-700 border-red-200',
                                        ];
                                        $color = $statusColors[strtolower($transaction->status)] ?? 'bg-gray-50 text-gray-700 border-gray-200';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $color }}">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('admin.transactions.show', $transaction) }}" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-500 hover:text-gray-700 transition" title="View">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </a>
                                        <a href="{{ route('admin.transactions.edit', $transaction) }}" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-500 hover:text-blue-600 transition" title="Edit">
                                            <i data-lucide="pencil" class="w-4 h-4"></i>
                                        </a>
                                        <a href="{{ route('admin.transactions.progress.edit', $transaction) }}" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-500 hover:text-emerald-600 transition" title="Progress">
                                            <i data-lucide="trending-up" class="w-4 h-4"></i>
                                        </a>
                                        <form action="{{ route('admin.transactions.destroy', $transaction) }}" method="POST"
                                            onsubmit="return confirm('Hapus transaksi ini?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg hover:bg-red-50 text-gray-500 hover:text-red-600 transition" title="Delete">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($transactions->isEmpty())
                <div class="text-center py-12">
                    <i data-lucide="inbox" class="w-10 h-10 text-gray-300 mx-auto mb-3"></i>
                    <p class="text-sm text-gray-500">Belum ada transaksi</p>
                </div>
            @endif
        </div>

        {{-- Pagination --}}
        <div class="mt-2">
            {{ $transactions->links() }}
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();
    });
</script>
@endpush
