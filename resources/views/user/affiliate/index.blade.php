@extends('layouts.user')

@section('title', 'Affiliate')
@section('page-title', 'Affiliate')
@section('page-description', 'Ajak teman dan dapatkan komisi dari setiap order mereka')

@section('content')
<div class="space-y-6">

    {{-- Referral Link Card --}}
    <div class="relative overflow-hidden bg-gradient-to-br from-orange-500 via-orange-600 to-amber-700 rounded-xl p-6 text-white">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/4 blur-2xl"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-orange-400/20 rounded-full translate-y-1/2 -translate-x-1/4 blur-xl"></div>
        <div class="relative z-10">
            <div class="flex items-center gap-2 mb-3">
                <i data-lucide="link" class="w-5 h-5 text-orange-200"></i>
                <h2 class="text-lg font-bold">Link Referral Anda</h2>
            </div>
            <p class="text-sm text-orange-200 mb-4">Bagikan link ini ke teman. Setiap teman yang daftar & order, Anda dapat <strong class="text-white">{{ \App\Models\ReferralCommission::getCommissionPercent() }}% komisi</strong> dari total order!</p>

            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1 bg-white/15 backdrop-blur-sm rounded-lg px-4 py-3 flex items-center justify-between border border-white/20">
                    <code id="referralUrl" class="text-sm font-mono text-white truncate mr-3">{{ $referralUrl }}</code>
                    <button onclick="copyReferralLink()" id="copyBtn" class="flex-shrink-0 px-3 py-1.5 bg-white text-orange-700 text-xs font-bold rounded-lg hover:bg-orange-50 transition">
                        <span id="copyText">Salin</span>
                    </button>
                </div>
            </div>

            <div class="flex items-center gap-2 mt-3 text-xs text-orange-200">
                <i data-lucide="info" class="w-3.5 h-3.5"></i>
                <span>Kode referral Anda: <strong class="text-white">{{ $referralCode }}</strong></span>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
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
                    <i data-lucide="banknote" class="w-5 h-5 text-amber-600"></i>
                </div>
                <span class="text-xs font-medium text-gray-400">Total Komisi</span>
            </div>
            <p class="text-2xl font-bold text-amber-600">Rp {{ number_format($totalCommissionEarned, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500 mt-1">Total komisi diperoleh</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200/80 p-5 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                    <i data-lucide="wallet" class="w-5 h-5 text-green-600"></i>
                </div>
                <span class="text-xs font-medium text-gray-400">Saldo</span>
            </div>
            <p class="text-2xl font-bold text-green-600">Rp {{ number_format($affiliateBalance, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500 mt-1">Saldo bisa ditarik</p>
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
                <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center flex-shrink-0 text-sm font-bold text-orange-600">1</div>
                <div>
                    <p class="text-sm font-medium text-gray-800">Bagikan Link</p>
                    <p class="text-xs text-gray-500 mt-0.5">Salin link referral dan bagikan ke teman, sosial media, atau grup.</p>
                </div>
            </div>
            <div class="flex gap-3">
                <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center flex-shrink-0 text-sm font-bold text-orange-600">2</div>
                <div>
                    <p class="text-sm font-medium text-gray-800">Teman Daftar & Order</p>
                    <p class="text-xs text-gray-500 mt-0.5">Teman klik link, daftar akun, lalu membuat order survey.</p>
                </div>
            </div>
            <div class="flex gap-3">
                <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0 text-sm font-bold text-amber-600">3</div>
                <div>
                    <p class="text-sm font-medium text-gray-800">Anda Dapat Komisi</p>
                    <p class="text-xs text-gray-500 mt-0.5">Setiap order teman yang berhasil bayar, Anda mendapat {{ \App\Models\ReferralCommission::getCommissionPercent() }}% dari total order sebagai saldo yang bisa ditarik.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Withdrawal Form --}}
    <div class="bg-white rounded-xl border border-gray-200/80 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                <i data-lucide="arrow-up-circle" class="w-4 h-4 text-green-600"></i>
                Tarik Saldo
            </h2>
            <p class="text-xs text-gray-400 mt-0.5">Tarik saldo komisi Anda ke rekening bank</p>
        </div>

        {{-- Withdrawal Schedule Notice --}}
        <div class="px-5 py-3 bg-blue-50 border-b border-blue-100">
            <div class="flex items-start gap-2">
                <i data-lucide="calendar-clock" class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0"></i>
                <div>
                    <p class="text-xs font-medium text-blue-800">Penarikan diproses setiap tanggal 6</p>
                    <p class="text-[11px] text-blue-600 mt-0.5">Anda dapat mengajukan penarikan kapan saja. Seluruh pengajuan akan diproses pada tanggal 6 setiap bulannya.</p>
                </div>
            </div>
        </div>
        <div class="p-5">
            @if($affiliateBalance >= 100000)
            <form method="POST" action="{{ route('user.affiliate.withdraw') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Jumlah Penarikan (Rp)</label>
                        <input type="number" name="amount" value="{{ old('amount') }}" required min="100000" max="{{ $affiliateBalance }}"
                            class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('amount') border-red-300 @enderror"
                            placeholder="Minimal Rp 100.000">
                        @error('amount')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                        <p class="text-xs text-gray-400 mt-1">Saldo tersedia: <strong class="text-green-600">Rp {{ number_format($affiliateBalance, 0, ',', '.') }}</strong></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Nama Bank</label>
                        <input type="text" name="bank_name" value="{{ old('bank_name') }}" required
                            class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                            placeholder="Contoh: BCA, BNI, Mandiri">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Nomor Rekening</label>
                        <input type="text" name="account_number" value="{{ old('account_number') }}" required
                            class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                            placeholder="Masukkan nomor rekening">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Nama Pemilik Rekening</label>
                        <input type="text" name="account_holder_name" value="{{ old('account_holder_name') }}" required
                            class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                            placeholder="Sesuai buku rekening">
                    </div>
                </div>
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition shadow-sm">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    Ajukan Penarikan
                </button>
            </form>
            @else
            <div class="text-center py-4">
                <i data-lucide="wallet" class="w-10 h-10 text-gray-300 mx-auto mb-2"></i>
                <p class="text-sm text-gray-500">Saldo belum mencukupi untuk penarikan.</p>
                <p class="text-xs text-gray-400 mt-1">Minimal penarikan Rp 100.000. Saldo Anda: <strong>Rp {{ number_format($affiliateBalance, 0, ',', '.') }}</strong></p>
            </div>
            @endif

            @if($pendingWithdrawal > 0)
            <div class="mt-4 bg-amber-50 border border-amber-200 rounded-lg px-4 py-3">
                <p class="text-xs text-amber-700 font-medium">
                    <i data-lucide="clock" class="w-3.5 h-3.5 inline -mt-0.5"></i>
                    Anda memiliki penarikan pending sebesar <strong>Rp {{ number_format($pendingWithdrawal, 0, ',', '.') }}</strong>.
                </p>
                <p class="text-[11px] text-amber-600 mt-1">Penarikan akan diproses pada tanggal 6 setiap bulannya.</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Three Column Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Referral List --}}
        <div class="bg-white rounded-xl border border-gray-200/80 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-900">Teman Terdaftar</h2>
                <p class="text-xs text-gray-400 mt-0.5">User dari link referral Anda</p>
            </div>
            <div class="divide-y divide-gray-100 max-h-80 overflow-y-auto">
                @forelse($referrals as $ref)
                <div class="px-5 py-3 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center">
                            <i data-lucide="user" class="w-4 h-4 text-gray-500"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $ref->name }}</p>
                            <p class="text-[11px] text-gray-400">{{ $ref->created_at->format('d M Y') }}</p>
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
                    <p class="text-sm text-gray-400">Belum ada teman terdaftar.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Commission History --}}
        <div class="bg-white rounded-xl border border-gray-200/80 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-900">Riwayat Komisi</h2>
                <p class="text-xs text-gray-400 mt-0.5">Komisi dari order teman</p>
            </div>
            <div class="divide-y divide-gray-100 max-h-80 overflow-y-auto">
                @forelse($commissions as $com)
                <div class="px-5 py-3 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center">
                            <i data-lucide="banknote" class="w-4 h-4 text-amber-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-800">
                                <strong>{{ $com->referredUser->name ?? 'User' }}</strong>
                            </p>
                            <p class="text-[11px] text-gray-400">
                                Order Rp {{ number_format($com->transaction->amount ?? 0, 0, ',', '.') }}
                                ({{ $com->commission_percent }}%)
                                · {{ $com->created_at->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                    <span class="text-sm font-bold text-green-600">+Rp {{ number_format($com->commission_amount, 0, ',', '.') }}</span>
                </div>
                @empty
                <div class="px-5 py-8 text-center">
                    <i data-lucide="gift" class="w-10 h-10 text-gray-300 mx-auto mb-2"></i>
                    <p class="text-sm text-gray-400">Belum ada komisi.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Withdrawal History --}}
        <div class="bg-white rounded-xl border border-gray-200/80 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-900">Riwayat Penarikan</h2>
                <p class="text-xs text-gray-400 mt-0.5">Status penarikan saldo Anda · Diproses tanggal 6 setiap bulan</p>
            </div>
            <div class="divide-y divide-gray-100 max-h-80 overflow-y-auto">
                @forelse($withdrawals as $wd)
                <div class="px-5 py-3 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-800">Rp {{ number_format($wd->amount, 0, ',', '.') }}</p>
                        <p class="text-[11px] text-gray-400">{{ $wd->bank_name }} · {{ $wd->account_number }}</p>
                        <p class="text-[11px] text-gray-400">{{ $wd->created_at->format('d M Y H:i') }}</p>
                    </div>
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
                </div>
                @empty
                <div class="px-5 py-8 text-center">
                    <i data-lucide="arrow-up-circle" class="w-10 h-10 text-gray-300 mx-auto mb-2"></i>
                    <p class="text-sm text-gray-400">Belum ada penarikan.</p>
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
        btn.classList.remove('bg-white', 'text-orange-700');
        btn.classList.add('bg-emerald-500', 'text-white');
        setTimeout(function() {
            text.textContent = 'Salin';
            btn.classList.remove('bg-emerald-500', 'text-white');
            btn.classList.add('bg-white', 'text-orange-700');
        }, 2000);
    });
}
</script>
@endsection
