@extends('layouts.app')

@section('title', 'Payment Successful - Faspay')

@section('content')
<div class="container mx-auto py-6">
    <div class="max-w-2xl mx-auto">
        <!-- Success Animation -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            </div>

            <h1 class="text-3xl font-bold text-gray-900 mb-2">Payment Successful!</h1>
            <p class="text-gray-600 text-lg">Your test transaction has been completed</p>
        </div>

        <!-- Transaction Details Card -->
        <div class="bg-white rounded-lg shadow-md p-8 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Transaction Details</h2>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Bill Number</p>
                    <p class="font-mono font-semibold text-gray-900">{{ $transaction->bill_no }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600 mb-1">Transaction ID</p>
                    <p class="font-mono font-semibold text-gray-900">{{ $transaction->trx_id ?? 'Pending' }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600 mb-1">Amount</p>
                    <p class="text-2xl font-bold text-gray-900">IDR {{ number_format($transaction->amount, 0, ',', '.') }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600 mb-1">Payment Method</p>
                    <p class="font-semibold text-gray-900">{{ $transaction->payment_channel ?? 'Processing' }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600 mb-1">Status</p>
                    <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full 
                        @if ($transaction->status === 'paid')
                            bg-green-100 text-green-800
                        @elseif ($transaction->status === 'processing')
                            bg-blue-100 text-blue-800
                        @else
                            bg-gray-100 text-gray-800
                        @endif">
                        {{ ucfirst($transaction->status) }}
                    </span>
                </div>

                <div>
                    <p class="text-sm text-gray-600 mb-1">Payment Date</p>
                    <p class="font-semibold text-gray-900">
                        @if ($transaction->payment_date)
                            {{ $transaction->payment_date->format('M d, Y H:i:s') }}
                        @else
                            Waiting for confirmation...
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Customer Info Card -->
        <div class="bg-gray-50 rounded-lg border border-gray-200 p-6 mb-6">
            <h3 class="font-semibold text-gray-900 mb-4">Customer Information</h3>
            <div class="space-y-2">
                <p class="text-gray-700"><span class="font-medium">Name:</span> {{ $transaction->customer_name }}</p>
                <p class="text-gray-700"><span class="font-medium">Email:</span> {{ $transaction->customer_email }}</p>
                <p class="text-gray-700"><span class="font-medium">Phone:</span> {{ $transaction->customer_phone }}</p>
            </div>
        </div>

        <!-- Info Messages -->
        <div class="space-y-4 mb-8">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-sm text-blue-800">
                    <strong>ℹ️ Next Steps:</strong> If payment status shows "Processing", it may take a few seconds for the webhook notification to be received and processed. This is normal in test environments.
                </p>
            </div>

            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <p class="text-sm text-green-800">
                    <strong>✓ Webhook Ready:</strong> Payment notification should be sent to your configured webhook URL. You can check your application logs for details.
                </p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-4">
            <a href="{{ route('faspay.test-transaction.index') }}" 
               class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg text-center transition duration-200">
                View All Transactions
            </a>
            <a href="{{ route('faspay.test-transaction.create') }}" 
               class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-3 rounded-lg text-center transition duration-200">
                Create Another Transaction
            </a>
        </div>

        <!-- Debug Info (if in local) -->
        @if (app()->isLocal())
            <div class="mt-8 bg-gray-100 rounded-lg border border-gray-300 p-6">
                <h3 class="font-semibold text-gray-900 mb-3">Debug Information (Local Only)</h3>
                <div class="bg-white rounded p-3 font-mono text-xs text-gray-700 overflow-auto">
                    <pre>{{ json_encode([
                        'transaction_id' => $transaction->id,
                        'bill_no' => $transaction->bill_no,
                        'trx_id' => $transaction->trx_id,
                        'status' => $transaction->status,
                        'amount' => $transaction->amount,
                        'payment_date' => $transaction->payment_date?->toDateTimeString(),
                        'payment_channel' => $transaction->payment_channel,
                    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
