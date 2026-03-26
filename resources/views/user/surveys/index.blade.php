@extends('layouts.user')

@section('title', 'Survey Saya')
@section('page-title', 'Survey Saya')
@section('page-description', 'Kelola semua survey yang Anda buat')

@section('content')
<div class="space-y-6">

    {{-- Header Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">Daftar Survey</h2>
            <p class="text-sm text-gray-500">Total {{ $surveys->total() }} survey</p>
        </div>
        <a href="{{ route('user.surveys.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-orange-600 text-white rounded-lg font-medium text-sm hover:bg-orange-700 transition shadow-sm">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Buat Survey Baru
        </a>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl border border-gray-200/80 p-4">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <div class="relative">
                    <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari survey..." 
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none">
                </div>
            </div>
            <div class="flex gap-2">
                <select name="status" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none">
                    <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>Dalam Proses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                </select>
                <button type="submit" class="px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition">
                    Filter
                </button>
            </div>
        </form>
    </div>

    {{-- Survey List --}}
    <div class="bg-white rounded-xl border border-gray-200/80 overflow-hidden">
        @if($surveys->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Survey</th>
                            <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Pertanyaan</th>
                            <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Responden</th>
                            <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Progress</th>
                            <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($surveys as $survey)
                            @php
                                $latestTransaction = $survey->transactions->first();
                                $progress = $latestTransaction->progress ?? 0;
                                $status = $latestTransaction->status ?? 'pending';
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-orange-100 to-amber-100 flex items-center justify-center flex-shrink-0">
                                            <i data-lucide="file-text" class="w-5 h-5 text-orange-600"></i>
                                        </div>
                                        <div>
                                            <a href="{{ route('user.surveys.show', $survey) }}" class="text-sm font-medium text-gray-900 hover:text-orange-600">
                                                {{ $survey->title }}
                                            </a>
                                            <p class="text-xs text-gray-500">{{ $survey->created_at->format('d M Y') }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <span class="text-sm font-medium text-gray-900">{{ $survey->question_count }}</span>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <span class="text-sm font-medium text-gray-900">{{ $survey->responses_count }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <div class="w-20 h-2 bg-gray-200 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full transition-all
                                                @if($progress >= 100) bg-emerald-500
                                                @elseif($progress > 0) bg-blue-500
                                                @else bg-gray-300
                                                @endif"
                                                style="width: {{ $progress }}%"></div>
                                        </div>
                                        <span class="text-xs font-medium text-gray-600">{{ $progress }}%</span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                        @if($status === 'paid') bg-emerald-100 text-emerald-700
                                        @elseif($status === 'pending') bg-amber-100 text-amber-700
                                        @else bg-red-100 text-red-700
                                        @endif">
                                        @if($status === 'paid') Dibayar
                                        @elseif($status === 'pending') Pending
                                        @else {{ ucfirst($status) }}
                                        @endif
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('user.surveys.show', $survey) }}" class="p-2 text-gray-400 hover:text-orange-600 hover:bg-orange-50 rounded-lg transition" title="Lihat Detail">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </a>
                                        @if($status === 'pending')
                                            <form method="POST" action="{{ route('user.surveys.destroy', $survey) }}" onsubmit="return confirm('Yakin ingin menghapus survey ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($surveys->hasPages())
                <div class="px-5 py-4 border-t border-gray-100">
                    {{ $surveys->withQueryString()->links() }}
                </div>
            @endif
        @else
            <div class="px-5 py-16 text-center">
                <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="clipboard-list" class="w-8 h-8 text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-1">Belum ada survey</h3>
                <p class="text-sm text-gray-500 mb-4">Mulai buat survey pertama Anda sekarang</p>
                <a href="{{ route('user.surveys.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-orange-600 text-white rounded-lg font-medium text-sm hover:bg-orange-700 transition">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Buat Survey Baru
                </a>
            </div>
        @endif
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
