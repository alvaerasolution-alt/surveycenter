@extends('layouts.app')

@section('title', 'Create Test Transaction - Faspay')

@section('content')
<div class="container mx-auto py-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Create Test Transaction</h1>
        <p class="text-gray-600 mt-2">Create a dummy transaction to test Faspay payment gateway integration</p>
    </div>

    @if ($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <h3 class="text-red-800 font-semibold mb-2">Validation Errors</h3>
            <ul class="list-disc list-inside text-red-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-8">
            <form action="{{ route('faspay.test-transaction.store') }}" method="POST">
                @csrf

                <!-- Amount -->
                <div class="mb-6">
                    <label for="amount" class="block text-sm font-semibold text-gray-700 mb-2">
                        Amount (IDR) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-3 text-gray-500">IDR</span>
                        <input type="number" 
                               id="amount" 
                               name="amount" 
                               value="{{ old('amount', 50000) }}"
                               min="1000" 
                               max="100000000" 
                               step="1000"
                               class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('amount') border-red-500 @enderror"
                               placeholder="e.g., 50000"
                               required>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Minimum: IDR 1,000 | Maximum: IDR 100,000,000</p>
                </div>

                <!-- Customer Name -->
                <div class="mb-6">
                    <label for="customer_name" class="block text-sm font-semibold text-gray-700 mb-2">
                        Customer Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="customer_name" 
                           name="customer_name" 
                           value="{{ old('customer_name', auth()->user()->name ?? 'Test Customer') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('customer_name') border-red-500 @enderror"
                           placeholder="Full name"
                           required>
                </div>

                <!-- Customer Email -->
                <div class="mb-6">
                    <label for="customer_email" class="block text-sm font-semibold text-gray-700 mb-2">
                        Customer Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" 
                           id="customer_email" 
                           name="customer_email" 
                           value="{{ old('customer_email', auth()->user()->email ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('customer_email') border-red-500 @enderror"
                           placeholder="email@example.com"
                           required>
                </div>

                <!-- Customer Phone -->
                <div class="mb-6">
                    <label for="customer_phone" class="block text-sm font-semibold text-gray-700 mb-2">
                        Customer Phone <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" 
                           id="customer_phone" 
                           name="customer_phone" 
                           value="{{ old('customer_phone', '081234567890') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('customer_phone') border-red-500 @enderror"
                           placeholder="+62 812 3456 7890"
                           required>
                </div>

                <!-- Bill Description -->
                <div class="mb-6">
                    <label for="bill_description" class="block text-sm font-semibold text-gray-700 mb-2">
                        Description (Optional)
                    </label>
                    <input type="text" 
                           id="bill_description" 
                           name="bill_description" 
                           value="{{ old('bill_description', 'Test Payment for Faspay Integration') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Payment description">
                </div>

                <!-- Notes -->
                <div class="mb-6">
                    <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">
                        Notes (Optional)
                    </label>
                    <textarea id="notes" 
                              name="notes" 
                              rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Internal notes for this test transaction..."></textarea>
                </div>

                <!-- Info Box -->
                <div class="mb-8 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h3 class="font-semibold text-blue-900 mb-2">ℹ️ Test Transaction Info</h3>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li>• This transaction will be saved to test payment notifications</li>
                        <li>• You'll be redirected to Faspay payment gateway</li>
                        <li>• In sandbox, use Faspay simulator for testing: <a href="https://simulator.faspay.co.id/simulator" target="_blank" class="underline hover:text-blue-600">simulator.faspay.co.id</a></li>
                        <li>• The transaction reference will be logged for webhook testing</li>
                    </ul>
                </div>

                <!-- Form Actions -->
                <div class="flex gap-4">
                    <button type="submit" 
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition duration-200">
                        Create & Continue to Payment
                    </button>
                    <a href="{{ route('faspay.test-transaction.index') }}" 
                       class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-3 rounded-lg text-center transition duration-200">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Format amount input with thousand separators
    document.getElementById('amount').addEventListener('input', function() {
        let value = this.value.replace(/[^0-9]/g, '');
        if (value) {
            this.value = value;
        }
    });

    // Phone number formatting
    document.getElementById('customer_phone').addEventListener('input', function() {
        let value = this.value.replace(/[^0-9+]/g, '');
        this.value = value;
    });
</script>
@endsection
