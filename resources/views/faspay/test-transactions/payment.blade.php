@extends('layouts.app')

@section('title', 'Process Payment - Faspay')

@section('content')
<div class="container mx-auto py-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Payment Details</h1>
        <p class="text-gray-600 mt-1">Complete your payment using Faspay Xpress</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Transaction Summary -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Transaction Summary</h2>
                
                <div class="space-y-3 border-t pt-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Bill Number:</span>
                        <span class="font-mono font-semibold">{{ $transaction->bill_no }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Description:</span>
                        <span class="text-right">{{ $transaction->bill_description }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Status:</span>
                        <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 text-sm font-medium">
                            {{ ucfirst($transaction->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Customer Information</h2>
                
                <div class="space-y-3">
                    <div>
                        <label class="text-sm text-gray-600">Name</label>
                        <p class="font-semibold text-gray-900">{{ $transaction->customer_name }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Email</label>
                        <p class="font-semibold text-gray-900">{{ $transaction->customer_email }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Phone</label>
                        <p class="font-semibold text-gray-900">{{ $transaction->customer_phone }}</p>
                    </div>
                </div>
            </div>

            <!-- Payment Methods Available -->
            @if ($faspayConfigured)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Available Payment Methods</h2>
                    
                    <div class="space-y-3">
                        @if ($paymentChannels['virtual_account'] ?? false)
                            <div class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                                <input type="radio" id="method_va" name="payment_method" value="VA" class="mr-3">
                                <label for="method_va" class="flex-1 cursor-pointer">
                                    <div class="font-semibold text-gray-900">Bank Virtual Account</div>
                                    <div class="text-sm text-gray-600">BCA, BNI, BRI, Mandiri, Permata</div>
                                </label>
                            </div>
                        @endif

                        @if ($paymentChannels['qris'] ?? false)
                            <div class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                                <input type="radio" id="method_qris" name="payment_method" value="QRIS" class="mr-3" checked>
                                <label for="method_qris" class="flex-1 cursor-pointer">
                                    <div class="font-semibold text-gray-900">QRIS</div>
                                    <div class="text-sm text-gray-600">Scan QR code using any banking app</div>
                                </label>
                            </div>
                        @endif

                        @if ($paymentChannels['e_wallet'] ?? false)
                            <div class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                                <input type="radio" id="method_ewallet" name="payment_method" value="EWALLET" class="mr-3">
                                <label for="method_ewallet" class="flex-1 cursor-pointer">
                                    <div class="font-semibold text-gray-900">E-Wallet</div>
                                    <div class="text-sm text-gray-600">GoPay, OVO, DANA, LinkAja</div>
                                </label>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                    <h3 class="text-red-900 font-semibold mb-2">⚠️ Faspay Not Configured</h3>
                    <p class="text-red-700 mb-3">Faspay credentials are not configured. Please add the following to your .env file:</p>
                    <ul class="text-sm text-red-700 font-mono space-y-1">
                        <li>FASPAY_ENV=sandbox</li>
                        <li>FASPAY_MERCHANT_ID=your_merchant_id</li>
                        <li>FASPAY_USER_ID=your_user_id</li>
                        <li>FASPAY_PASSWORD=your_password</li>
                        <li>FASPAY_API_KEY=your_api_key</li>
                    </ul>
                </div>
            @endif
        </div>

        <!-- Amount Card -->
        <div class="lg:col-span-1">
            <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-lg shadow-lg p-6 text-white sticky top-6">
                <p class="text-blue-100 text-sm mb-2">Total Amount</p>
                <p class="text-4xl font-bold mb-6">
                    IDR {{ number_format($transaction->amount, 0, ',', '.') }}
                </p>

                <div class="bg-blue-500 rounded-lg p-4 mb-6">
                    <p class="text-sm text-blue-100 mb-1">Payment expires in</p>
                    <p class="text-2xl font-bold">
                        @if ($transaction->expires_at)
                            {{ $transaction->expires_at->diffForHumans() }}
                        @else
                            30 minutes
                        @endif
                    </p>
                </div>

                @if ($faspayConfigured)
                    <form action="{{ route('faspay.test-transaction.process-payment', $transaction) }}" method="POST" class="space-y-3">
                        @csrf
                        
                        @if (app()->isLocal())
                            <div class="bg-blue-500 rounded-lg p-3 mb-4">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" name="use_simulator" value="1" class="mr-2">
                                    <span class="text-sm">Simulate Payment (Dev Only)</span>
                                </label>
                            </div>
                        @endif

                        <button type="submit" class="w-full bg-white text-blue-600 font-bold py-3 rounded-lg hover:bg-blue-50 transition duration-200">
                            Proceed to Payment →
                        </button>
                    </form>
                @endif

                @if (app()->isLocal())
                    <hr class="border-blue-400 my-4">
                    
                    <p class="text-sm text-blue-100 mb-2">Test Payment (Development)</p>
                    <button onclick="simulatePayment()" 
                            class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 rounded-lg transition duration-200 text-sm">
                        Simulate Success Payment
                    </button>
                @endif
            </div>

            <!-- Test Simulator Link -->
            <div class="mt-6 bg-amber-50 border border-amber-200 rounded-lg p-4">
                <p class="text-sm font-semibold text-amber-900 mb-2">📋 Faspay Simulator</p>
                <p class="text-xs text-amber-800 mb-3">Test your payment integration using Faspay's test simulator:</p>
                <a href="https://simulator.faspay.co.id/simulator" 
                   target="_blank"
                   class="block w-full text-center bg-amber-500 hover:bg-amber-600 text-white font-semibold py-2 rounded transition duration-200 text-sm">
                    Open Simulator ↗
                </a>
            </div>
        </div>
    </div>
</div>

@if (app()->isLocal())
    <script>
        function simulatePayment() {
            if (!confirm('Simulate a successful payment for this transaction?')) {
                return;
            }

            fetch('{{ route("faspay.test-transaction.simulate", $transaction) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    payment_channel: 'QRIS (Simulated)',
                    bank_user_name: 'Test User'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Payment simulated successfully! Redirecting...');
                    window.location.href = data.redirect_url;
                } else {
                    alert('Error: ' + data.error);
                }
            })
            .catch(error => {
                alert('Error simulating payment: ' + error);
            });
        }
    </script>
@endif
@endsection
