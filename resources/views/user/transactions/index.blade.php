@extends('layouts.user')

@section('title', 'Riwayat Transaksi')
@section('page-title', 'Riwayat Transaksi')
@section('page-description', 'Kelola dan pantau semua transaksi survey Anda')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">Daftar Transaksi</h2>
            <p class="text-sm text-gray-500">Total {{ $transactions->total() }} transaksi</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl border border-gray-200/80 p-4">
        <form method="GET" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Status Filter --}}
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none">
                        <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                        <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Verifikasi</option>
                        <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Dibayar</option>
                        <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Gagal</option>
                    </select>
                </div>

                {{-- Sort --}}
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-2">Urutan</label>
                    <select name="sort" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none">
                        <option value="latest" {{ request('sort') === 'latest' || !request('sort') ? 'selected' : '' }}>Terbaru</option>
                        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Terlama</option>
                    </select>
                </div>

                {{-- From Date --}}
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-2">Dari Tanggal</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}" 
                        class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none">
                </div>

                {{-- To Date --}}
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}" 
                        class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none">
                </div>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-lg text-sm font-medium hover:bg-orange-700 transition">
                    Filter
                </button>
                <a href="{{ route('user.transactions.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Transactions Table --}}
    <div class="bg-white rounded-xl border border-gray-200/80 overflow-hidden">
        @if($transactions->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Survey</th>
                            <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Biaya</th>
                            <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Progress</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($transactions as $transaction)
                            @php
                                $progress = $transaction->safeProgress();
                                $tahap1Selesai = $transaction->isStage1Completed();
                                $tahap2Selesai = $transaction->isStage2Completed();
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                {{-- Survey Name --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-orange-100 to-amber-100 flex items-center justify-center flex-shrink-0">
                                            <i data-lucide="file-text" class="w-5 h-5 text-orange-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $transaction->survey?->title ?? 'Survey Tidak Ditemukan' }}
                                            </p>
                                            <p class="text-xs text-gray-500">ID: #{{ $transaction->id }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Amount --}}
                                <td class="px-5 py-4 text-center">
                                    <p class="text-sm font-semibold text-gray-900">
                                        Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                    </p>
                                </td>

                                {{-- Status --}}
                                <td class="px-5 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $transaction->statusBadgeClass() }}">
                                        {{ $transaction->statusLabel() }}
                                    </span>
                                </td>

                                {{-- Progress --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <div class="w-20 h-2 bg-gray-200 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full transition-all
                                                @if($progress >= 100) bg-emerald-500
                                                @elseif($progress > 0) bg-blue-500
                                                @else bg-gray-300
                                                @endif"
                                                data-progress-width="{{ $progress }}"></div>
                                        </div>
                                        <span class="text-xs font-medium text-gray-600 w-10 text-right">{{ $progress }}%</span>
                                    </div>
                                    <div class="mt-1.5 text-center">
                                        <p class="text-[11px] {{ $tahap1Selesai ? 'text-emerald-600' : 'text-amber-600' }}">Tahap 1: {{ $tahap1Selesai ? 'Selesai' : 'Menunggu Pembayaran' }}</p>
                                        <p class="text-[11px] {{ $tahap2Selesai ? 'text-emerald-600' : 'text-gray-500' }}">Tahap 2: {{ $tahap2Selesai ? 'Selesai' : 'Proses' }}</p>
                                    </div>
                                </td>

                                {{-- Date --}}
                                <td class="px-5 py-4">
                                    <div class="text-sm text-gray-600">
                                        <p class="font-medium">{{ $transaction->created_at->format('d M Y') }}</p>
                                        <p class="text-xs text-gray-500">{{ $transaction->created_at->format('H:i') }}</p>
                                    </div>
                                </td>

                                {{-- Actions --}}
                                <td class="px-5 py-4 text-center">
                                    <a href="{{ route('user.transactions.show', $transaction) }}" class="inline-flex items-center justify-center p-2 text-gray-400 hover:text-orange-600 hover:bg-orange-50 rounded-lg transition" title="Lihat Detail">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($transactions->hasPages())
                <div class="px-5 py-4 border-t border-gray-100">
                    {{ $transactions->withQueryString()->links() }}
                </div>
            @endif
        @else
            <div class="px-5 py-16 text-center">
                <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="receipt" class="w-8 h-8 text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-1">Belum ada transaksi</h3>
                <p class="text-sm text-gray-500 mb-4">Mulai buat survey untuk memiliki riwayat transaksi</p>
                <a href="{{ route('user.surveys.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-orange-600 text-white rounded-lg font-medium text-sm hover:bg-orange-700 transition">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Buat Survey Baru
                </a>
            </div>
        @endif
    </div>

    {{-- Summary Card --}}
    @if($transactions->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
            @php
                $stats = \App\Models\Transaction::where('user_id', auth()->id())
                    ->selectRaw('
                        SUM(amount) as total_spent,
                        SUM(CASE WHEN status = "paid" THEN amount ELSE 0 END) as paid_amount,
                        SUM(CASE WHEN status = "pending" THEN amount ELSE 0 END) as pending_amount,
                        SUM(CASE WHEN status = "processing" THEN amount ELSE 0 END) as processing_amount
                    ')
                    ->first();
            @endphp

            <div class="bg-gradient-to-br from-emerald-50 to-green-50 rounded-xl p-5 border border-emerald-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-emerald-600 uppercase">Total Pembayaran</p>
                        <p class="text-lg font-bold text-emerald-900 mt-2">
                            Rp {{ number_format($stats->paid_amount ?? 0, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-lg bg-emerald-200 flex items-center justify-center">
                        <i data-lucide="check-circle" class="w-6 h-6 text-emerald-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl p-5 border border-amber-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-amber-600 uppercase">Menunggu Pembayaran</p>
                        <p class="text-lg font-bold text-amber-900 mt-2">
                            Rp {{ number_format($stats->pending_amount ?? 0, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-lg bg-amber-200 flex items-center justify-center">
                        <i data-lucide="clock" class="w-6 h-6 text-amber-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-xl p-5 border border-indigo-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-indigo-600 uppercase">Verifikasi</p>
                        <p class="text-lg font-bold text-indigo-900 mt-2">
                            Rp {{ number_format($stats->processing_amount ?? 0, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-lg bg-indigo-200 flex items-center justify-center">
                        <i data-lucide="loader" class="w-6 h-6 text-indigo-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl p-5 border border-blue-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-blue-600 uppercase">Total Pengeluaran</p>
                        <p class="text-lg font-bold text-blue-900 mt-2">
                            Rp {{ number_format($stats->total_spent ?? 0, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-lg bg-blue-200 flex items-center justify-center">
                        <i data-lucide="trending-up" class="w-6 h-6 text-blue-600"></i>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('[data-progress-width]').forEach(function(el) {
            const value = parseInt(el.dataset.progressWidth || '0', 10);
            const safeValue = Math.min(Math.max(value, 0), 100);
            el.style.width = safeValue + '%';
        });

        if (typeof lucide !== 'undefined') lucide.createIcons();
    });
</script>
@endpush
