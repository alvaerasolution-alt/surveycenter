@extends('layouts.crm')

@section('title', 'Dashboard CRM')
@section('page-title', 'Dashboard CRM')

@section('content')
<div class="space-y-8">

    {{-- Welcome Banner --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-600 via-blue-700 to-orange-700 text-white p-6 sm:p-8">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4"></div>
        <div class="absolute bottom-0 left-0 w-40 h-40 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/4"></div>
        <div class="relative z-10">
            <h1 class="text-2xl sm:text-3xl font-bold">Dashboard CRM 📊</h1>
            <p class="mt-2 text-blue-100 text-sm sm:text-base">Monitor pipeline, customer, dan follow-up dari sini.</p>
        </div>
    </div>

    {{-- Stats Section --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach ($stats as $stat)
            <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md hover:border-orange-200 transition-all duration-200">
                <p class="text-sm text-gray-500 font-medium">{{ $stat['title'] }}</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stat['value'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- Pipeline Overview --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="text-base font-semibold text-gray-900 mb-4">Tinjauan Pipeline</h2>
        <canvas id="pipelineChart" height="120"></canvas>
    </div>

    {{-- Customer Sudah Bayar --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-500"></i>
                Customer Sudah Bayar
            </h2>
            <a href="{{ route('crm.customer-already') }}" class="text-sm text-orange-600 hover:text-orange-700 font-medium flex items-center gap-1">
                Lihat Semua
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Transaksi</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Terakhir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($customerAlready->take(10) as $user)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3.5 font-medium text-gray-900">{{ $user->name }}</td>
                            <td class="px-4 py-3.5 text-gray-600">{{ $user->email }}</td>
                            <td class="px-4 py-3.5">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                    {{ $user->transactions->count() }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 font-semibold text-emerald-600">
                                Rp {{ number_format($user->transactions->sum('amount'), 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3.5 text-gray-500 text-xs">
                                @php
                                    $latestTransaction = $user->transactions->sortByDesc('created_at')->first();
                                @endphp
                                {{ $latestTransaction ? \Carbon\Carbon::parse($latestTransaction->created_at)->format('d M Y') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-10">
                                <i data-lucide="inbox" class="w-8 h-8 text-gray-300 mx-auto mb-2"></i>
                                <p class="text-sm text-gray-500">Belum ada customer yang melakukan pembayaran</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Follow-Up Terbaru --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                <i data-lucide="phone-call" class="w-5 h-5 text-amber-500"></i>
                Follow-Up Terbaru
            </h2>
            <a href="{{ route('followups.index') }}" class="text-sm text-orange-600 hover:text-orange-700 font-medium flex items-center gap-1">
                Lihat Semua
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($followUps as $followup)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3.5 font-medium text-gray-900">{{ $followup->customer->full_name }}</td>
                            <td class="px-4 py-3.5 text-gray-600">{{ $followup->customer->email ?? '-' }}</td>
                            <td class="px-4 py-3.5 text-gray-500 text-xs">
                                {{ \Carbon\Carbon::parse($followup->follow_up_date)->format('d M Y H:i') }}
                            </td>
                            <td class="px-4 py-3.5">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'contacted' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'negotiation' => 'bg-purple-50 text-purple-700 border-purple-200',
                                    ];
                                    $color = $statusColors[$followup->status] ?? 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $color }}">
                                    {{ ucfirst($followup->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-gray-500 text-xs">{{ Str::limit($followup->note, 30) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-10">
                                <i data-lucide="inbox" class="w-8 h-8 text-gray-300 mx-auto mb-2"></i>
                                <p class="text-sm text-gray-500">Tidak ada follow-up</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') lucide.createIcons();

            const ctx = document.getElementById('pipelineChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Lead', 'Terkualifikasi', 'Proposal', 'Selesai'],
                    datasets: [{
                        label: 'Jumlah',
                        data: [40, 30, 20, 15],
                        backgroundColor: ['#6366f1', '#818cf8', '#34d399', '#10b981'],
                        borderRadius: 8,
                        barThickness: 50
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            titleColor: '#e2e8f0',
                            bodyColor: '#f8fafc',
                            padding: 10,
                            borderWidth: 1,
                            borderColor: '#6366f1'
                        }
                    },
                    scales: {
                        x: { grid: { display: false } },
                        y: { beginAtZero: true }
                    }
                }
            });
        });
    </script>
@endpush
