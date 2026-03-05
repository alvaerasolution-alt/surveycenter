@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto my-12 p-10 bg-white rounded-3xl shadow-2xl font-sans">

    <!-- Header -->
    <div class="text-center mb-10">
        <h1 class="text-4xl md:text-6xl font-extrabold leading-tight text-yellow-400 drop-shadow-lg mb-4">
            Progress Survey #{{ $transaction->id }}
        </h1>
        <p class="text-lg md:text-xl text-yellow-300">
            Status saat ini: <span class="font-semibold">{{ ucfirst($transaction->status) }}</span>
        </p>
    </div>

    <!-- Survey Info Card -->
    <div class="mb-10 p-6 bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-shadow duration-300">
        <h2 class="text-2xl md:text-3xl font-bold text-yellow-400 mb-4 tracking-wide">Detail Survey</h2>
        <ul class="space-y-2 text-yellow-400 text-base md:text-lg leading-relaxed font-semibold">
            <li><span class="font-bold">Survey:</span> {{ $transaction->survey->title ?? '-' }}</li>
            <li><span class="font-bold">User:</span> {{ optional($transaction->user)->name ?? 'Guest User' }}</li>
            <li><span class="font-bold">Amount:</span> Rp {{ number_format($transaction->amount,0,',','.') }}</li>
            <li><span class="font-bold">Tanggal:</span> {{ $transaction->created_at->format('d M Y H:i') }}</li>
        </ul>
    </div>

    <!-- Progress Bar Card -->
    <div class="mb-10 p-6 bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-shadow duration-300">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4 tracking-wide">Progress Survey</h2>
        <div class="w-full bg-gray-200 rounded-full h-10 overflow-hidden shadow-inner mb-4">
            <div class="bg-green-500 h-10 rounded-full text-white text-center font-bold text-lg md:text-xl transition-all duration-1000 ease-out flex items-center justify-center"
                 style="width: {{ $transaction->progress }}%;">
                {{ $transaction->progress }}%
            </div>
        </div>
        @if($transaction->progress == 100)
            <div class="flex items-center justify-center gap-2 mt-4 text-green-600 font-bold text-xl md:text-2xl animate-pulse">
                <!-- Heroicons check-circle -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 md:h-8 md:w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2l4-4m5 2a9 9 0 11-18 0a9 9 0 0118 0z" />
                </svg>
                Survey selesai
            </div>
        @else
            <p class="text-gray-600 mt-2 text-base md:text-lg leading-relaxed text-center font-semibold">
                Progress akan diperbarui oleh admin sesuai perkembangan survey.
            </p>
        @endif
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-wrap justify-center gap-6 mt-6">
        <a href="{{ route('transactions.invoice', $transaction->id) }}"
           class="px-6 py-3 bg-orange-500 text-white rounded-xl hover:bg-orange-600 text-lg font-semibold shadow-md transition">
           📄 Lihat Invoice
        </a>
    </div>

</div>
@endsection
