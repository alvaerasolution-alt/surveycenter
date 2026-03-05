@extends('layouts.admin')

@section('title', 'Tambah Banner')
@section('page-title', 'Tambah Banner')

@section('content')
<div class="p-6 max-w-2xl">
    <h1 class="text-xl font-bold mb-6">Tambah Banner</h1>

    <form action="{{ route('admin.discount-banners.store') }}" 
          method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Judul</label>
            <input type="text" name="title" value="{{ old('title') }}" class="border border-gray-300 p-2.5 w-full rounded-lg text-sm focus:ring-2 focus:ring-orange-400 focus:border-transparent outline-none">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Sub Judul</label>
            <input type="text" name="subtitle" value="{{ old('subtitle') }}" class="border border-gray-300 p-2.5 w-full rounded-lg text-sm focus:ring-2 focus:ring-orange-400 focus:border-transparent outline-none">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Teks Tombol</label>
                <input type="text" name="button_text" value="{{ old('button_text') }}" class="border border-gray-300 p-2.5 w-full rounded-lg text-sm focus:ring-2 focus:ring-orange-400 focus:border-transparent outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Urutan</label>
                <input type="number" name="order" value="{{ old('order', 0) }}" class="border border-gray-300 p-2.5 w-full rounded-lg text-sm focus:ring-2 focus:ring-orange-400 focus:border-transparent outline-none">
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Link Tombol</label>
            <input type="text" name="button_link" value="{{ old('button_link') }}" class="border border-gray-300 p-2.5 w-full rounded-lg text-sm focus:ring-2 focus:ring-orange-400 focus:border-transparent outline-none">
        </div>

        {{-- Background Color Picker --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Warna Background</label>

            {{-- Preset Colors --}}
            <div class="flex flex-wrap gap-2 mb-3">
                <button type="button" onclick="setPreset('#FF6B1A','#FF8C42')" class="w-8 h-8 rounded-lg border-2 border-gray-200 hover:border-orange-500 transition" style="background:linear-gradient(135deg,#FF6B1A,#FF8C42)" title="Orange"></button>
                <button type="button" onclick="setPreset('#2563eb','#3b82f6')" class="w-8 h-8 rounded-lg border-2 border-gray-200 hover:border-orange-500 transition" style="background:linear-gradient(135deg,#2563eb,#3b82f6)" title="Blue"></button>
                <button type="button" onclick="setPreset('#059669','#10b981')" class="w-8 h-8 rounded-lg border-2 border-gray-200 hover:border-orange-500 transition" style="background:linear-gradient(135deg,#059669,#10b981)" title="Green"></button>
                <button type="button" onclick="setPreset('#dc2626','#ef4444')" class="w-8 h-8 rounded-lg border-2 border-gray-200 hover:border-orange-500 transition" style="background:linear-gradient(135deg,#dc2626,#ef4444)" title="Red"></button>
                <button type="button" onclick="setPreset('#7c3aed','#8b5cf6')" class="w-8 h-8 rounded-lg border-2 border-gray-200 hover:border-orange-500 transition" style="background:linear-gradient(135deg,#7c3aed,#8b5cf6)" title="Purple"></button>
                <button type="button" onclick="setPreset('#0d9488','#14b8a6')" class="w-8 h-8 rounded-lg border-2 border-gray-200 hover:border-orange-500 transition" style="background:linear-gradient(135deg,#0d9488,#14b8a6)" title="Teal"></button>
                <button type="button" onclick="setPreset('#ea580c','#f59e0b')" class="w-8 h-8 rounded-lg border-2 border-gray-200 hover:border-orange-500 transition" style="background:linear-gradient(135deg,#ea580c,#f59e0b)" title="Amber"></button>
                <button type="button" onclick="setPreset('#1e293b','#334155')" class="w-8 h-8 rounded-lg border-2 border-gray-200 hover:border-orange-500 transition" style="background:linear-gradient(135deg,#1e293b,#334155)" title="Dark"></button>
            </div>

            {{-- Color Pickers --}}
            <div class="flex items-center gap-4 mb-3">
                <div class="flex items-center gap-2">
                    <label class="text-xs text-gray-500">Warna 1</label>
                    <input type="color" id="color1" value="#FF6B1A" class="w-10 h-10 rounded-lg border border-gray-300 cursor-pointer p-0.5" onchange="updateGradient()">
                </div>
                <div class="flex items-center gap-2">
                    <label class="text-xs text-gray-500">Warna 2</label>
                    <input type="color" id="color2" value="#FF8C42" class="w-10 h-10 rounded-lg border border-gray-300 cursor-pointer p-0.5" onchange="updateGradient()">
                </div>
                <div class="flex items-center gap-2">
                    <label class="text-xs text-gray-500">Arah</label>
                    <select id="gradientDir" class="border border-gray-300 rounded-lg text-xs p-1.5" onchange="updateGradient()">
                        <option value="90deg">→ Horizontal</option>
                        <option value="135deg" selected>↘ Diagonal</option>
                        <option value="180deg">↓ Vertikal</option>
                        <option value="45deg">↗ Diagonal Naik</option>
                    </select>
                </div>
            </div>

            {{-- Hidden input --}}
            <input type="hidden" name="background" id="backgroundInput" value="{{ old('background', 'linear-gradient(135deg, #FF6B1A, #FF8C42)') }}">

            {{-- Live Preview --}}
            <div id="bannerPreview" class="rounded-xl p-5 flex items-center justify-between shadow-sm border border-gray-200 mt-2 relative overflow-hidden" style="background: {{ old('background', 'linear-gradient(135deg, #FF6B1A, #FF8C42)') }};">
                <div>
                    <div class="absolute inset-0 bg-black/40 rounded-xl"></div>
                    <h3 class="relative text-lg font-bold text-white" style="text-shadow:0 1px 3px rgba(0,0,0,.4)">Preview Judul Banner</h3>
                    <p class="relative text-sm text-white/80" style="text-shadow:0 1px 3px rgba(0,0,0,.4)">Preview subtitle teks</p>
                </div>
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Gambar</label>
            <input type="file" name="image" class="border border-gray-300 p-2.5 w-full rounded-lg text-sm">
        </div>

        <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-bold px-6 py-2.5 rounded-lg transition shadow-sm">
            Simpan
        </button>
    </form>
</div>

<script>
function updateGradient() {
    const c1 = document.getElementById('color1').value;
    const c2 = document.getElementById('color2').value;
    const dir = document.getElementById('gradientDir').value;
    const bg = `linear-gradient(${dir}, ${c1}, ${c2})`;
    document.getElementById('backgroundInput').value = bg;
    document.getElementById('bannerPreview').style.background = bg;
}

function setPreset(c1, c2) {
    document.getElementById('color1').value = c1;
    document.getElementById('color2').value = c2;
    updateGradient();
}

// Init: parse existing value
document.addEventListener('DOMContentLoaded', function() {
    const val = document.getElementById('backgroundInput').value;
    const match = val.match(/#[0-9a-fA-F]{3,8}/g);
    if (match && match.length >= 2) {
        document.getElementById('color1').value = match[0].length <= 4 ? match[0] : match[0].substring(0, 7);
        document.getElementById('color2').value = match[1].length <= 4 ? match[1] : match[1].substring(0, 7);
    }
    const dirMatch = val.match(/(\d+deg)/);
    if (dirMatch) {
        const sel = document.getElementById('gradientDir');
        for (let o of sel.options) { if (o.value === dirMatch[1]) { o.selected = true; break; } }
    }
});
</script>
@endsection
