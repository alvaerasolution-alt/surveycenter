@extends('layouts.crm')

@section('title', 'Progress Transaksi Survey')

@section('content')
<div class="bg-white shadow-xl rounded-2xl p-6 font-sans">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between md:items-center gap-4 mb-6">
        <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            <i class="fas fa-chart-line text-orange-500"></i>
            Daftar Transaksi Paid
        </h2>

        {{-- Flash messages --}}
        @if (session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded-xl border border-green-300 shadow-sm text-sm font-medium animate-fade-in">
                ✅ {{ session('success') }}
            </div>
        @elseif (session('error'))
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded-xl border border-red-300 shadow-sm text-sm font-medium animate-fade-in">
                ⚠️ {{ session('error') }}
            </div>
        @endif
    </div>

    {{-- Data Table --}}
    @if ($transactions && $transactions->count() > 0)
        <div class="overflow-x-auto border border-gray-200 rounded-2xl shadow-sm">
            <table class="w-full text-sm text-left text-gray-700">
                <thead class="bg-gray-100 uppercase text-gray-600 text-xs font-semibold tracking-wider">
                    <tr>
                        <th class="px-4 py-3">ID</th>
                        <th class="px-4 py-3">Survey</th>
                        <th class="px-4 py-3">User</th>
                        <th class="px-4 py-3 text-right">Jumlah</th>
                        <th class="px-4 py-3 w-48">Progress</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $transaction)
                        <tr class="border-t hover:bg-gray-50 transition duration-150 ease-in-out">
                            <td class="px-4 py-3 font-semibold text-gray-900">#{{ $transaction->id }}</td>
                            <td class="px-4 py-3 truncate max-w-xs" title="{{ $transaction->survey->title ?? '-' }}">
                                {{ $transaction->survey->title ?? '-' }}
                            </td>
                            <td class="px-4 py-3">{{ $transaction->user->name ?? 'Guest' }}</td>
                            <td class="px-4 py-3 text-right font-medium text-gray-800">
                                Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="w-full bg-gray-200 rounded-full h-6 overflow-hidden shadow-inner relative">
                                    <div class="bg-green-500 h-6 rounded-full text-white text-xs text-center font-semibold transition-all duration-500 ease-out flex items-center justify-center"
                                        style="width: {{ min($transaction->progress ?? 0, 100) }}%;">
                                        {{ $transaction->progress ?? 0 }}%
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('admin.transactions.progress.edit', $transaction) }}"
                                    class="inline-flex items-center gap-1 px-4 py-2 bg-orange-500 text-white text-xs font-semibold rounded-lg hover:bg-orange-600 transition duration-300 shadow-md">
                                    <i class="fas fa-edit"></i> Update
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $transactions->links('pagination::tailwind') }}
        </div>
    @else
        {{-- Empty State --}}
        <div class="text-center py-12 text-gray-500">
            <i class="fas fa-inbox text-4xl mb-2 text-gray-400"></i>
            <p class="text-lg font-medium">Belum ada transaksi yang dibayar.</p>
        </div>
    @endif
</div>

{{-- Fade Animation --}}
<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(-6px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in { animation: fade-in 0.3s ease-out; }
</style>
@endsection
