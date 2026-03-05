@extends('layouts.admin')
@section('title', 'Kelola SEO')
@section('page-title', 'Kelola SEO')

@section('content')
<div class="p-2" x-data="seoManager()">

  <div class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-xl font-semibold text-gray-900">Kelola SEO</h1>
      <p class="text-sm text-gray-500 mt-1">Title, meta description, dan keywords untuk setiap halaman.</p>
    </div>
  </div>

  @if(session('success'))
    <div class="mb-5 px-4 py-3 bg-green-100 border border-green-300 text-green-700 rounded-lg text-sm flex items-center gap-2">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>
      {{ session('success') }}
    </div>
  @endif

  {{-- Table --}}
  <div class="overflow-hidden rounded-xl border border-gray-200">
    <table class="w-full text-sm">
      <thead>
        <tr class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide">
          <th class="px-5 py-3 text-left">Halaman</th>
          <th class="px-5 py-3 text-left">URL</th>
          <th class="px-5 py-3 text-left">Title SEO</th>
          <th class="px-5 py-3 text-left">Meta Description</th>
          <th class="px-5 py-3 text-center">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100">
        @foreach($pages as $slug => $label)
          @php
            $title    = $settings["seo_title_{$slug}"]->value    ?? '-';
            $desc     = $settings["seo_desc_{$slug}"]->value     ?? '-';
            $keywords = $settings["seo_keywords_{$slug}"]->value ?? '';
          @endphp
          <tr class="hover:bg-gray-50 transition">
            <td class="px-5 py-3 font-semibold text-gray-700">
              <span class="inline-flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-orange-500 inline-block"></span>
                {{ $label }}
              </span>
            </td>
            <td class="px-5 py-3 text-gray-400 font-mono text-xs">/{{ $slug === 'home' ? '' : $slug }}</td>
            <td class="px-5 py-3 text-gray-600 max-w-[200px] truncate">
              @if($title !== '-')
                <span class="text-gray-800">{{ Str::limit($title, 50) }}</span>
              @else
                <span class="text-gray-300 italic text-xs">Belum diisi</span>
              @endif
            </td>
            <td class="px-5 py-3 text-gray-600 max-w-[220px] truncate">
              @if($desc !== '-')
                <span>{{ Str::limit($desc, 60) }}</span>
              @else
                <span class="text-gray-300 italic text-xs">Belum diisi</span>
              @endif
            </td>
            <td class="px-5 py-3 text-center">
              <button
                @click="openModal('{{ $slug }}', '{{ $label }}', {{ json_encode($title === '-' ? '' : $title) }}, {{ json_encode($desc === '-' ? '' : $desc) }}, {{ json_encode($keywords) }})"
                class="bg-orange-600 hover:bg-orange-700 text-white text-xs font-semibold px-4 py-1.5 rounded-lg transition inline-flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828a4 4 0 01-2.828 1.172H7v-2a4 4 0 011.172-2.828z"/>
                </svg>
                Edit
              </button>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  {{-- ===== MODAL ===== --}}
  <div x-show="showModal"
       x-transition:enter="transition ease-out duration-200"
       x-transition:enter-start="opacity-0"
       x-transition:enter-end="opacity-100"
       x-transition:leave="transition ease-in duration-150"
       x-transition:leave-start="opacity-100"
       x-transition:leave-end="opacity-0"
       class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm"
       @click.self="showModal = false">

    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100">

      {{-- Modal Header --}}
      <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
        <div>
          <h2 class="font-bold text-gray-800 text-base">Edit SEO</h2>
          <p class="text-xs text-gray-400 mt-0.5" x-text="'Halaman: ' + currentLabel"></p>
        </div>
        <button @click="showModal = false" class="text-gray-400 hover:text-gray-600 transition">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>

      {{-- Modal Form --}}
      <form :action="'{{ route('admin.seo.update') }}'" method="POST" class="px-6 py-5 space-y-4">
        @csrf
        @method('PUT')
        <input type="hidden" name="single_slug" :value="currentSlug">

        {{-- Title --}}
        <div>
          <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">
            Title SEO
            <span class="text-gray-400 font-normal normal-case">(maks 60 karakter)</span>
            <span class="ml-1 text-xs text-gray-300" x-text="'(' + titleLen + '/60)'"></span>
          </label>
          <input type="text"
                 name="title"
                 x-model="form.title"
                 @input="titleLen = form.title.length"
                 maxlength="200"
                 placeholder="Judul halaman untuk Google..."
                 class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition">
          {{-- Character hint bar --}}
          <div class="mt-1 h-1 rounded-full bg-gray-100 overflow-hidden">
            <div class="h-full bg-orange-500 transition-all duration-300"
                 :style="'width: ' + Math.min((form.title.length/60)*100, 100) + '%'"
                 :class="form.title.length > 60 ? 'bg-red-400' : 'bg-orange-500'"></div>
          </div>
        </div>

        {{-- Meta Description --}}
        <div>
          <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">
            Meta Description
            <span class="text-gray-400 font-normal normal-case">(maks 160 karakter)</span>
            <span class="ml-1 text-xs text-gray-300" x-text="'(' + descLen + '/160)'"></span>
          </label>
          <textarea name="desc"
                    x-model="form.desc"
                    @input="descLen = form.desc.length"
                    maxlength="500"
                    rows="3"
                    placeholder="Deskripsi singkat yang muncul di hasil pencarian Google..."
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition resize-none"></textarea>
          <div class="mt-1 h-1 rounded-full bg-gray-100 overflow-hidden">
            <div class="h-full transition-all duration-300"
                 :style="'width: ' + Math.min((form.desc.length/160)*100, 100) + '%'"
                 :class="form.desc.length > 160 ? 'bg-red-400' : 'bg-orange-500'"></div>
          </div>
        </div>

        {{-- Keywords --}}
        <div>
          <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">
            Keywords <span class="text-gray-400 font-normal normal-case">(pisah dengan koma)</span>
          </label>
          <input type="text"
                 name="keywords"
                 x-model="form.keywords"
                 maxlength="300"
                 placeholder="survei, riset pasar, kuesioner..."
                 class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition">
        </div>

        {{-- Actions --}}
        <div class="flex gap-3 pt-2">
          <button type="button" @click="showModal = false"
                  class="flex-1 border border-gray-200 text-gray-600 font-semibold py-2.5 rounded-xl hover:bg-gray-50 transition text-sm">
            Batal
          </button>
          <button type="submit"
                  class="flex-1 bg-orange-600 hover:bg-orange-700 text-white font-semibold py-2.5 rounded-xl transition text-sm flex items-center justify-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
            Simpan
          </button>
        </div>
      </form>
    </div>
  </div>

</div>
@endsection

@push('scripts')
<script>
function seoManager() {
  return {
    showModal: false,
    currentSlug: '',
    currentLabel: '',
    titleLen: 0,
    descLen: 0,
    form: { title: '', desc: '', keywords: '' },

    openModal(slug, label, title, desc, keywords) {
      this.currentSlug  = slug;
      this.currentLabel = label;
      this.form.title    = title;
      this.form.desc     = desc;
      this.form.keywords = keywords;
      this.titleLen = title.length;
      this.descLen  = desc.length;
      this.showModal = true;
    }
  }
}
</script>
@endpush
