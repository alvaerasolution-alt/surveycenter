@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto px-6 py-10">
        <!-- Header -->
        <h2 class="text-3xl font-extrabold text-gray-900 mb-10 tracking-tight border-b pb-4">
            🛒 Keranjang & Riwayat Pesanan
        </h2>

        @if ($transactions->isEmpty())
            <!-- Jika kosong -->
            <div class="bg-white shadow-xl rounded-2xl p-16 text-center">
                <p class="text-xl text-gray-600 mb-4">Belum ada pesanan yang dibuat.</p>
                <a href="{{ route('surveys.create') }}"
                    class="px-8 py-3 bg-gradient-to-r from-yellow-400 to-yellow-500 text-white text-lg rounded-xl shadow-lg hover:shadow-xl hover:from-yellow-500 hover:to-yellow-600 transition-all transform hover:-translate-y-0.5 font-semibold">
                    + Buat Survey Baru
                </a>
            </div>
        @else
            <div class="space-y-6">
                @foreach ($transactions as $transaction)
                    <div class="bg-white shadow-md hover:shadow-2xl transition rounded-2xl p-6 border border-gray-100">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">

                            <!-- Left: Detail -->
                            <div class="flex-1 space-y-2">
                                <p class="text-xs text-gray-400 uppercase tracking-wide">
                                    ID Transaksi:
                                    <span class="font-mono font-semibold text-gray-600">#{{ $transaction->id }}</span>
                                </p>
                                <h3 class="text-lg font-semibold text-gray-800 tracking-tight">
                                    {{ $transaction->survey->title ?? '-' }}
                                </h3>
                                <p class="text-sm text-gray-500">
                                    {{ $transaction->created_at->format('d M Y, H:i') }}
                                </p>
                                <p class="text-3xl font-extrabold text-green-600 mt-2 tracking-tight">
                                    Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                </p>
                                <p class="text-xs text-gray-500 italic">
                                    Termasuk
                                    {{ $transaction->survey->question_count ?? 0 }} pertanyaan &
                                    {{ $transaction->survey->responses->first()->respond_count ?? 0 }} responden
                                </p>
                            </div>

                            <!-- Right: Status + Aksi -->
                            <div class="flex flex-col items-start md:items-end space-y-3">
                                <!-- Status -->
                                @if ($transaction->status == 'paid')
                                    <span
                                        class="px-4 py-1 rounded-full text-xs font-bold text-green-700 bg-green-100 shadow-sm">
                                        ✅ PAID
                                    </span>
                                @elseif ($transaction->status == 'pending')
                                    <span
                                        class="px-4 py-1 rounded-full text-xs font-bold text-yellow-700 bg-yellow-100 shadow-sm">
                                        ⏳ PENDING
                                    </span>
                                @else
                                    <span
                                        class="px-4 py-1 rounded-full text-xs font-bold text-red-700 bg-red-100 shadow-sm">
                                        ❌ UNPAID
                                    </span>
                                @endif

                                <!-- Tombol -->
                                <div class="flex gap-2">
                                    <a href="{{ route('transactions.invoice', $transaction->id) }}"
                                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm font-medium shadow-sm transition">
                                        📄 Lihat Invoice
                                    </a>
                                    <a href="{{ route('transactions.progress', $transaction->id) }}"
                                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm font-medium shadow-sm transition">
                                        📊 Lihat Progress
                                    </a>
                                    @if ($transaction->status != 'paid')
                                        <a href="{{ route('transactions.payment', $transaction->id) }}"
                                            class="px-5 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 text-sm font-semibold shadow-lg transition transform hover:-translate-y-0.5">
                                            💳 Bayar Sekarang
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
