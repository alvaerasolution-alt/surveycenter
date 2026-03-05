@extends('layouts.admin')

@section('title', 'Testimoni')
@section('page-title', 'Kelola Testimoni')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold text-gray-900">Gambar Testimoni</h2>
            <p class="text-sm text-gray-500 mt-1">Upload screenshot WhatsApp atau gambar testimoni pelanggan</p>
        </div>
        <button onclick="document.getElementById('uploadPanel').classList.toggle('hidden')"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition shadow-sm">
            <i data-lucide="upload" class="w-4 h-4"></i>
            Upload Gambar
        </button>
    </div>

    {{-- Success Alert --}}
    @if(session('success'))
        <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
            <i data-lucide="check-circle" class="w-4 h-4 flex-shrink-0"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Upload Panel --}}
    <div id="uploadPanel" class="hidden">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                <i data-lucide="image-plus" class="w-4 h-4 text-orange-500"></i>
                Upload Foto Testimoni
            </h3>
            <form action="{{ route('admin.testimoni.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                {{-- Drop Zone --}}
                <div id="dropZone"
                    class="relative border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-orange-400 transition-colors cursor-pointer"
                    onclick="document.getElementById('imageInput').click()">
                    <input type="file" id="imageInput" name="images[]" accept="image/*" multiple class="hidden"
                        onchange="previewImages(event)">
                    <div id="dropHint">
                        <i data-lucide="upload-cloud" class="w-10 h-10 text-gray-300 mx-auto mb-3"></i>
                        <p class="text-sm text-gray-500 font-medium">Klik atau seret gambar ke sini</p>
                        <p class="text-xs text-gray-400 mt-1">JPG, PNG, WEBP — max 5MB per file — bisa pilih banyak</p>
                    </div>
                    <div id="previewGrid" class="hidden mt-4 grid grid-cols-3 sm:grid-cols-4 gap-3 text-left">
                    </div>
                </div>

                {{-- Caption --}}
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Keterangan (opsional)</label>
                    <input type="text" name="caption" placeholder="Contoh: Klien survey kepuasan"
                        class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400">
                </div>

                @error('images.*')
                    <p class="text-xs text-red-500">{{ $message }}</p>
                @enderror

                <div class="flex gap-3">
                    <button type="submit"
                        class="px-5 py-2.5 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition">
                        Upload Sekarang
                    </button>
                    <button type="button" onclick="document.getElementById('uploadPanel').classList.add('hidden')"
                        class="px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Image Grid --}}
    @if($testimonis->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
            @foreach ($testimonis as $t)
                <div class="group bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-md hover:border-orange-200 transition-all duration-200">
                    {{-- Image --}}
                    <a href="{{ asset('storage/'.$t->image_path) }}" target="_blank" class="block">
                        <div class="aspect-[9/16] bg-gray-100 overflow-hidden">
                            <img src="{{ asset('storage/'.$t->image_path) }}"
                                alt="{{ $t->caption ?? 'Testimoni' }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                    </a>
                    {{-- Info & Actions --}}
                    <div class="px-3 py-2.5 border-t border-gray-100">
                        @if($t->caption)
                            <p class="text-xs text-gray-600 truncate mb-1.5">{{ $t->caption }}</p>
                        @endif
                        <div class="flex items-center justify-between">
                            {{-- Toggle aktif --}}
                            <form action="{{ route('admin.testimoni.toggle', $t) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="text-xs px-2 py-0.5 rounded-full font-medium transition
                                    {{ $t->is_active ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                                    {{ $t->is_active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </form>
                            {{-- Hapus --}}
                            <form action="{{ route('admin.testimoni.destroy', $t) }}" method="POST"
                                onsubmit="return confirm('Hapus gambar ini?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="p-1 rounded hover:bg-red-50 text-gray-400 hover:text-red-600 transition">
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-xl border border-gray-200 text-center py-20">
            <i data-lucide="message-square" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
            <p class="text-sm text-gray-500">Belum ada gambar testimoni</p>
            <p class="text-xs text-gray-400 mt-1">Klik tombol "Upload Gambar" di atas untuk mulai</p>
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof lucide !== 'undefined') lucide.createIcons();
});

function previewImages(event) {
    const files = Array.from(event.target.files);
    if (!files.length) return;

    const previewGrid = document.getElementById('previewGrid');
    const dropHint    = document.getElementById('dropHint');
    previewGrid.innerHTML = '';
    previewGrid.classList.remove('hidden');
    dropHint.classList.add('hidden');

    files.forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            const div = document.createElement('div');
            div.className = 'relative aspect-[9/16] bg-gray-100 rounded-lg overflow-hidden';
            div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">
                <span class="absolute bottom-0 left-0 right-0 bg-black/50 text-white text-[9px] px-1 py-0.5 truncate">${file.name}</span>`;
            previewGrid.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
}
</script>
@endpush
