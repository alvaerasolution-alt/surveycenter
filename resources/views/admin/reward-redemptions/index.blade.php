@extends('layouts.admin')

@section('title', 'Penukaran Poin')
@section('page-title', 'Penukaran Poin')

@section('content')
<div class="max-w-6xl">

    @if(session('success'))
    <div class="mb-4 flex items-start gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm">
        <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Riwayat Penukaran Poin</h2>
                <p class="text-sm text-gray-500 mt-1">Kelola permintaan penukaran poin dari user</p>
            </div>

            {{-- Filter --}}
            <form method="GET" class="flex items-center gap-2">
                <select name="status" onchange="this.form.submit()"
                    class="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-gray-50 focus:ring-2 focus:ring-orange-400 focus:border-transparent outline-none">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
                    <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Diproses</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">User</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Item</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Poin</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">No. HP</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($redemptions as $rd)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-gray-500 font-mono text-xs">#{{ $rd->id }}</td>
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-800">{{ $rd->user->name ?? '-' }}</p>
                            <p class="text-[11px] text-gray-400">{{ $rd->user->email ?? '' }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-800">{{ $rd->rewardItem->name ?? '-' }}</p>
                            <p class="text-[11px] text-gray-400">{{ $rd->rewardItem ? \App\Models\RewardItem::getCategoryLabel($rd->rewardItem->category) : '' }}</p>
                        </td>
                        <td class="px-4 py-3 font-semibold text-amber-600">
                            {{ number_format($rd->points_spent, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-gray-600 font-mono text-xs">
                            {{ $rd->phone_number ?? '-' }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold {{ \App\Models\RewardRedemption::getStatusBadgeClass($rd->status) }}">
                                {{ \App\Models\RewardRedemption::getStatusLabel($rd->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-500">
                            {{ $rd->created_at->format('d M Y') }}<br>
                            <span class="text-gray-400">{{ $rd->created_at->format('H:i') }}</span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <button type="button" onclick="openStatusModal({{ $rd->id }}, '{{ $rd->status }}', '{{ addslashes($rd->admin_notes ?? '') }}')"
                                class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-gray-100 text-gray-700 text-xs font-medium rounded-lg hover:bg-gray-200 transition">
                                <i data-lucide="settings-2" class="w-3 h-3"></i> Ubah Status
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-12 text-center">
                            <i data-lucide="inbox" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                            <p class="text-sm text-gray-400">Belum ada penukaran poin.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($redemptions->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $redemptions->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Update Status Modal --}}
<div id="statusModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-sm p-6" onclick="event.stopPropagation()">
        <h3 class="text-base font-bold text-gray-900 mb-4">Ubah Status Penukaran</h3>
        <form id="statusForm" method="POST">
            @csrf
            @method('PATCH')

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                <select name="status" id="statusSelect"
                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-orange-400 focus:border-transparent outline-none">
                    <option value="pending">Menunggu</option>
                    <option value="processing">Diproses</option>
                    <option value="completed">Selesai</option>
                    <option value="rejected">Ditolak</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Catatan Admin</label>
                <textarea name="admin_notes" id="adminNotes" rows="2"
                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-orange-400 focus:border-transparent outline-none resize-none"
                    placeholder="Catatan opsional..."></textarea>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeStatusModal()"
                    class="flex-1 px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-2 text-sm font-bold text-white bg-orange-500 rounded-lg hover:bg-orange-600 transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openStatusModal(id, status, notes) {
    document.getElementById('statusForm').action = '{{ url("admin/reward-redemptions") }}/' + id + '/status';
    document.getElementById('statusSelect').value = status;
    document.getElementById('adminNotes').value = notes;
    const m = document.getElementById('statusModal');
    m.classList.remove('hidden'); m.classList.add('flex');
}
function closeStatusModal() {
    const m = document.getElementById('statusModal');
    m.classList.add('hidden'); m.classList.remove('flex');
}
document.getElementById('statusModal').addEventListener('click', function(e) {
    if (e.target === this) closeStatusModal();
});
</script>
@endsection
