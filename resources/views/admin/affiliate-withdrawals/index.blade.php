@extends('layouts.admin')

@section('title', 'Withdrawal Affiliate')
@section('page-title', 'Withdrawal Affiliate')

@section('content')
<div class="max-w-5xl">
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Permintaan Withdrawal</h2>
                <p class="text-sm text-gray-500 mt-1">Kelola permintaan penarikan saldo affiliate dari user</p>
                <p class="text-xs text-blue-600 mt-1"><i data-lucide="calendar-clock" class="w-3.5 h-3.5 inline -mt-0.5"></i> Penarikan diproses setiap tanggal 6 setiap bulannya</p>
            </div>
            @if($pendingCount > 0)
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-100 text-amber-700 text-xs font-bold rounded-full">
                <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                {{ $pendingCount }} Pending
            </span>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">User</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Jumlah</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Rekening</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($withdrawals as $wd)
                    <tr class="{{ $wd->status === 'pending' ? 'bg-amber-50/30' : '' }}">
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-800">{{ $wd->user->name ?? '-' }}</p>
                            <p class="text-[11px] text-gray-400">{{ $wd->user->email ?? '-' }}</p>
                        </td>
                        <td class="px-4 py-3 font-semibold text-gray-900">
                            Rp {{ number_format($wd->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-gray-800">{{ $wd->bank_name }}</p>
                            <p class="text-[11px] text-gray-400">{{ $wd->account_number }} · {{ $wd->account_holder_name }}</p>
                        </td>
                        <td class="px-4 py-3 text-gray-500">
                            {{ $wd->created_at->format('d M Y H:i') }}
                        </td>
                        <td class="px-4 py-3">
                            @if($wd->status === 'approved')
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-green-100 text-green-700">
                                <i data-lucide="check-circle" class="w-3 h-3"></i> Disetujui
                            </span>
                            @elseif($wd->status === 'rejected')
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-red-100 text-red-700" title="{{ $wd->admin_notes }}">
                                <i data-lucide="x-circle" class="w-3 h-3"></i> Ditolak
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-700">
                                <i data-lucide="clock" class="w-3 h-3"></i> Pending
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            @if($wd->status === 'pending')
                            <div class="flex items-center justify-end gap-2">
                                <form method="POST" action="{{ route('admin.affiliate-withdrawals.approve', $wd) }}" onsubmit="return confirm('Approve withdrawal Rp {{ number_format($wd->amount, 0, ',', '.') }} untuk {{ $wd->user->name }}?')">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-green-600 text-white text-xs font-medium rounded-lg hover:bg-green-700 transition">
                                        <i data-lucide="check" class="w-3 h-3"></i> Approve
                                    </button>
                                </form>
                                <button type="button" onclick="openRejectModal({{ $wd->id }})" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-red-600 text-white text-xs font-medium rounded-lg hover:bg-red-700 transition">
                                    <i data-lucide="x" class="w-3 h-3"></i> Reject
                                </button>
                            </div>
                            @elseif($wd->processed_at)
                            <span class="text-[11px] text-gray-400">{{ $wd->processed_at->format('d M Y H:i') }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center">
                            <i data-lucide="inbox" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                            <p class="text-sm text-gray-400">Belum ada permintaan withdrawal.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($withdrawals->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $withdrawals->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Reject Modal --}}
<div id="rejectModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Reject Withdrawal</h3>
            <p class="text-sm text-gray-500">Berikan alasan penolakan (opsional)</p>
        </div>
        <form id="rejectForm" method="POST" action="">
            @csrf
            <div class="p-6">
                <textarea name="admin_notes" rows="3"
                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                    placeholder="Alasan penolakan..."></textarea>
            </div>
            <div class="px-6 py-4 bg-gray-50 flex items-center justify-end gap-3">
                <button type="button" onclick="closeRejectModal()" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 transition">Batal</button>
                <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition">
                    <i data-lucide="x" class="w-4 h-4"></i> Reject
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();
    });

    function openRejectModal(id) {
        const modal = document.getElementById('rejectModal');
        const form = document.getElementById('rejectForm');
        form.action = '/admin/affiliate-withdrawals/' + id + '/reject';
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeRejectModal() {
        const modal = document.getElementById('rejectModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endpush
@endsection
