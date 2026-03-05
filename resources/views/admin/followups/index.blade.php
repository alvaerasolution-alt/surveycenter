@extends('layouts.crm')

@section('title', 'Follow Ups')
@section('page-title', 'Follow Ups')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Daftar Follow-Up</h2>
                <p class="text-sm text-gray-500 mt-1">Kelola follow-up dan prospek klien</p>
            </div>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Customer</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Telepon</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Catatan</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($followUps as $followup)
                            <tr class="hover:bg-gray-50 transition {{ $followup->customer->status === 'lead' ? 'bg-amber-50/50' : '' }}">
                                <td class="px-4 py-3.5 font-medium text-gray-900">
                                    {{ $followup->customer->full_name }}
                                </td>
                                <td class="px-4 py-3.5 text-gray-600">
                                    {{ $followup->customer->email ?? '-' }}
                                </td>
                                <td class="px-4 py-3.5">
                                    @if ($followup->customer->phone)
                                        @php
                                            $phone = preg_replace('/[^0-9]/', '', $followup->customer->phone);
                                            if (Str::startsWith($phone, '0')) {
                                                $phone = '62' . substr($phone, 1);
                                            }
                                        @endphp
                                        <a href="https://wa.me/{{ $phone }}" target="_blank"
                                            class="inline-flex items-center gap-1.5 text-emerald-600 hover:text-emerald-700 text-xs font-medium">
                                            <i class="fab fa-whatsapp"></i>
                                            {{ $followup->customer->phone }}
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-xs">—</span>
                                    @endif
                                </td>
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
                                <td class="px-4 py-3.5 text-gray-500 text-xs max-w-[150px] truncate">
                                    {{ $followup->note ?? '-' }}
                                </td>
                                <td class="px-4 py-3.5">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('followups.edit', $followup->id) }}" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-500 hover:text-blue-600 transition" title="Edit">
                                            <i data-lucide="pencil" class="w-4 h-4"></i>
                                        </a>
                                        <form action="{{ route('followups.destroy', $followup->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus follow-up ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg hover:bg-red-50 text-gray-500 hover:text-red-600 transition" title="Hapus">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-12">
                                    <i data-lucide="phone-call" class="w-10 h-10 text-gray-300 mx-auto mb-3"></i>
                                    <p class="text-sm text-gray-500">Belum ada data follow-up</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="mt-2">
            {{ $followUps->links() }}
        </div>
    </div>

    {{-- Notification Handler --}}
    <div x-data="notificationHandler()" x-init="init()"
        class="fixed right-4 bottom-4 z-[9999] w-80 max-w-[90%] flex flex-col gap-3 pointer-events-none"
        style="position: fixed; right: 1rem; bottom: 1rem;">
    </div>

    <script>
        function notificationHandler() {
            return {
                notifications: [],
                init() {
                    const data = @json(session('new_customers_data', []));
                    data.forEach((customer, index) => {
                        const uniqueId = (customer.email && customer.email.trim() !== '') ?
                            customer.email :
                            `notif-${customer.name ?? 'unknown'}-${customer.phone ?? 'nop'}-${index}`;
                        if (!this.notifications.find(n => n.id === uniqueId)) {
                            this.notifications.push({
                                id: uniqueId,
                                name: customer.name ?? '-',
                                email: customer.email ?? '-',
                                phone: customer.phone ?? '-'
                            });
                        }
                    });
                },
                remove(id) {
                    this.notifications = this.notifications.filter(n => n.id !== id);
                },
                clearAll() {
                    this.notifications = [];
                }
            }
        }
    </script>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();
    });
</script>
@endpush
