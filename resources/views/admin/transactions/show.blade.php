@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto my-12 p-8 bg-white rounded-2xl shadow-2xl font-sans">
    <h1 class="text-3xl font-bold mb-4">Invoice Transaksi #{{ $transaction->id }}</h1>

    <!-- Status -->
    @php $st = $transaction->status; @endphp
    <div class="mb-4">
        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
        {{ $st == 'paid' ? 'bg-green-100 text-green-700' : ($st == 'pending' ? 'bg-orange-100 text-orange-700' : 'bg-red-100 text-red-700') }}">
            {{ strtoupper($st) }}
        </span>
    </div>

    <!-- Penagih & Pelanggan -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div>
            <h3 class="text-xs font-bold uppercase text-gray-500 mb-1 tracking-wider">Penagih</h3>
            <p class="font-semibold text-gray-800">Survey Center Indonesia</p>
            <p class="text-sm text-gray-500">Jl. Contoh No.1, Jakarta</p>
            <p class="text-sm text-gray-500">NPWP: 02.670.337.1-609-000</p>
        </div>

        <div>
            <h3 class="text-xs font-bold uppercase text-gray-500 mb-1 tracking-wider">Pelanggan</h3>
            <p class="font-semibold text-gray-800">{{ optional($transaction->user)->name ?? 'Guest User' }}</p>
            <p class="text-sm text-gray-500">{{ optional($transaction->user)->email ?? '-' }}</p>
            <p class="text-sm text-gray-500">Survey: {{ $transaction->survey->title ?? '-' }}</p>
        </div>
    </div>

    <!-- Tabel Detail -->
    <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm mb-6">
        <table class="w-full text-sm border-collapse">
            <thead>
                <tr class="bg-gray-100 text-gray-700 uppercase text-xs tracking-wider">
                    <th class="text-left p-3 border-b">Deskripsi</th>
                    <th class="text-right p-3 border-b w-40">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="p-3 border-b">Pembayaran Survey #{{ $transaction->survey_id }}</td>
                    <td class="p-3 border-b text-right font-medium font-mono">Rp {{ number_format($transaction->amount,0,',','.') }}</td>
                </tr>
                <tr>
                    <td class="p-3 border-b">Subtotal</td>
                    <td class="p-3 border-b text-right font-mono">Rp {{ number_format($transaction->amount,0,',','.') }}</td>
                </tr>
                <tr class="bg-gray-50">
                    <td class="p-3 font-bold text-gray-900">Total</td>
                    <td class="p-3 text-right font-extrabold text-xl text-orange-600 font-serif">Rp {{ number_format(round($transaction->amount),0,',','.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- QRIS -->
    <div class="mt-6 p-4 border rounded-lg text-center">
        <h4 class="font-semibold mb-3">Scan QRIS untuk membayar</h4>
        <img src="{{ asset('storage/assets/qriss.jpeg') }}" alt="QRIS" class="mx-auto w-48 mb-2">
        <p class="text-gray-600 mb-2">Gunakan aplikasi e-wallet atau m-banking untuk scan QR ini.</p>
        <a href="{{ asset('storage/assets/qriss.jpeg') }}" download="QRIS_{{ $transaction->id }}.jpeg" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition">
            ⬇️ Download QRIS
        </a>
    </div>

    <!-- Aksi -->
    <div class="mt-6 flex items-center justify-between">
        <p class="text-sm text-gray-500">Metode Pembayaran: <span class="font-semibold">{{ strtoupper($transaction->payment_method ?? 'N/A') }}</span></p>
        <div class="flex gap-3">
            <a href="{{ route('transactions.download', $transaction->id) }}" class="px-5 py-2 bg-orange-600 text-white rounded-lg shadow hover:bg-orange-700 text-sm font-semibold">
                ⬇️ Download PDF
            </a>
            @if ($transaction->status != 'paid')
            <a href="{{ route('transactions.payment', $transaction->id) }}" class="px-5 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 text-sm font-semibold">
                💳 Bayar Sekarang
            </a>
            @endif
            <a href="https://wa.me/6281234567890?text={{ urlencode('Halo Admin, saya sudah melakukan pembayaran. Berikut data transaksi:' . "\n\n" . 'ID: '.$transaction->id . "\n" . 'Nama: ' . (optional($transaction->user)->name ?? 'Guest User') . "\n" . 'Jumlah: Rp '.number_format($transaction->amount,0,',','.') . "\n" . 'Tanggal: '.$transaction->created_at->format('d M Y H:i')) }}" target="_blank" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                Hubungi Admin
            </a>
        </div>
    </div>
</div>
@endsection
