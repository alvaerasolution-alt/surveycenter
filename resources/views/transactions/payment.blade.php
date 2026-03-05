@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto bg-white shadow-lg rounded-xl p-6 mt-10">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Pembayaran Tagihan</h2>

        <!-- Detail Tagihan -->
        <div class="bg-gray-50 border rounded-lg p-4 mb-6">
            <p><strong>ID Tagihan:</strong> #{{ $transaction->id }}</p>
            <p><strong>Status:</strong>
                <span
                    class="px-2 py-1 text-xs font-bold rounded
                {{ $transaction->status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ strtoupper($transaction->status) }}
                </span>
            </p>
            <p class="mt-2 text-xl font-bold text-gray-800">
                Rp {{ number_format($transaction->amount, 0, ',', '.') }}
            </p>
        </div>

        <!-- Pilih Metode Pembayaran -->
        <div x-data="{ method: '' }">
            <h3 class="text-lg font-semibold mb-3">Pilih Metode Pembayaran</h3>
            <div class="space-y-3">
                <!-- Tombol QRIS -->
                <button @click="method = 'qris'"
                    class="w-full flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50">
                    <span>QRIS</span><span class="text-gray-500">&rsaquo;</span>
                </button>

                <!-- Tombol Bank Neo -->
                <button @click="method = 'neo'"
                    class="w-full flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50">
                    <span>Bank Neo</span><span class="text-gray-500">&rsaquo;</span>
                </button>
            </div>

            <!-- QRIS -->
            <div x-show="method === 'qris'" class="max-w-lg mx-auto bg-white shadow rounded-lg p-6 text-center mt-4">
                <h2 class="text-lg font-semibold mb-4">Pembayaran via QRIS</h2>

                @if ($transaction->qr_data)
                    {{-- Kalau simple-qrcode sudah bisa --}}
                    {{-- {!! QrCode::size(250)->generate($transaction->qr_data) !!} --}}


                    <img src="{{ asset('storage/assets/qriss.jpeg') }}" alt="QRIS" class="mx-auto w-48 mb-3">

                    {{-- Fallback pakai Google Chart API --}}
                    {{-- <img src="https://chart.googleapis.com/chart?cht=qr&chs=300x300&chl={{ urlencode($transaction->qr_data) }}"
                        alt="QRIS" class="mx-auto w-48 mb-3"> --}}

                    <p class="mt-2 text-gray-600">Gunakan aplikasi e-wallet atau m-banking untuk scan QR ini.</p>
                @else
                    <img src="{{ asset('storage/assets/qriss.jpeg') }}" alt="QRIS" class="mx-auto w-48 mb-3">
                    {{-- <p class="text-red-600">Gagal generate QRIS. Silakan coba lagi.</p> --}}
                @endif
            </div>

            <!-- Bank Neo -->
            <div x-show="method === 'neo'" class="max-w-lg mx-auto bg-white shadow rounded-lg p-6 mt-4">
                <h2 class="text-lg font-semibold mb-4 text-center">Pembayaran via Bank Neo</h2>
                <div class="text-left space-y-2">
                    <p><strong>Bank:</strong> Bank Neo
                    <p><strong>No. Rekening:</strong> <span class="font-mono">88880000812107</span></p>
                    <p><strong>Nama Pemilik:</strong> PT Market Research and Branding</p>
                </div>
                <p class="mt-3 text-gray-600 text-sm text-center">
                    Silakan transfer sesuai jumlah tagihan. Setelah transfer, simpan bukti pembayaran Anda.
                </p>
            </div>
        </div>


        <!-- Countdown -->
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">Batas waktu pembayaran</p>
            <p id="countdown" class="text-xl font-bold text-red-600">00:30:00</p>
        </div>

        <!-- Tombol Aksi -->
        <div class="mt-6 flex justify-between">
            <a href="{{ route('transactions.invoice', $transaction->id) }}"
                class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">Lihat Detail Invoice</a>
            <a href="{{ route('transactions.download', $transaction->id) }}"
                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Download Bukti Tagihan</a>
            <a href="{{ route('payment-proofs.create', $transaction->id) }}"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Upload Bukti Pembayaran
            </a>

        </div>
    </div>

    <script>
        let countdown = 1800;
        const countdownEl = document.getElementById('countdown');

        setInterval(() => {
            if (countdown <= 0) return;
            let m = String(Math.floor(countdown / 60)).padStart(2, '0');
            let s = String(countdown % 60).padStart(2, '0');
            countdownEl.textContent = `00:${m}:${s}`;
            countdown--;
        }, 1000);
    </script>
@endsection
