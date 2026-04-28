@extends('layouts.user')

@section('title', 'Affiliate')
@section('page-title', 'Affiliate')
@section('page-description', 'Ajak teman dan dapatkan komisi poin dari setiap order mereka')

@section('content')
<div class="space-y-6">

    {{-- Referral Link Card --}}
    <div class="relative overflow-hidden bg-gradient-to-br from-indigo-500 via-purple-600 to-indigo-700 rounded-xl p-6 text-white">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/4 blur-2xl"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-purple-400/20 rounded-full translate-y-1/2 -translate-x-1/4 blur-xl"></div>
        <div class="relative z-10">
            <div class="flex items-center gap-2 mb-3">
                <i data-lucide="link" class="w-5 h-5 text-purple-200"></i>
                <h2 class="text-lg font-bold">Link Referral Anda</h2>
            </div>
            <p class="text-sm text-purple-200 mb-4">Bagikan link ini ke teman. Setiap teman yang daftar & order, Anda dapat <strong class="text-white">{{ number_format(\App\Models\ReferralCommission::getCommissionPoints(), 0, ',', '.') }} Poin</strong>!</p>

            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1 bg-white/15 backdrop-blur-sm rounded-lg px-4 py-3 flex items-center justify-between border border-white/20">
                    <code id="referralUrl" class="text-sm font-mono text-white truncate mr-3">{{ $referralUrl }}</code>
                    <button onclick="copyReferralLink()" id="copyBtn" class="flex-shrink-0 px-3 py-1.5 bg-white text-purple-700 text-xs font-bold rounded-lg hover:bg-purple-50 transition">
                        <span id="copyText">Salin</span>
                    </button>
                </div>
            </div>

            <div class="flex items-center gap-2 mt-3 text-xs text-purple-200">
                <i data-lucide="info" class="w-3.5 h-3.5"></i>
                <span>Kode referral Anda: <strong class="text-white">{{ $referralCode }}</strong></span>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-gray-200/80 p-5 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                    <i data-lucide="users" class="w-5 h-5 text-blue-600"></i>
                </div>
                <span class="text-xs font-medium text-gray-400">Referral</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $totalReferrals }}</p>
            <p class="text-xs text-gray-500 mt-1">Teman terdaftar</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200/80 p-5 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center">
                    <i data-lucide="shopping-bag" class="w-5 h-5 text-emerald-600"></i>
                </div>
                <span class="text-xs font-medium text-gray-400">Konversi</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $totalWithOrders }}</p>
            <p class="text-xs text-gray-500 mt-1">Teman sudah order</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200/80 p-5 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center">
                    <i data-lucide="coins" class="w-5 h-5 text-amber-600"></i>
                </div>
                <span class="text-xs font-medium text-gray-400">Total</span>
            </div>
            <p class="text-2xl font-bold text-amber-600">{{ number_format($totalCommissionPoints, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500 mt-1">Poin komisi diperoleh</p>
        </div>
    </div>

    {{-- How It Works --}}
    <div class="bg-white rounded-xl border border-gray-200/80 p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
            <i data-lucide="help-circle" class="w-4 h-4 text-gray-400"></i>
            Cara Kerja Affiliate
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="flex gap-3">
                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0 text-sm font-bold text-indigo-600">1</div>
                <div>
                    <p class="text-sm font-medium text-gray-800">Bagikan Link</p>
                    <p class="text-xs text-gray-500 mt-0.5">Salin link referral dan bagikan ke teman, sosial media, atau grup.</p>
                </div>
            </div>
            <div class="flex gap-3">
                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0 text-sm font-bold text-indigo-600">2</div>
                <div>
                    <p class="text-sm font-medium text-gray-800">Teman Daftar & Order</p>
                    <p class="text-xs text-gray-500 mt-0.5">Teman klik link, daftar akun, lalu membuat order survey.</p>
                </div>
            </div>
            <div class="flex gap-3">
                <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0 text-sm font-bold text-amber-600">3</div>
                <div>
                    <p class="text-sm font-medium text-gray-800">Anda Dapat Poin</p>
                    <p class="text-xs text-gray-500 mt-0.5">Setiap order teman yang berhasil bayar, Anda mendapat {{ number_format(\App\Models\ReferralCommission::getCommissionPoints(), 0, ',', '.') }} poin.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Two Column Grid: Referral List + Commission History --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Referral List --}}
        <div class="bg-white rounded-xl border border-gray-200/80 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-900">Teman yang Terdaftar</h2>
                <p class="text-xs text-gray-400 mt-0.5">User yang mendaftar melalui link referral Anda</p>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($referrals as $ref)
                <div class="px-5 py-3 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center">
                            <i data-lucide="user" class="w-4 h-4 text-gray-500"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $ref->name }}</p>
                            <p class="text-[11px] text-gray-400">Daftar {{ $ref->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                    @if($ref->paid_orders > 0)
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-700">
                        <i data-lucide="check" class="w-3 h-3"></i>
                        {{ $ref->paid_orders }} order
                    </span>
                    @else
                    <span class="text-[11px] text-gray-400 font-medium">Belum order</span>
                    @endif
                </div>
                @empty
                <div class="px-5 py-8 text-center">
                    <i data-lucide="user-plus" class="w-10 h-10 text-gray-300 mx-auto mb-2"></i>
                    <p class="text-sm text-gray-400">Belum ada teman yang terdaftar.</p>
                    <p class="text-xs text-gray-400 mt-1">Bagikan link referral Anda!</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Commission History --}}
        <div class="bg-white rounded-xl border border-gray-200/80 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-900">Riwayat Komisi</h2>
                <p class="text-xs text-gray-400 mt-0.5">Poin yang Anda terima dari order teman</p>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($commissions as $com)
                <div class="px-5 py-3 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center">
                            <i data-lucide="coins" class="w-4 h-4 text-amber-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-800">
                                Komisi dari <strong>{{ $com->referredUser->name ?? 'User' }}</strong>
                            </p>
                            <p class="text-[11px] text-gray-400">
                                Transaksi Rp {{ number_format($com->transaction->amount ?? 0, 0, ',', '.') }}
                                · {{ $com->created_at->format('d M Y H:i') }}
                            </p>
                        </div>
                    </div>
                    <span class="text-sm font-bold text-amber-600">+{{ number_format($com->points_earned, 0, ',', '.') }}</span>
                </div>
                @empty
                <div class="px-5 py-8 text-center">
                    <i data-lucide="gift" class="w-10 h-10 text-gray-300 mx-auto mb-2"></i>
                    <p class="text-sm text-gray-400">Belum ada komisi diterima.</p>
                    <p class="text-xs text-gray-400 mt-1">Ajak teman untuk daftar & order!</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
function copyReferralLink() {
    const url = document.getElementById('referralUrl').textContent;
    const btn = document.getElementById('copyBtn');
    const text = document.getElementById('copyText');

    navigator.clipboard.writeText(url).then(function() {
        text.textContent = 'Tersalin!';
        btn.classList.remove('bg-white', 'text-purple-700');
        btn.classList.add('bg-emerald-500', 'text-white');
        setTimeout(function() {
            text.textContent = 'Salin';
            btn.classList.remove('bg-emerald-500', 'text-white');
            btn.classList.add('bg-white', 'text-purple-700');
        }, 2000);
    });
}
</script>
@endsection
