{{-- resources/views/transactions/invoice.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto my-12 p-8 bg-white rounded-2xl shadow-2xl font-sans">
        <!-- Header -->
        <div class="flex items-start justify-between mb-8 border-b pb-4">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Invoice</h1>
                <p class="text-sm text-gray-500 font-mono">#{{ $transaction->id }}</p>
            </div>

            <div class="text-right">
                <p class="text-xs text-gray-500 uppercase tracking-wide">Tanggal</p>
                <p class="text-sm font-medium text-gray-700">{{ $transaction->created_at->format('d M Y H:i') }}</p>
                <div class="mt-2">
                    @php $st = $transaction->status @endphp
                    <span
                        class="inline-block px-3 py-1 rounded-full text-xs font-semibold tracking-wide
                    {{ $st == 'paid' ? 'bg-green-100 text-green-700' : ($st == 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                        {{ strtoupper($st) }}
                    </span>
                </div>
            </div>
            @if ($st == 'pending')
                <!-- Modal -->
                <div id="pendingModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
                    <div class="bg-white rounded-2xl shadow-lg max-w-md w-full p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Status Transaksi Pending</h2>
                        <p class="text-gray-600 mb-6">
                            Transaksi Anda masih <span class="font-bold">pending</span>.
                            Jika Anda sudah membayar namun status tidak berubah, silakan hubungi admin dan berikan bukti
                            pembayaran.
                        </p>
                        <div class="flex justify-between">
                            <button id="closeModal"
                                class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition">
                                Tutup
                            </button>

                            <!-- Tombol Hubungi Admin -->
                            <a href="https://wa.me/6285198887963?text={{ urlencode(
                                'Halo Admin, saya sudah melakukan pembayaran. Berikut data transaksi:' .
                                    "\n\n" .
                                    'ID: ' .
                                    $transaction->id .
                                    "\n" .
                                    'Nama: ' .
                                    (optional($transaction->user)->name ?? 'Guest User') .
                                    "\n" .
                                    'Jumlah: Rp ' .
                                    number_format($transaction->amount, 0, ',', '.') .
                                    "\n" .
                                    'Tanggal: ' .
                                    $transaction->created_at->format('d M Y H:i'),
                            ) }}"
                                target="_blank"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                                Hubungi Admin
                            </a>
                        </div>
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const modal = document.getElementById('pendingModal');
                        const closeBtn = document.getElementById('closeModal');

                        // Tampilkan modal
                        modal.classList.remove('hidden');

                        // Tutup modal saat tombol diklik
                        closeBtn.addEventListener('click', function() {
                            modal.classList.add('hidden');
                        });

                        // Tutup modal saat klik di luar konten
                        modal.addEventListener('click', function(e) {
                            if (e.target === modal) modal.classList.add('hidden');
                        });
                    });
                </script>
            @endif

        </div>

        <!-- Detail Penagih & Pelanggan -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <div>
                <h3 class="text-xs font-bold uppercase text-gray-500 mb-1 tracking-wider">Penagih</h3>
                <p class="font-semibold text-gray-800">Survey Center Indonesia</p>
                <p class="text-sm text-gray-500">Jl. Raya Palka.Km 03, Sindangheula, Kec. Pabuaran, Royal Sindangheula
                    Kabupaten Serang, Blok A 23B, Kabupaten Serang, Banten 42163</p>
                <p class="text-sm text-gray-500">NPWP: 47.831.083.2-124.000</p>
            </div>

            <div>
                <h3 class="text-xs font-bold uppercase text-gray-500 mb-1 tracking-wider">Pelanggan</h3>
                <p class="font-semibold text-gray-800">
                    {{ optional($transaction->user)->name ?? 'Guest User' }}
                </p>
                <p class="text-sm text-gray-500">{{ optional($transaction->user)->email ?? '-' }}</p>
                <p class="text-sm text-gray-500">Survey: {{ $transaction->survey->title ?? '-' }}</p>
            </div>
        </div>

        <!-- Tabel -->
        <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
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
                        <td class="p-3 border-b text-right font-medium font-mono">
                            Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="p-3 border-b">Subtotal</td>
                        <td class="p-3 border-b text-right font-mono">
                            Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr class="bg-gray-50">
                        <td class="p-3 font-bold text-gray-900">Total</td>
                        <td class="p-3 text-right font-extrabold text-xl text-indigo-600 font-serif">
                            Rp {{ number_format(round($transaction->amount), 0, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Aksi -->
        <div class="mt-8 flex items-center justify-between">
            <p class="text-sm text-gray-500">Metode Pembayaran:
                <span class="font-semibold">{{ strtoupper($transaction->payment_method ?? 'N/A') }}</span>
            </p>

            <div class="flex items-center gap-3">
                <a href="{{ route('transactions.download', $transaction->id) }}"
                    class="px-5 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700 text-sm font-semibold">
                    ⬇️ Download PDF
                </a>

                @if ($transaction->status != 'paid')
                    <a href="{{ route('transactions.payment', $transaction->id) }}"
                        class="px-5 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 text-sm font-semibold">
                        💳 Bayar Sekarang
                    </a>
                @endif
            </div>
        </div>

        <p class="mt-6 text-xs text-gray-400">* Simpan bukti transfer atau screenshot pembayaran untuk konfirmasi.</p>
    </div>
@endsection
