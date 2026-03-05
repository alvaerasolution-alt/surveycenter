@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto bg-white shadow-lg rounded-xl p-6 mt-10">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Upload Bukti Pembayaran</h2>

        <div class="bg-gray-50 border rounded-lg p-4 mb-6">
            <p><strong>ID Tagihan:</strong> #{{ $transaction->id }}</p>
            <p class="mt-2 text-xl font-bold text-gray-800">
                Rp {{ number_format($transaction->amount, 0, ',', '.') }}
            </p>
            <p class="mt-2 text-gray-600">Silakan upload bukti pembayaran Anda di bawah ini.</p>
        </div>

        <form action="{{ route('payment-proofs.store', $transaction->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="block text-sm font-medium">Bukti Pembayaran (jpg/png)</label>
                <input type="file" name="file" class="mt-1 block w-full" accept="image/*" required>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium">Catatan (opsional)</label>
                <textarea name="note" class="mt-1 block w-full border rounded p-2" rows="3"></textarea>
            </div>
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                Kirim Bukti Pembayaran
            </button>
        </form>
    </div>
@endsection
