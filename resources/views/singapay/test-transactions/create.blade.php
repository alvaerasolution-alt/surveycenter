@extends('layouts.admin')

@section('title', 'Buat Test Transaction')
@section('page-title', 'Buat Test Transaction')

@section('content')
<div class="max-w-2xl">

    <div class="mb-4">
        <a href="{{ route('singapay.test.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke daftar
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Buat Test Transaction</h2>
            <p class="text-sm text-gray-500 mt-1">Buat transaksi test untuk menguji integrasi SingaPay — alur sama dengan payment asli</p>
        </div>

        <form method="POST" action="{{ route('singapay.test.store') }}" class="p-6 space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Jumlah (Rp) <span class="text-red-500">*</span></label>
                <input type="number" name="amount" value="{{ old('amount', 10000) }}" required min="1000" max="100000000"
                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('amount') border-red-300 @enderror"
                    placeholder="10000">
                @error('amount')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                <p class="text-xs text-gray-400 mt-1">Minimal Rp 1.000</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Customer <span class="text-red-500">*</span></label>
                    <input type="text" name="customer_name" value="{{ old('customer_name', Auth::user()->name ?? 'Test User') }}" required
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    @error('customer_name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Phone <span class="text-red-500">*</span></label>
                    <input type="text" name="customer_phone" value="{{ old('customer_phone', '08123456789') }}" required
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    @error('customer_phone')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                <input type="email" name="customer_email" value="{{ old('customer_email', Auth::user()->email ?? 'test@test.com') }}" required
                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                @error('customer_email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi</label>
                <input type="text" name="bill_description" value="{{ old('bill_description', 'Test Payment SingaPay') }}"
                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Catatan</label>
                <textarea name="notes" rows="2"
                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent resize-none"
                    placeholder="Catatan opsional...">{{ old('notes') }}</textarea>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t">
                <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition shadow-sm">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Buat & Lanjut ke Pembayaran
                </button>
                <a href="{{ route('singapay.test.index') }}"
                    class="px-5 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-800 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
