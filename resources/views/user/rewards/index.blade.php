@extends('layouts.user')

@section('title', 'Reward & Poin')
@section('page-title', 'Reward & Poin')
@section('page-description', 'Kumpulkan poin dari setiap transaksi dan tukarkan dengan hadiah menarik')

@section('content')
<div class="space-y-6">

    {{-- Alert Messages --}}
    @if(session('success'))
    <div class="flex items-start gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm">
        <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
        <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    {{-- Points Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        {{-- Point Balance --}}
        <div class="relative overflow-hidden bg-gradient-to-br from-orange-500 via-orange-600 to-amber-600 rounded-xl p-6 text-white">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/4 blur-xl"></div>
            <div class="relative">
                <div class="flex items-center gap-2 mb-2">
                    <i data-lucide="coins" class="w-5 h-5 text-orange-200"></i>
                    <p class="text-sm font-medium text-orange-100">Saldo Poin</p>
                </div>
                <p class="text-3xl font-extrabold">{{ number_format($pointBalance, 0, ',', '.') }}</p>
                <p class="text-xs text-orange-200 mt-1">poin tersedia</p>
            </div>
        </div>

        {{-- Total Earned --}}
        <div class="bg-white rounded-xl border border-gray-200/80 p-6">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                    <i data-lucide="trending-up" class="w-4 h-4 text-emerald-600"></i>
                </div>
                <p class="text-sm font-medium text-gray-500">Total Poin Diperoleh</p>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($totalEarned, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-1">sepanjang waktu</p>
        </div>

        {{-- Info Card --}}
        <div class="bg-white rounded-xl border border-gray-200/80 p-6">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                    <i data-lucide="info" class="w-4 h-4 text-blue-600"></i>
                </div>
                <p class="text-sm font-medium text-gray-500">Cara Dapat Poin</p>
            </div>
            <p class="text-sm text-gray-700 font-semibold">Rp 1.000 = 1 Poin</p>
            <p class="text-xs text-gray-400 mt-1">Otomatis dari setiap transaksi berhasil</p>
        </div>
    </div>

    {{-- Reward Catalog --}}
    <div class="bg-white rounded-xl border border-gray-200/80 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                <i data-lucide="gift" class="w-5 h-5 text-orange-500"></i>
                Tukar Poin
            </h2>
            <p class="text-xs text-gray-500 mt-1">Pilih hadiah yang ingin ditukar dengan poin Anda</p>
        </div>

        @forelse($rewardItems as $category => $items)
        <div class="px-6 py-4 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
            <div class="flex items-center gap-2 mb-3">
                <i data-lucide="{{ \App\Models\RewardItem::getCategoryIcon($category) }}" class="w-4 h-4 text-gray-500"></i>
                <h3 class="text-sm font-semibold text-gray-700">{{ \App\Models\RewardItem::getCategoryLabel($category) }}</h3>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($items as $item)
                <div class="border border-gray-200 rounded-lg p-4 hover:border-orange-300 hover:shadow-sm transition {{ $pointBalance >= $item->points_cost ? '' : 'opacity-60' }}">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ $item->name }}</p>
                            @if($item->description)
                            <p class="text-xs text-gray-500 mt-0.5">{{ $item->description }}</p>
                            @endif
                        </div>
                        @if($item->value)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-orange-100 text-orange-700">
                            {{ $item->value }}
                        </span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between mt-3">
                        <div class="flex items-center gap-1">
                            <i data-lucide="coins" class="w-3.5 h-3.5 text-amber-500"></i>
                            <span class="text-sm font-bold text-amber-600">{{ number_format($item->points_cost, 0, ',', '.') }}</span>
                            <span class="text-xs text-gray-400">poin</span>
                        </div>

                        @if($pointBalance >= $item->points_cost)
                            <button type="button"
                                onclick="openRedeemModal({{ $item->id }}, '{{ addslashes($item->name) }}', {{ $item->points_cost }}, '{{ $item->category }}')"
                                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-white bg-orange-500 rounded-lg hover:bg-orange-600 transition">
                                <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                Tukar
                            </button>
                        @else
                        <span class="text-xs text-gray-400 font-medium">Poin kurang</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @empty
        <div class="px-6 py-12 text-center">
            <i data-lucide="package-x" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
            <p class="text-sm text-gray-500">Belum ada reward yang tersedia saat ini.</p>
            <p class="text-xs text-gray-400 mt-1">Nantikan penawaran menarik dari kami!</p>
        </div>
        @endforelse
    </div>

    {{-- Two Column: Point History + Redemption History --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Point History --}}
        <div class="bg-white rounded-xl border border-gray-200/80 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-900">Riwayat Poin</h2>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($recentPointHistory as $pt)
                <div class="px-5 py-3 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ $pt->type === 'earn' ? 'bg-emerald-100' : 'bg-red-100' }}">
                            <i data-lucide="{{ $pt->type === 'earn' ? 'plus' : 'minus' }}" class="w-4 h-4 {{ $pt->type === 'earn' ? 'text-emerald-600' : 'text-red-600' }}"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-800">{{ $pt->description }}</p>
                            <p class="text-[11px] text-gray-400">{{ $pt->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                    <span class="text-sm font-bold {{ $pt->type === 'earn' ? 'text-emerald-600' : 'text-red-600' }}">
                        {{ $pt->type === 'earn' ? '+' : '-' }}{{ number_format($pt->points, 0, ',', '.') }}
                    </span>
                </div>
                @empty
                <div class="px-5 py-8 text-center">
                    <p class="text-sm text-gray-400">Belum ada riwayat poin.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Redemption History --}}
        <div class="bg-white rounded-xl border border-gray-200/80 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-900">Riwayat Penukaran</h2>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($redemptions as $rd)
                <div class="px-5 py-3 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-orange-100 flex items-center justify-center">
                            <i data-lucide="gift" class="w-4 h-4 text-orange-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-800">{{ $rd->rewardItem->name ?? 'Reward' }}</p>
                            <p class="text-[11px] text-gray-400">{{ $rd->created_at->format('d M Y H:i') }} · {{ number_format($rd->points_spent, 0, ',', '.') }} poin</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold {{ \App\Models\RewardRedemption::getStatusBadgeClass($rd->status) }}">
                        {{ \App\Models\RewardRedemption::getStatusLabel($rd->status) }}
                    </span>
                </div>
                @empty
                <div class="px-5 py-8 text-center">
                    <p class="text-sm text-gray-400">Belum ada penukaran reward.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Redeem Modal --}}
<div id="redeemModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-sm p-6">
        <h3 class="text-base font-bold text-gray-900 mb-1">Tukar Poin</h3>
        <p id="redeemModalDesc" class="text-sm text-gray-600 mb-4"></p>
        <form id="redeemForm" method="POST">
            @csrf
            <div class="mb-4">
                <label id="redeemPhoneLabel" class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                <input type="text" name="phone_number" id="redeemPhone"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-orange-400 focus:border-transparent outline-none"
                    placeholder="Contoh: Dana 08123xxx / Mandiri 123xxx">
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeRedeemModal()" class="flex-1 px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                    Batal
                </button>
                <button type="submit" class="flex-1 px-4 py-2 text-sm font-bold text-white bg-orange-500 rounded-lg hover:bg-orange-600 transition">
                    Konfirmasi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openRedeemModal(itemId, itemName, pointsCost, category) {
    const modal = document.getElementById('redeemModal');
    const desc = document.getElementById('redeemModalDesc');
    const form = document.getElementById('redeemForm');
    const phoneInput = document.getElementById('redeemPhone');
    const phoneLabel = document.getElementById('redeemPhoneLabel');

    desc.textContent = 'Tukar ' + pointsCost.toLocaleString('id-ID') + ' poin untuk ' + itemName + '?';
    form.action = '{{ url("rewards") }}/' + itemId + '/redeem';
    phoneInput.value = '';

    if (category === 'tunai') {
        phoneLabel.innerHTML = 'Nomor Rekening / E-Wallet <span class="text-red-500">*</span>';
        phoneInput.required = true;
        phoneInput.placeholder = 'Contoh: Dana 08123xxx / BCA 123xxx';
    } else {
        phoneLabel.innerHTML = 'Keterangan <span class="text-gray-400 font-normal">(Opsional)</span>';
        phoneInput.required = false;
        phoneInput.placeholder = 'Catatan tambahan (opsional)';
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    phoneInput.focus();
}

function closeRedeemModal() {
    const modal = document.getElementById('redeemModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

document.getElementById('redeemModal').addEventListener('click', function(e) {
    if (e.target === this) closeRedeemModal();
});
</script>
@endsection
