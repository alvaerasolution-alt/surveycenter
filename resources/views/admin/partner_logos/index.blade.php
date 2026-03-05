@extends('layouts.admin')

@section('title', 'Partner Logos')
@section('page-title', 'Kelola Logos')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Partner Logos</h2>
                <p class="text-sm text-gray-500 mt-1">Kelola logo partner dan klien</p>
            </div>
            <a href="{{ route('partner-logos.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition shadow-sm">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Tambah Logo
            </a>
        </div>

        {{-- Grid Cards --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
            @foreach ($logos as $logo)
                <div class="group bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-md hover:border-orange-200 transition-all duration-200">
                    <div class="aspect-[3/2] bg-gray-50 flex items-center justify-center p-4">
                        <img src="{{ asset('storage/'.$logo->logo_path) }}" alt="{{ $logo->name }}" class="max-h-16 max-w-full object-contain">
                    </div>
                    <div class="px-4 py-3 border-t border-gray-100">
                        <p class="text-sm font-medium text-gray-800 truncate">{{ $logo->name }}</p>
                        <div class="flex items-center gap-1 mt-2">
                            <a href="{{ route('partner-logos.edit', $logo) }}" class="p-1 rounded hover:bg-gray-100 text-gray-400 hover:text-blue-600 transition" title="Edit">
                                <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                            </a>
                            <form action="{{ route('partner-logos.destroy', $logo) }}" method="POST" class="inline" onclick="return confirm('Delete this logo?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1 rounded hover:bg-red-50 text-gray-400 hover:text-red-600 transition" title="Hapus">
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if(count($logos) === 0)
            <div class="bg-white rounded-xl border border-gray-200 text-center py-16">
                <i data-lucide="award" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                <p class="text-sm text-gray-500">Belum ada logo partner</p>
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
