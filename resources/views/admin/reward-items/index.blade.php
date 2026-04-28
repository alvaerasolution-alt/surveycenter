@extends('layouts.admin')

@section('title', 'Kelola Reward Items')
@section('page-title', 'Kelola Reward Items')

@section('content')
<div class="max-w-5xl">

    @if(session('success'))
    <div class="mb-4 flex items-start gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm">
        <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Reward Items</h2>
                <p class="text-sm text-gray-500 mt-1">Kelola item reward yang bisa ditukar user dengan poin</p>
            </div>
            <a href="{{ route('admin.reward-items.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition shadow-sm">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Tambah Item
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Kategori</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Harga Poin</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Nilai</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Stok</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($items as $item)
                    <tr>
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-800">{{ $item->name }}</p>
                            @if($item->description)
                            <p class="text-[11px] text-gray-400 truncate max-w-xs">{{ $item->description }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold {{ $item->category === 'pulsa' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                <i data-lucide="{{ \App\Models\RewardItem::getCategoryIcon($item->category) }}" class="w-3 h-3"></i>
                                {{ \App\Models\RewardItem::getCategoryLabel($item->category) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 font-semibold text-amber-600">
                            {{ number_format($item->points_cost, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-gray-600">
                            {{ $item->value ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-gray-600">
                            {{ $item->stock === -1 ? 'Unlimited' : $item->stock }}
                        </td>
                        <td class="px-4 py-3">
                            @if($item->is_active)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-green-100 text-green-700">
                                <i data-lucide="check-circle" class="w-3 h-3"></i> Aktif
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-gray-100 text-gray-500">
                                <i data-lucide="x-circle" class="w-3 h-3"></i> Nonaktif
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.reward-items.edit', $item) }}"
                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-gray-100 text-gray-700 text-xs font-medium rounded-lg hover:bg-gray-200 transition">
                                    <i data-lucide="pencil" class="w-3 h-3"></i> Edit
                                </a>
                                <form method="POST" action="{{ route('admin.reward-items.destroy', $item) }}" onsubmit="return confirm('Hapus item {{ $item->name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-red-100 text-red-700 text-xs font-medium rounded-lg hover:bg-red-200 transition">
                                        <i data-lucide="trash-2" class="w-3 h-3"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center">
                            <i data-lucide="gift" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                            <p class="text-sm text-gray-400">Belum ada reward item.</p>
                            <a href="{{ route('admin.reward-items.create') }}" class="inline-flex items-center gap-1 text-sm text-orange-600 font-medium mt-2 hover:underline">
                                <i data-lucide="plus" class="w-4 h-4"></i> Tambah item pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($items->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $items->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
