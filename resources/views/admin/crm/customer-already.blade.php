@extends('layouts.crm')

@section('title', 'Customer Sudah Bayar')
@section('page-title', 'Customer Sudah Bayar')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 flex items-center gap-2">
                    <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-500"></i>
                    Customer Sudah Bayar
                </h2>
                <p class="text-sm text-gray-500 mt-1">Daftar user yang telah melakukan pembayaran dan transaksi berhasil</p>
            </div>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Transaksi</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Dibayar</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Terakhir</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($users as $user)
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
                                <td class="px-4 py-3.5">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="https://wa.me/{{ $user->phone }}" target="_blank" title="Chat WhatsApp"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-700 text-xs font-medium rounded-lg hover:bg-emerald-100 transition">
                                            <i class="fab fa-whatsapp"></i>
                                            Chat
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-12">
                                    <i data-lucide="inbox" class="w-10 h-10 text-gray-300 mx-auto mb-3"></i>
                                    <p class="text-sm text-gray-500">Belum ada customer yang melakukan pembayaran</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if ($users->hasPages())
            <div class="mt-2">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();
    });
</script>
@endpush
