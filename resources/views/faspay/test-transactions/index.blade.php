@extends('layouts.app')

@section('title', 'Faspay Test Transactions')

@section('content')
<div class="container mx-auto py-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Faspay Test Transactions</h1>
        <p class="text-gray-600 mt-2">Create and manage test payment transactions for Faspay integration testing</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-5">
                    <p class="text-sm text-blue-700 font-medium">Active</p>
                    <p class="text-2xl font-bold text-blue-900">{{ $activeCount }}</p>
                </div>
            </div>
        </div>

        <div class="bg-green-50 border border-green-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-5">
                    <p class="text-sm text-green-700 font-medium">Paid</p>
                    <p class="text-2xl font-bold text-green-900">{{ $paidCount }}</p>
                </div>
            </div>
        </div>

        <div class="bg-purple-50 border border-purple-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m0 0h6"/>
                    </svg>
                </div>
                <div class="ml-5">
                    <p class="text-sm text-purple-700 font-medium">Create New</p>
                    <a href="{{ route('faspay.test-transaction.create') }}" class="text-lg font-bold text-purple-900 hover:underline">Add Transaction</a>
                </div>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <h3 class="text-red-800 font-semibold mb-2">Errors</h3>
            <ul class="list-disc list-inside text-red-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 text-green-800">
            ✓ {{ session('success') }}
        </div>
    @endif

    <!-- Transactions Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Bill No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Payment Method</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Created</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($transactions as $transaction)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">
                            {{ $transaction->bill_no }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <div>{{ $transaction->customer_name }}</div>
                            <div class="text-xs text-gray-500">{{ $transaction->customer_email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                            IDR {{ number_format($transaction->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($transaction->status === \App\Models\FaspayTestTransaction::STATUS_PAID)
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    ✓ Paid
                                </span>
                            @elseif ($transaction->status === \App\Models\FaspayTestTransaction::STATUS_PROCESSING)
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    ⟳ Processing
                                </span>
                            @elseif ($transaction->status === \App\Models\FaspayTestTransaction::STATUS_EXPIRED)
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    ✕ Expired
                                </span>
                            @elseif ($transaction->status === \App\Models\FaspayTestTransaction::STATUS_FAILED)
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    ✗ Failed
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    ⊗ {{ ucfirst($transaction->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $transaction->payment_channel ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $transaction->created_at->format('M d, Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                            <a href="{{ route('faspay.test-transaction.show', $transaction) }}" 
                               class="text-blue-600 hover:text-blue-900 font-medium">View</a>
                            @if (!$transaction->isPaid() && !$transaction->isExpired())
                                <a href="{{ route('faspay.test-transaction.payment', $transaction) }}" 
                                   class="text-green-600 hover:text-green-900 font-medium">Pay</a>
                            @endif
                            <form action="{{ route('faspay.test-transaction.destroy', $transaction) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Delete this transaction?')" 
                                        class="text-red-600 hover:text-red-900 font-medium">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <p class="text-gray-500">No test transactions yet.</p>
                            <a href="{{ route('faspay.test-transaction.create') }}" class="text-blue-600 hover:underline mt-2 inline-block">
                                Create your first test transaction →
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if ($transactions->hasPages())
        <div class="mt-6">
            {{ $transactions->links() }}
        </div>
    @endif
</div>
@endsection
