@extends('layouts.user')

@section('title', 'Analytics Dashboard')
@section('page-title', 'Analytics')
@section('page-description', 'Analisis kinerja dan statistik survey Anda')

@section('content')
<div class="space-y-6">
    
    {{-- Key Metrics --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
        {{-- Total Surveys --}}
        <div class="bg-white rounded-xl border border-gray-200/80 p-5">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                    <i data-lucide="bar-chart-2" class="w-5 h-5 text-blue-600"></i>
                </div>
                <span class="text-xs font-medium text-gray-400">TOTAL</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $totalSurveys }}</p>
            <p class="text-xs text-gray-500 mt-1">Survey dibuat</p>
        </div>

        {{-- Total Target Responses --}}
        <div class="bg-white rounded-xl border border-gray-200/80 p-5">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center">
                    <i data-lucide="users" class="w-5 h-5 text-emerald-600"></i>
                </div>
                <span class="text-xs font-medium text-gray-400">TARGET</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $totalTargetResponses }}</p>
            <p class="text-xs text-gray-500 mt-1">Target responden</p>
        </div>

        {{-- Total Obtained Responses --}}
        <div class="bg-white rounded-xl border border-gray-200/80 p-5">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-lg bg-cyan-100 flex items-center justify-center">
                    <i data-lucide="user-check" class="w-5 h-5 text-cyan-700"></i>
                </div>
                <span class="text-xs font-medium text-gray-400">DIPEROLEH</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $totalObtainedResponses }}</p>
            <p class="text-xs text-gray-500 mt-1">Responden diperoleh</p>
        </div>

        {{-- Achievement Rate --}}
        <div class="bg-white rounded-xl border border-gray-200/80 p-5">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center">
                    <i data-lucide="target" class="w-5 h-5 text-orange-600"></i>
                </div>
                <span class="text-xs font-medium text-gray-400">CAPAIAN</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $responseAchievementRate }}%</p>
            <p class="text-xs text-gray-500 mt-1">Pencapaian target</p>
        </div>
    </div>

    {{-- Survey Status Breakdown --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-gray-200/80 p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-5 flex items-center gap-2">
                <i data-lucide="git-branch" class="w-4 h-4 text-orange-600"></i>
                Progress Tahapan
            </h3>
            <div class="space-y-4">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Tahap 1 - Create Pembayaran</span>
                        <span class="text-sm font-bold text-gray-900">{{ $stage1CompletedSurveys }}</span>
                    </div>
                    <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-blue-500" data-progress-width="{{ $totalSurveys > 0 ? ($stage1CompletedSurveys / $totalSurveys * 100) : 0 }}"></div>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Tahap 2 - Hasil</span>
                        <span class="text-sm font-bold text-gray-900">{{ $stage2CompletedSurveys }}</span>
                    </div>
                    <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-emerald-500" data-progress-width="{{ $totalSurveys > 0 ? ($stage2CompletedSurveys / $totalSurveys * 100) : 0 }}"></div>
                    </div>
                </div>
                <div class="pt-2 border-t border-gray-100"></div>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Verifikasi Pembayaran</span>
                        <span class="text-sm font-bold text-gray-900">{{ $verificationSurveys }}</span>
                    </div>
                    <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-indigo-500" data-progress-width="{{ $totalSurveys > 0 ? ($verificationSurveys / $totalSurveys * 100) : 0 }}"></div>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Survey selesai 100%</span>
                        <span class="text-sm font-bold text-gray-900">{{ $completedSurveys }}</span>
                    </div>
                    <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-amber-500" data-progress-width="{{ $totalSurveys > 0 ? ($completedSurveys / $totalSurveys * 100) : 0 }}"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Transaction Status --}}
        <div class="bg-white rounded-xl border border-gray-200/80 p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-5 flex items-center gap-2">
                <i data-lucide="credit-card" class="w-4 h-4 text-blue-600"></i>
                Status Transaksi
            </h3>
            <div class="space-y-3">
                @forelse($transactionStats as $status => $stat)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-gray-900">
                                @php
                                    $label = \App\Models\Transaction::getStatusLabel($status);
                                @endphp
                                {{ $label }}
                            </p>
                            <p class="text-xs text-gray-500">{{ $stat->count }} transaksi</p>
                        </div>
                        <p class="text-sm font-bold text-gray-900">Rp {{ number_format($stat->total ?? 0, 0, ',', '.') }}</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 text-center py-4">Belum ada transaksi</p>
                @endforelse
            </div>
        </div>

        {{-- Quick Stats --}}
        <div class="bg-white rounded-xl border border-gray-200/80 p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-5 flex items-center gap-2">
                <i data-lucide="info" class="w-4 h-4 text-purple-600"></i>
                Ringkasan Cepat
            </h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-orange-50 rounded-lg border border-orange-100">
                    <span class="text-sm text-gray-600">Rata-rata target per survey</span>
                    <span class="text-sm font-bold text-gray-900">{{ $totalSurveys > 0 ? round($totalTargetResponses / $totalSurveys) : 0 }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg border border-blue-100">
                    <span class="text-sm text-gray-600">Rata-rata diperoleh per survey</span>
                    <span class="text-sm font-bold text-gray-900">{{ $totalSurveys > 0 ? round($totalObtainedResponses / $totalSurveys) : 0 }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-indigo-50 rounded-lg border border-indigo-100">
                    <span class="text-sm text-gray-600">Biaya rata-rata per survey</span>
                    <span class="text-sm font-bold text-gray-900">Rp {{ $totalSurveys > 0 ? number_format($totalSpending / $totalSurveys, 0, ',', '.') : 0 }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-100">
                    <span class="text-sm text-gray-600">Tingkat pencapaian target</span>
                    <span class="text-sm font-bold text-gray-900">{{ $responseAchievementRate }}%</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Top Performing Surveys --}}
    @if($topSurveys->isNotEmpty())
    <div class="bg-white rounded-xl border border-gray-200/80 p-6">
        <h3 class="text-sm font-semibold text-gray-900 mb-5 flex items-center gap-2">
            <i data-lucide="trophy" class="w-4 h-4 text-amber-600"></i>
            Survey Terbaik
        </h3>
        <div class="space-y-3">
            @foreach($topSurveys as $index => $item)
            <a href="{{ route('user.surveys.show', $item['survey']) }}" class="flex items-start gap-4 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center flex-shrink-0">
                    <span class="text-sm font-bold text-amber-700">#{{ $index + 1 }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ $item['survey']->title }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i data-lucide="users" class="w-3 h-3 inline mr-1"></i>
                        Target {{ $item['target_responses'] }} • Diperoleh {{ $item['obtained_responses'] }}
                        @if($item['transaction'])
                            • Rp {{ number_format($item['transaction']->amount, 0, ',', '.') }}
                        @endif
                    </p>
                    <p class="text-[11px] mt-1 text-gray-500">
                        Tahap 1: {{ $item['stage1_done'] ? 'Selesai' : 'Menunggu Pembayaran' }} •
                        Tahap 2: {{ $item['stage2_done'] ? 'Selesai' : 'Proses' }}
                    </p>
                </div>
                <i data-lucide="arrow-right" class="w-4 h-4 text-gray-400 flex-shrink-0"></i>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Response Trends (if chart library available) --}}
    @if($responseTrends->isNotEmpty())
    <div class="bg-white rounded-xl border border-gray-200/80 p-6">
        <h3 class="text-sm font-semibold text-gray-900 mb-5 flex items-center gap-2">
            <i data-lucide="activity" class="w-4 h-4 text-green-600"></i>
            Tren Responden Diperoleh (30 Hari Terakhir)
        </h3>
        <div class="space-y-4">
            @php $maxResponses = $responseTrends->pluck('count')->max(); @endphp
            @foreach($responseTrends as $trend)
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs text-gray-600">{{ \Carbon\Carbon::parse($trend->date)->format('d M') }}</span>
                    <span class="text-xs font-bold text-gray-900">{{ $trend->count }} responden</span>
                </div>
                <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-green-500 to-emerald-500" data-progress-width="{{ $maxResponses > 0 ? ($trend->count / $maxResponses * 100) : 0 }}"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('[data-progress-width]').forEach(function(el) {
            const value = parseFloat(el.dataset.progressWidth || '0');
            const safeValue = Math.min(Math.max(value, 0), 100);
            el.style.width = safeValue + '%';
        });

        if (typeof lucide !== 'undefined') lucide.createIcons();
    });
</script>
@endpush
@endsection
