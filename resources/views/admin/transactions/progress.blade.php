@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto my-12 p-8 bg-white rounded-2xl shadow-xl font-sans">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Update Progress Survey #{{ $transaction->id }}</h1>
        <span class="px-3 py-1 bg-gray-100 rounded-full text-sm font-medium text-gray-700">
            Status: {{ ucfirst($transaction->status) }}
        </span>
    </div>

    <!-- Feedback Messages -->
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 mb-4 rounded shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-3 mb-4 rounded shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <!-- Survey Info -->
    <div class="mb-6 p-4 bg-gray-50 rounded-lg shadow-inner">
        <p class="text-gray-700"><span class="font-semibold">Survey:</span> {{ $transaction->survey->title ?? '-' }}</p>
        <p class="text-gray-700 mt-1"><span class="font-semibold">User:</span> {{ optional($transaction->user)->name ?? 'Guest User' }}</p>
        <p class="text-gray-700 mt-1"><span class="font-semibold">Amount:</span> Rp {{ number_format($transaction->amount,0,',','.') }}</p>
    </div>

    <!-- Progress Form -->
    <form action="{{ route('admin.transactions.progress.update', $transaction) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label for="progress" class="block font-semibold mb-2">Progress (%)</label>
            <input type="number" name="progress" id="progress" min="0" max="100"
                   value="{{ old('progress', $transaction->progress) }}"
                   class="border p-2 rounded w-28 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
        </div>

        <!-- Progress Bar Live Preview -->
        <div class="w-full bg-gray-200 rounded-full h-6 overflow-hidden">
            <div id="progressBar"
                 class="bg-green-500 h-6 rounded-full text-white text-center font-semibold transition-all duration-500 ease-out flex items-center justify-center"
                 style="width: {{ old('progress', $transaction->progress) }}%;">
                {{ old('progress', $transaction->progress) }}%
            </div>
        </div>

        <button type="submit"
                class="px-6 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition font-semibold shadow">
            Update Progress
        </button>
    </form>

    <!-- Back Button -->
    <div class="mt-6">
        <a href="{{ route('admin.transactions.progress.index') }}"
           class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition font-semibold shadow">
            Kembali
        </a>
    </div>

    <!-- Notes -->
    <div class="mt-4 text-gray-500 text-sm">
        * Progress hanya bisa diupdate untuk transaksi yang sudah <strong>paid</strong>.
    </div>
</div>

<!-- Live JS Update -->
<script>
    const progressInput = document.getElementById('progress');
    const progressBar = document.getElementById('progressBar');

    progressInput.addEventListener('input', function() {
        let value = Math.min(Math.max(this.value, 0), 100); // batasi 0-100
        progressBar.style.width = value + '%';
        progressBar.textContent = value + '%';
    });
</script>
@endsection
