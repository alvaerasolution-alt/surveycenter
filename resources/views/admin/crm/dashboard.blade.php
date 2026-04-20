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

    {{-- Charts Grid: Revenue + Follow-Up + Transaction --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Monthly Revenue --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-base font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i data-lucide="trending-up" class="w-5 h-5 text-orange-500"></i>
                Revenue Bulanan (6 Bulan Terakhir)
            </h2>
            <canvas id="revenueChart" height="160"></canvas>
        </div>

        {{-- Follow-Up & Transaction Status side by side --}}
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <h2 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                    <i data-lucide="phone-call" class="w-4 h-4 text-amber-500"></i>
                    Follow-Up
                </h2>
                <canvas id="followUpChart" height="180"></canvas>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <h2 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                    <i data-lucide="credit-card" class="w-4 h-4 text-emerald-500"></i>
                    Transaksi
                </h2>
                <canvas id="transactionChart" height="180"></canvas>
            </div>
        </div>
    </div>

    {{-- Manage User --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-500"></i>
                Manage User
            </h2>
            <a href="{{ route('crm.manage-users') }}" class="text-sm text-orange-600 hover:text-orange-700 font-medium flex items-center gap-1">
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
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Link Form</th>
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
                                    $latestSurvey = $latestTransaction?->survey;
                                    $legacyUserResponse = $latestSurvey?->responses?->firstWhere('input_by_admin_id', null);
                                    $latestSurveyLink = $latestSurvey?->form_link ?: $legacyUserResponse?->google_form_link;
                                @endphp
                                {{ $latestTransaction ? \Carbon\Carbon::parse($latestTransaction->created_at)->format('d M Y') : '-' }}
                            </td>
                            <td class="px-4 py-3.5">
                                @if (!empty($latestSurveyLink))
                                    <a href="{{ $latestSurveyLink }}" target="_blank" rel="noopener noreferrer"
                                       class="inline-flex items-center gap-1 rounded-lg border border-orange-200 bg-orange-50 px-2.5 py-1.5 text-xs font-medium text-orange-700 hover:bg-orange-100 transition">
                                        <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                                        Lihat URL
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-10">
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

            // Pipeline Chart (Customer Status)
            const ctxPipeline = document.getElementById('pipelineChart').getContext('2d');
            new Chart(ctxPipeline, {
                type: 'bar',
                data: {
                    labels: ['Lead', 'Prospect', 'Customer'],
                    datasets: [{
                        label: 'Jumlah',
                        data: [{{ $pipeline['lead'] }}, {{ $pipeline['prospect'] }}, {{ $pipeline['customer'] }}],
                        backgroundColor: ['#6366f1', '#818cf8', '#10b981'],
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
                        y: { beginAtZero: true, ticks: { stepSize: 1 } }
                    }
                }
            });

            // Monthly Revenue Chart
            const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
            new Chart(ctxRevenue, {
                type: 'line',
                data: {
                    labels: {!! json_encode($monthLabels) !!},
                    datasets: [{
                        label: 'Revenue (Rp)',
                        data: {!! json_encode($monthlyRevenue) !!},
                        borderColor: '#f97316',
                        backgroundColor: 'rgba(249,115,22,0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 2,
                        pointRadius: 4,
                        pointBackgroundColor: '#f97316'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            callbacks: {
                                label: function(ctx) {
                                    return 'Rp ' + ctx.parsed.y.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        x: { grid: { display: false } },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(v) { return 'Rp ' + (v/1000) + 'k'; }
                            }
                        }
                    }
                }
            });

            // Follow-Up Status Chart
            const ctxFollowUp = document.getElementById('followUpChart').getContext('2d');
            new Chart(ctxFollowUp, {
                type: 'doughnut',
                data: {
                    labels: ['Pending', 'Contacted', 'Negotiation', 'Closed'],
                    datasets: [{
                        data: [{{ $followUpStats['pending'] }}, {{ $followUpStats['contacted'] }}, {{ $followUpStats['negotiation'] }}, {{ $followUpStats['closed'] }}],
                        backgroundColor: ['#fbbf24', '#3b82f6', '#a855f7', '#10b981'],
                        borderWidth: 0,
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true,
                    cutout: '65%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { padding: 15, usePointStyle: true, pointStyle: 'circle', font: { size: 12 } }
                        }
                    }
                }
            });

            // Transaction Status Chart
            const ctxTrx = document.getElementById('transactionChart').getContext('2d');
            new Chart(ctxTrx, {
                type: 'doughnut',
                data: {
                    labels: ['Pending', 'Paid'],
                    datasets: [{
                        data: [{{ $transactionStats['pending'] }}, {{ $transactionStats['paid'] }}],
                        backgroundColor: ['#fbbf24', '#10b981'],
                        borderWidth: 0,
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true,
                    cutout: '65%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { padding: 15, usePointStyle: true, pointStyle: 'circle', font: { size: 12 } }
                        }
                    }
                }
            });
        });
    </script>
@endpush
