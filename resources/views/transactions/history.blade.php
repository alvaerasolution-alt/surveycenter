@extends('layouts.app')

@section('content')
<div class="min-h-[calc(100vh-220px)] bg-gradient-to-b from-orange-500 to-orange-700 py-10">
    <div class="max-w-5xl mx-auto px-4">
        <h1 class="text-2xl font-semibold text-yellow-50 mb-6">Riwayat Transaksi</h1>

        <div class="bg-yellow-50/95 border border-yellow-300 rounded-xl shadow-lg overflow-hidden">
            <table class="min-w-full text-sm">
                <thead class="bg-yellow-100 border-b border-yellow-200">
                    <tr class="text-left text-gray-800">
                        <th class="px-5 py-3 font-semibold">Tanggal</th>
                        <th class="px-5 py-3 font-semibold">Survey</th>
                        <th class="px-5 py-3 font-semibold">Jumlah</th>
                        <th class="px-5 py-3 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $transaction)
                        <tr class="border-b border-yellow-100 last:border-b-0 even:bg-yellow-50 hover:bg-yellow-100/80 transition">
                            <td class="px-5 py-3 text-gray-800">
                                {{ $transaction->created_at->format('d M Y') }}
                            </td>
                            <td class="px-5 py-3 text-gray-800">
                                {{ $transaction->survey->title ?? '-' }}
                            </td>
                            <td class="px-5 py-3 text-gray-900 font-medium">
                                Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-3">
                                @php
                                    $isSuccess = $transaction->status === 'success';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                    {{ $isSuccess ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-6 text-center text-gray-500">
                                Belum ada transaksi
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($transactions->hasPages())
            <div class="mt-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <div class="text-xs text-yellow-50/90">
                    Menampilkan
                    <span class="font-semibold">
                        {{ $transactions->firstItem() }}–{{ $transactions->lastItem() }}
                    </span>
                    dari
                    <span class="font-semibold">
                        {{ $transactions->total() }}
                    </span>
                    transaksi
                </div>

                <div class="flex items-center justify-end gap-1 text-sm">
                    @if ($transactions->onFirstPage())
                        <span class="px-3 py-1.5 rounded-lg border border-yellow-200/60 bg-yellow-50/40 text-gray-400 cursor-not-allowed">
                            Sebelumnya
                        </span>
                    @else
                        <a href="{{ $transactions->previousPageUrl() }}"
                           class="px-3 py-1.5 rounded-lg border border-yellow-300 bg-yellow-50/90 text-orange-800 hover:bg-orange-400 hover:text-white transition">
                            Sebelumnya
                        </a>
                    @endif

                    @foreach ($transactions->getUrlRange(1, $transactions->lastPage()) as $page => $url)
                        @if ($page == $transactions->currentPage())
                            <span class="px-3 py-1.5 rounded-lg bg-orange-500 text-white border border-orange-500">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                               class="px-3 py-1.5 rounded-lg border border-yellow-300 bg-yellow-50/90 text-orange-800 hover:bg-orange-400 hover:text-white transition">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach

                    @if ($transactions->hasMorePages())
                        <a href="{{ $transactions->nextPageUrl() }}"
                           class="px-3 py-1.5 rounded-lg border border-yellow-300 bg-yellow-50/90 text-orange-800 hover:bg-orange-400 hover:text-white transition">
                            Selanjutnya
                        </a>
                    @else
                        <span class="px-3 py-1.5 rounded-lg border border-yellow-200/60 bg-yellow-50/40 text-gray-400 cursor-not-allowed">
                            Selanjutnya
                        </span>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
