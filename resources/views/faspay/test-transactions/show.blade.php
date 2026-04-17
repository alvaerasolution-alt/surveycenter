@extends('layouts.app')

@section('title', 'Transaction Details - Faspay')

@section('content')
<div class="container mx-auto py-6">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Transaction Details</h1>
            <p class="text-gray-600 mt-1">Bill: <span class="font-mono font-semibold">{{ $transaction->bill_no }}</span></p>
        </div>
        <a href="{{ route('faspay.test-transaction.index') }}" class="text-blue-600 hover:text-blue-900">
            ← Back to List
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-900">Payment Status</h2>
                    <span class="px-4 py-2 rounded-full text-sm font-semibold
                        @if ($transaction->status === \App\Models\FaspayTestTransaction::STATUS_PAID)
                            bg-green-100 text-green-800
                        @elseif ($transaction->status === \App\Models\FaspayTestTransaction::STATUS_PROCESSING)
                            bg-blue-100 text-blue-800
                        @elseif ($transaction->status === \App\Models\FaspayTestTransaction::STATUS_FAILED)
                            bg-red-100 text-red-800
                        @elseif ($transaction->status === \App\Models\FaspayTestTransaction::STATUS_EXPIRED)
                            bg-gray-100 text-gray-800
                        @else
                            bg-yellow-100 text-yellow-800
                        @endif">
                        {{ $transaction->statusLabel() }}
                    </span>
                </div>

                @if ($transaction->status === \App\Models\FaspayTestTransaction::STATUS_PAID)
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                        <p class="text-green-800 font-semibold">✓ Payment Confirmed</p>
                        <p class="text-sm text-green-700">Payment was successfully received and processed.</p>
                    </div>
                @elseif ($transaction->status === \App\Models\FaspayTestTransaction::STATUS_PROCESSING)
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                        <p class="text-blue-800 font-semibold">⟳ Processing</p>
                        <p class="text-sm text-blue-700">Waiting for payment confirmation from the payment gateway.</p>
                    </div>
                @elseif ($transaction->status === \App\Models\FaspayTestTransaction::STATUS_FAILED)
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                        <p class="text-red-800 font-semibold">✗ Payment Failed</p>
                        <p class="text-sm text-red-700">{{ $transaction->notes ?? 'Payment processing encountered an error.' }}</p>
                    </div>
                @elseif ($transaction->status === \App\Models\FaspayTestTransaction::STATUS_EXPIRED)
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">
                        <p class="text-gray-800 font-semibold">⊗ Expired</p>
                        <p class="text-sm text-gray-700">This transaction has expired and can no longer be paid.</p>
                    </div>
                @endif
            </div>

            <!-- Transaction Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Transaction Information</h2>

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm text-gray-600">Bill Number</label>
                            <p class="font-mono font-semibold text-gray-900">{{ $transaction->bill_no }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Transaction ID</label>
                            <p class="font-mono font-semibold text-gray-900">{{ $transaction->trx_id ?? 'Not assigned' }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">Description</label>
                        <p class="text-gray-900">{{ $transaction->bill_description }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm text-gray-600">Amount</label>
                            <p class="text-2xl font-bold text-gray-900">IDR {{ number_format($transaction->amount, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Currency</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $transaction->currency }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm text-gray-600">Created</label>
                            <p class="text-gray-900">{{ $transaction->created_at->format('M d, Y H:i:s') }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Expires</label>
                            <p class="text-gray-900">
                                @if ($transaction->expires_at)
                                    {{ $transaction->expires_at->format('M d, Y H:i:s') }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Details -->
            @if ($transaction->payment_date || $transaction->payment_channel)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Payment Details</h2>

                    <div class="space-y-4">
                        @if ($transaction->payment_date)
                            <div>
                                <label class="text-sm text-gray-600">Payment Date</label>
                                <p class="text-gray-900">{{ $transaction->payment_date->format('M d, Y H:i:s') }}</p>
                            </div>
                        @endif

                        @if ($transaction->payment_channel)
                            <div>
                                <label class="text-sm text-gray-600">Payment Channel</label>
                                <p class="text-gray-900">{{ $transaction->payment_channel }}</p>
                            </div>
                        @endif

                        @if ($transaction->payment_reff)
                            <div>
                                <label class="text-sm text-gray-600">Payment Reference</label>
                                <p class="font-mono text-gray-900">{{ $transaction->payment_reff }}</p>
                            </div>
                        @endif

                        @if ($transaction->bank_user_name)
                            <div>
                                <label class="text-sm text-gray-600">Bank User Name</label>
                                <p class="text-gray-900">{{ $transaction->bank_user_name }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Customer Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Customer Information</h2>

                <div class="space-y-4">
                    <div>
                        <label class="text-sm text-gray-600">Name</label>
                        <p class="text-gray-900">{{ $transaction->customer_name }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Email</label>
                        <p class="text-gray-900">{{ $transaction->customer_email }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Phone</label>
                        <p class="text-gray-900">{{ $transaction->customer_phone }}</p>
                    </div>
                </div>
            </div>

            <!-- Raw Response Data (if available) -->
            @if ($transaction->payment_response)
                <div class="bg-gray-50 rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Raw Payment Response</h2>
                    <div class="bg-white rounded p-3 font-mono text-xs text-gray-700 overflow-auto max-h-64 border border-gray-200">
                        <pre>{{ json_encode(json_decode($transaction->payment_response, true), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar Actions -->
        <div class="lg:col-span-1">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="font-semibold text-gray-900 mb-4">Actions</h3>

                <div class="space-y-2">
                    @if (!$transaction->isPaid() && !$transaction->isExpired())
                        <a href="{{ route('faspay.test-transaction.payment', $transaction) }}" 
                           class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg text-center transition duration-200 text-sm">
                            Pay Now
                        </a>
                    @endif

                    <a href="{{ route('faspay.test-transaction.index') }}" 
                       class="block w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 rounded-lg text-center transition duration-200 text-sm">
                        Back to List
                    </a>

                    <form action="{{ route('faspay.test-transaction.destroy', $transaction) }}" method="POST" class="block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                onclick="return confirm('Delete this transaction?')"
                                class="w-full bg-red-100 hover:bg-red-200 text-red-800 font-semibold py-2 rounded-lg text-center transition duration-200 text-sm">
                            Delete
                        </button>
                    </form>
                </div>
            </div>

            <!-- Status Timeline -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Timeline</h3>

                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-8 w-8 rounded-full bg-blue-100">
                                <svg class="h-5 w-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-2a6 6 0 100-12 6 6 0 000 12z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Created</p>
                            <p class="text-xs text-gray-600">{{ $transaction->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>

                    @if ($transaction->payment_date)
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-8 w-8 rounded-full bg-green-100">
                                    <svg class="h-5 w-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Paid</p>
                                <p class="text-xs text-gray-600">{{ $transaction->payment_date->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
