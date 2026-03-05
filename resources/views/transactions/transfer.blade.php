@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-white shadow-md rounded-xl p-6 mt-10">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Pembayaran via Transfer Bank</h2>

    <p class="text-gray-700 mb-4">Silakan transfer sesuai nominal berikut:</p>

    <div class="bg-gray-50 border rounded-lg p-4 mb-6">
        <p><strong>Nominal:</strong> Rp {{ number_format($transaction->amount, 0, ',', '.') }}</p>
        <p><strong>Bank:</strong> BCA</p>
        <p><strong>No Rekening:</strong> 1234567890</p>
        <p><strong>Atas Nama:</strong> PT Survey Center Indonesia</p>
    </div>

    <div class="flex justify-end">
        <a href="{{ route('surveys.index') }}" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Selesai
        </a>
    </div>
</div>
@endsection
