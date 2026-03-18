@extends('layouts.app')

@section('title', 'Payment Processing - Faspay')

@section('content')
<div class="container mx-auto py-12">
    <div class="max-w-md mx-auto text-center">
        <!-- Animated Loading -->
        <div class="inline-block mb-6">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full">
                <svg class="w-8 h-8 text-blue-600 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
        </div>

        <h1 class="text-2xl font-bold text-gray-900 mb-2">Payment Processing</h1>
        <p class="text-gray-600 mb-6">{{ $message ?? 'Your payment is being processed. Please wait...' }}</p>

        <!-- Transaction Info -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6 text-left">
            <p class="text-sm text-gray-600 mb-1">Transaction</p>
            <p class="font-mono text-sm font-semibold text-gray-900">{{ $transaction->bill_no }}</p>
            <p class="text-sm text-gray-600 mt-3 mb-1">Amount</p>
            <p class="text-lg font-bold text-gray-900">IDR {{ number_format($transaction->amount, 0, ',', '.') }}</p>
        </div>

        <!-- Info Box -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <p class="text-sm text-blue-800">
                This page will automatically update once payment confirmation is received. 
                This usually takes a few seconds.
            </p>
        </div>

        <div class="space-y-3">
            <p class="text-sm text-gray-600">Don't close this window</p>
            <button onclick="checkStatus()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition duration-200">
                Refresh Status
            </button>
        </div>
    </div>
</div>

<script>
    let checkCount = 0;
    const maxChecks = 12; // Check for 1 minute (5 seconds × 12)
    
    function checkStatus() {
        fetch('{{ route("faspay.test-transaction.show", $transaction) }}', {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.text())
        .then(html => {
            // Check if transaction is paid
            if (html.includes('bg-green-100 text-green-800') || html.includes('✓ Paid')) {
                window.location.href = '{{ route("faspay.test-transaction.success", $transaction) }}';
            }
        });
    }

    // Auto-check every 5 seconds
    setInterval(() => {
        if (checkCount < maxChecks) {
            checkStatus();
            checkCount++;
        }
    }, 5000);

    // Initial check
    setTimeout(() => {
        checkStatus();
        checkCount++;
    }, 2000);
</script>
@endsection
