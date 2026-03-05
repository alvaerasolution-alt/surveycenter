@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white shadow-md rounded-lg p-6">
    <h2 class="text-lg font-bold mb-4">Pembayaran</h2>
    <p>Survey: <strong>{{ $transaction->survey->title }}</strong></p>
    <p>Total Biaya: <strong>Rp{{ number_format($transaction->amount, 0, ',', '.') }}</strong></p>

    <a href="{{ $paymentUrl }}" target="_blank"
       class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
       Bayar Sekarang
    </a>
</div>
@endsection
