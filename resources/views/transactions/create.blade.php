@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen py-10">
    <div class="max-w-3xl mx-auto bg-white shadow-xl rounded-xl p-6">

        <!-- Header -->
        <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-yellow-500" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8v10" />
            </svg>
            Konfirmasi Transaksi
        </h2>

        <!-- Detail Survey -->
        <div class="bg-gray-50 border rounded-lg p-4 mb-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Detail Survey</h3>
            <p><strong>Judul:</strong> {{ $survey->title }}</p>
            <p><strong>Jumlah Pertanyaan:</strong> {{ $survey->question_count }}</p>
            <p><strong>Jumlah Responden:</strong> {{ $survey->respondent_count ?? ($survey->responses->first()->respond_count ?? 0) }}</p>
            <p><strong>Link Survey:</strong>
                @if(!empty($survey->form_link))
                    <a href="{{ $survey->form_link }}" target="_blank" rel="noopener noreferrer">{{ $survey->form_link }}</a>
                @else
                    -
                @endif
            </p>
        </div>

        <!-- Ringkasan Biaya -->
        @php
            $price = 1000; // misalnya Rp 1000 per pertanyaan
            $respond = $survey->respondent_count ?? ($survey->responses->first()->respond_count ?? 0);
            $total = $survey->question_count * $price * $respond;
        @endphp

        <div class="bg-white border rounded-lg shadow-sm overflow-hidden mb-6">
            <div class="bg-gray-100 px-4 py-2 font-semibold text-gray-800">Ringkasan Biaya</div>
            <div class="divide-y text-sm">
                <div class="flex justify-between px-4 py-2">
                    <span>Biaya per pertanyaan</span>
                    <span>Rp {{ number_format($price, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between px-4 py-2">
                    <span>Jumlah pertanyaan</span>
                    <span>{{ $survey->question_count }}</span>
                </div>
                <div class="flex justify-between px-4 py-2">
                    <span>Total per pertanyaan</span>
                    <span>Rp {{ number_format($survey->question_count * $price, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between px-4 py-2">
                    <span>Jumlah responden</span>
                    <span>{{ $respond }}</span>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 flex justify-between items-center font-bold text-green-600 text-lg">
                <span>Total yang harus dibayar</span>
                <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Form Transaksi -->
        <form action="{{ route('transactions.store', $survey->id) }}" method="POST">
            @csrf
            <input type="hidden" name="amount" value="{{ $total }}">

            <div class="flex justify-end gap-3">
                <a href="{{ route('surveys.index') }}"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700">
                    Lanjutkan Bayar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
