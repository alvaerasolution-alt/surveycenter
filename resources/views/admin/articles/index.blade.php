@extends('layouts.admin')

@section('title', 'Kelola Articles')
@section('page-title', 'Kelola Articles')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Daftar Article</h2>
                <p class="text-sm text-gray-500 mt-1">Kelola semua artikel blog</p>
            </div>
            <a href="{{ route('admin.articles.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition shadow-sm">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Tambah Article
            </a>
        </div>

        {{-- Search --}}
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <form action="{{ route('admin.articles.index') }}" method="GET" class="flex gap-3">
                <div class="relative flex-1">
                    <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                    <input type="text" name="search" placeholder="Cari artikel..." value="{{ request('search') }}"
                        class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                </div>
                <button type="submit" class="px-5 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition">
                    Cari
                </button>
            </form>
        </div>

        {{-- Table Card --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-12">#</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Judul</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Gambar</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($articles as $article)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3.5 text-gray-400 text-xs">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3.5">
                                    <a class="font-medium text-gray-900 hover:text-orange-600 transition" href="{{ route('blog.show', $article->slug) }}" target="_blank">
                                        {{ Str::limit($article->title, 50) }}
                                    </a>
                                </td>
                                <td class="px-4 py-3.5">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                        {{ $article->category }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5">
                                    @if ($article->image)
                                        <img src="{{ url('storage/' . $article->image) }}" alt="thumb" class="w-14 h-10 object-cover rounded-lg border border-gray-200">
                                    @else
                                        <span class="text-gray-400 text-xs">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('admin.articles.edit', $article->id) }}" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-500 hover:text-blue-600 transition" title="Edit">
                                            <i data-lucide="pencil" class="w-4 h-4"></i>
                                        </a>
                                        <form action="{{ route('admin.articles.destroy', $article->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin hapus?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg hover:bg-red-50 text-gray-500 hover:text-red-600 transition" title="Hapus">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($articles->isEmpty())
                <div class="text-center py-12">
                    <i data-lucide="file-text" class="w-10 h-10 text-gray-300 mx-auto mb-3"></i>
                    <p class="text-sm text-gray-500">Belum ada artikel</p>
                </div>
            @endif
        </div>

        {{-- Pagination --}}
        <div class="mt-2">
            {{ $articles->links() }}
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
