@extends('layouts.admin')

@section('title', 'Customer Stories')
@section('page-title', 'Customer Stories')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Customer Success Stories</h2>
                <p class="text-sm text-gray-500 mt-1">Kelola testimoni dan cerita sukses pelanggan</p>
            </div>
            <a href="{{ route('customer-stories.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition shadow-sm">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Tambah Story
            </a>
        </div>

        {{-- Stories Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($stories as $story)
                <div class="group bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-md hover:border-orange-200 transition-all duration-200">
                    @if($story->image)
                        <div class="aspect-video bg-gray-50 overflow-hidden">
                            <img src="{{ asset('storage/' . $story->image) }}" alt="{{ $story->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        </div>
                    @endif
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 text-sm mb-1">{{ $story->title }}</h3>
                        <p class="text-xs text-gray-500 line-clamp-2">{{ $story->highlight }}</p>
                        <div class="flex items-center gap-1 mt-3 pt-3 border-t border-gray-100">
                            <a href="{{ route('customer-stories.edit', $story) }}" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-gray-100 transition">
                                <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                Edit
                            </a>
                            <form action="{{ route('customer-stories.destroy', $story) }}" method="POST" class="inline" onclick="return confirm('Delete this story?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-medium text-red-600 hover:bg-red-50 transition">
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if(count($stories) === 0)
            <div class="bg-white rounded-xl border border-gray-200 text-center py-16">
                <i data-lucide="message-square-quote" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                <p class="text-sm text-gray-500">Belum ada customer story</p>
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
