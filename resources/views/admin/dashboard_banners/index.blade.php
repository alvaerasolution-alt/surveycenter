@extends('layouts.admin')

@section('title', 'Kelola Slider Dashboard')
@section('page-title', 'Slider Dashboard')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Slider Dashboard User</h2>
                <p class="text-sm text-gray-500 mt-1">Kelola banner/slider yang tampil di halaman dashboard user</p>
            </div>
            <a href="{{ route('admin.dashboard-banners.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition shadow-sm">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Tambah Slide
            </a>
        </div>

        {{-- Alert messages --}}
        @if(session('success'))
            <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm">
                <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0"></i>
                {{ session('success') }}
            </div>
        @endif

        {{-- Preview info --}}
        <div class="bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 flex items-start gap-3">
            <i data-lucide="info" class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5"></i>
            <p class="text-sm text-blue-700">
                Banner aktif dengan urutan terendah akan tampil pertama. Jika tidak ada banner aktif, dashboard user akan menampilkan banner default.
            </p>
        </div>

        {{-- Table Card --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-12">#</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Preview</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Urutan</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($banners as $banner)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3.5 text-gray-400 text-xs">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3.5">
                                    @if($banner->image)
                                        <img src="{{ asset('storage/'.$banner->image) }}"
                                             class="w-24 h-14 rounded-lg object-cover border border-gray-200"
                                             alt="Slide image">
                                    @else
                                        <span class="text-red-500 text-xs">Gambar tidak tersedia</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-gray-100 text-gray-600 text-xs font-semibold">
                                        {{ $banner->order }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    <form action="{{ route('admin.dashboard-banners.toggle', $banner) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold transition
                                                    {{ $banner->is_active ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                                            <span class="w-2 h-2 rounded-full mr-1.5 {{ $banner->is_active ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                            {{ $banner->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="px-4 py-3.5">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('admin.dashboard-banners.edit', $banner) }}"
                                           class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-500 hover:text-blue-600 transition" title="Edit">
                                            <i data-lucide="pencil" class="w-4 h-4"></i>
                                        </a>
                                        <form action="{{ route('admin.dashboard-banners.destroy', $banner) }}" method="POST"
                                              class="inline" onsubmit="return confirm('Yakin hapus banner ini?')">
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
                                <td colspan="5" class="text-center py-16">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center">
                                            <i data-lucide="layout" class="w-8 h-8 text-gray-300"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-500">Belum ada slider</p>
                                            <p class="text-xs text-gray-400 mt-1">Tambah slide pertama untuk dashboard user</p>
                                        </div>
                                        <a href="{{ route('admin.dashboard-banners.create') }}"
                                           class="inline-flex items-center gap-1.5 text-sm font-medium text-orange-600 hover:text-orange-700">
                                            <i data-lucide="plus" class="w-4 h-4"></i>
                                            Tambah Slide
                                        </a>
                                    </div>
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
