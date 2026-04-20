@extends('layouts.crm')

@section('title', 'Client Dashboard')
@section('page-title', 'Klien')

@section('content')
<div class="space-y-8">

    {{-- Client Management Header --}}
    <div class="text-center py-4">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Kelola Klien</h2>
        <p class="text-sm text-gray-500">Pilih kategori klien yang ingin dikelola</p>
    </div>

    {{-- Quick Navigation Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-2xl mx-auto">
        <a href="{{ route('followups.index') }}"
           class="group relative overflow-hidden bg-white rounded-xl border border-gray-200 p-6 hover:border-amber-300 hover:shadow-lg hover:shadow-amber-100/50 transition-all duration-300">
            <div class="absolute top-0 right-0 w-20 h-20 bg-amber-50 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:bg-amber-100 transition"></div>
            <div class="relative z-10">
                <div class="w-11 h-11 rounded-xl bg-amber-50 group-hover:bg-amber-100 flex items-center justify-center mb-4 transition">
                    <i data-lucide="phone-call" class="w-5 h-5 text-amber-600"></i>
                </div>
                <h3 class="text-base font-semibold text-gray-900 mb-1">Customer Follow-Up</h3>
                <p class="text-xs text-gray-500">Kelola follow-up dan prospek klien baru</p>
                <div class="mt-3 flex items-center gap-1 text-amber-600 text-sm font-medium group-hover:gap-2 transition-all">
                    <span>Buka</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </div>
            </div>
        </a>

        <a href="{{ route('crm.manage-users') }}"
           class="group relative overflow-hidden bg-white rounded-xl border border-gray-200 p-6 hover:border-emerald-300 hover:shadow-lg hover:shadow-emerald-100/50 transition-all duration-300">
            <div class="absolute top-0 right-0 w-20 h-20 bg-emerald-50 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:bg-emerald-100 transition"></div>
            <div class="relative z-10">
                <div class="w-11 h-11 rounded-xl bg-emerald-50 group-hover:bg-emerald-100 flex items-center justify-center mb-4 transition">
                    <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600"></i>
                </div>
                <h3 class="text-base font-semibold text-gray-900 mb-1">Manage User</h3>
                <p class="text-xs text-gray-500">Daftar semua user dan login sebagai user</p>
                <div class="mt-3 flex items-center gap-1 text-emerald-600 text-sm font-medium group-hover:gap-2 transition-all">
                    <span>Buka</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </div>
            </div>
        </a>
    </div>

    {{-- Follow-Up Terbaru --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                <i data-lucide="phone-call" class="w-5 h-5 text-amber-500"></i>
                Follow-Up Terbaru
            </h2>
            <a href="{{ route('followups.index') }}" class="text-sm text-orange-600 hover:text-orange-700 font-medium flex items-center gap-1">
                Lihat Semua <i data-lucide="arrow-right" class="w-4 h-4"></i>
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

    {{-- Manage User --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-500"></i>
                Manage User
            </h2>
            <a href="{{ route('crm.manage-users') }}" class="text-sm text-orange-600 hover:text-orange-700 font-medium flex items-center gap-1">
                Lihat Semua <i data-lucide="arrow-right" class="w-4 h-4"></i>
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
                    @forelse ($customerAlready as $user)
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
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();
    });
</script>
@endpush
