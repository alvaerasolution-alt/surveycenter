@extends('layouts.admin')

@section('title', 'Kelola Banner Diskon')
@section('page-title', 'Kelola Banners')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Banner Diskon</h2>
                <p class="text-sm text-gray-500 mt-1">Kelola banner promosi dan diskon</p>
            </div>
            <a href="{{ route('admin.discount-banners.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition shadow-sm">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Tambah Banner
            </a>
        </div>

        {{-- Table Card --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-12">#</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Judul</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Sub Judul</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tombol</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Background</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Gambar</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Urutan</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($banners as $banner)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3.5 text-gray-400 text-xs">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3.5 font-medium text-gray-900">{{ $banner->title }}</td>
                                <td class="px-4 py-3.5 text-gray-600">{{ Str::limit($banner->subtitle, 40) }}</td>
                                <td class="px-4 py-3.5">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                        {{ $banner->button_text }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5">
                                    <div class="flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-md border border-gray-200 flex-shrink-0" style="background: {{ $banner->background }}"></span>
                                        <span class="text-xs text-gray-500 font-mono">{{ $banner->background }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3.5">
                                    @if($banner->image)
                                        <img src="{{ asset('storage/'.$banner->image) }}" class="w-14 h-10 object-cover rounded-lg border border-gray-200">
                                    @else
                                        <span class="text-gray-400 text-xs">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-gray-100 text-gray-600 text-xs font-semibold">
                                        {{ $banner->order }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('admin.discount-banners.edit', $banner) }}" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-500 hover:text-blue-600 transition" title="Edit">
                                            <i data-lucide="pencil" class="w-4 h-4"></i>
                                        </a>
                                        <form action="{{ route('admin.discount-banners.destroy', $banner) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus banner ini?')">
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
                                <td colspan="8" class="text-center py-12">
                                    <i data-lucide="image" class="w-10 h-10 text-gray-300 mx-auto mb-3"></i>
                                    <p class="text-sm text-gray-500">Belum ada banner</p>
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
