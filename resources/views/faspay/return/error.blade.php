@extends('layouts.app')

@section('title', 'Payment Error - Faspay')

@section('content')
<div class="container mx-auto py-12">
    <div class="max-w-md mx-auto">
        <!-- Error Icon -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-red-100 rounded-full mb-4">
                <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
            </div>

            <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $message ?? 'Payment Error' }}</h1>
            <p class="text-gray-600 mb-6">An error occurred while processing your payment</p>
        </div>

        <!-- Error Details -->
        @if (!empty($details))
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                @if (is_array($details))
                    @foreach ($details as $key => $value)
                        <p class="text-sm text-red-800 mb-1">
                            <span class="font-semibold">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                            {{ $value }}
                        </p>
                    @endforeach
                @else
                    <p class="text-sm text-red-800">{{ $details }}</p>
                @endif
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="space-y-2">
            <a href="{{ route('faspay.test-transaction.index') }}" 
               class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg text-center transition duration-200">
                Back to Transactions
            </a>
            <a href="{{ route('faspay.test-transaction.create') }}" 
               class="block w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 rounded-lg text-center transition duration-200">
                Create New Transaction
            </a>
        </div>

        <!-- Support Info -->
        <div class="mt-6 bg-gray-50 rounded-lg p-4 text-center">
            <p class="text-sm text-gray-700">
                <strong>Need help?</strong><br>
                Check the application logs for detailed error information.
            </p>
        </div>
    </div>
</div>
@endsection
