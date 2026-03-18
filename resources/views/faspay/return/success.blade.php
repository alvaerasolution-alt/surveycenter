@extends('layouts.app')

@section('title', 'Payment Successful - Faspay')

@section('content')
<div class="container mx-auto py-12">
    <div class="max-w-md mx-auto text-center">
        <!-- Success Animation -->
        <div class="inline-block mb-6 animate-bounce">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full">
                <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>

        <h1 class="text-2xl font-bold text-gray-900 mb-2">Payment Received</h1>
        <p class="text-gray-600 mb-6">Your payment has been received and is being processed</p>

        <!-- Transaction Info -->
        <div class="bg-green-50 rounded-lg p-4 mb-6 text-left border border-green-200">
            <p class="text-sm text-green-700 mb-1">Transaction</p>
            <p class="font-mono text-sm font-semibold text-green-900">{{ $transaction->bill_no }}</p>
            <p class="text-sm text-green-700 mt-3 mb-1">Amount Paid</p>
            <p class="text-lg font-bold text-green-900">IDR {{ number_format($transaction->amount, 0, ',', '.') }}</p>
        </div>

        <!-- Info Box -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <p class="text-sm text-blue-800">
                <strong>ℹ️</strong> Payment notification will be sent to the merchant's webhook URL. 
                The transaction status will be automatically updated once confirmed.
            </p>
        </div>

        <a href="{{ route('faspay.test-transaction.success', $transaction) }}" 
           class="inline-block w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-lg transition duration-200">
            View Transaction Details
        </a>
    </div>
</div>
@endsection
