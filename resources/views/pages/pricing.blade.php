@extends('layouts.app')
@section('seo_slug', 'pricing')

@section('content')

{{-- ═══════════ HERO HEADER ═══════════ --}}
<section class="relative overflow-hidden bg-orange-500 py-20 px-4">
  {{-- Decorative blobs --}}
  <div class="absolute -top-32 -left-32 w-96 h-96 bg-orange-400/10 rounded-full blur-3xl pointer-events-none"></div>
  <div class="absolute -bottom-24 right-0 w-80 h-80 bg-orange-500/10 rounded-full blur-3xl pointer-events-none"></div>

  <div class="relative max-w-3xl mx-auto text-center">
    <span class="inline-block bg-white/20 text-white text-xs font-bold uppercase tracking-widest px-4 py-1.5 rounded-full mb-5 border border-white/30">
      💰 Harga Transparan
    </span>
    <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-4 leading-tight">
      Hitung Biaya Survey<br>
      <span class="text-white/90">Anda Sekarang</span>
    </h1>
    <p class="text-white/75 text-base md:text-lg max-w-xl mx-auto">
      Kalkulator harga instan — masukkan detail survey dan dapatkan estimasi biaya secara real-time.
    </p>
  </div>
</section>

{{-- ═══════════ MAIN CONTENT ═══════════ --}}
<div class="bg-gray-50 min-h-screen">
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

  {{-- Alert Messages --}}
  @if (session('error'))
    <div class="mb-6 flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
      <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      <span>{{ session('error') }}</span>
    </div>
  @endif
  @if ($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
      <strong class="block font-bold mb-1">Validasi Gagal!</strong>
      <ul class="list-disc list-inside space-y-0.5">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- ── TWO COLUMN LAYOUT ── --}}
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">

    {{-- LEFT: Pricing Info Card --}}
    <div class="space-y-6">

      {{-- Price Highlight --}}
      <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-7">
        <div class="flex items-center gap-3 mb-5">
          <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center text-xl">💡</div>
          <div>
            <h2 class="text-lg font-bold text-gray-900">Harga Dasar</h2>
            <p class="text-xs text-gray-400">per pertanyaan × per responden</p>
          </div>
        </div>
        <div class="flex items-end gap-2 mb-2">
          <span class="text-5xl font-extrabold text-gray-900">Rp</span>
          <span class="text-6xl font-extrabold text-orange-500 leading-none">1.000</span>
        </div>
        <p class="text-sm text-gray-500 mb-4">per pertanyaan / per responden</p>
        <div class="bg-red-50 text-red-600 text-xs font-semibold px-3 py-1.5 rounded-lg inline-flex items-center gap-1.5 border border-red-200">
          <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
          Minimal order Rp 50.000 per survey
        </div>
      </div>

      {{-- Diskon Badge --}}
      <div class="grid grid-cols-3 gap-3">
        <div class="bg-white border border-gray-200 rounded-xl p-4 text-center shadow-sm">
          <div class="text-2xl font-extrabold text-orange-600 mb-1">50%</div>
          <div class="text-xs font-semibold text-gray-700">Diskon</div>
          <div class="text-xs text-gray-400 mt-0.5">Mahasiswa</div>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4 text-center shadow-sm">
          <div class="text-2xl font-extrabold text-emerald-600 mb-1">30%</div>
          <div class="text-xs font-semibold text-gray-700">Diskon</div>
          <div class="text-xs text-gray-400 mt-0.5">Perusahaan</div>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4 text-center shadow-sm">
          <div class="text-2xl font-extrabold text-gray-400 mb-1">0%</div>
          <div class="text-xs font-semibold text-gray-700">Diskon</div>
          <div class="text-xs text-gray-400 mt-0.5">Umum</div>
        </div>
      </div>

      {{-- Rules & Notices --}}
      <div class="space-y-3">
        <div class="flex items-start gap-3 bg-red-50 border border-red-200 rounded-xl px-4 py-3">
          <svg class="w-4 h-4 text-red-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
          <div>
            <p class="text-xs font-bold text-red-700 mb-1">Larangan</p>
            <ul class="text-xs text-red-600 space-y-0.5 list-disc list-inside">
              <li>Dilarang mengandung SARA, pornografi, atau ujaran kebencian</li>
              <li>Pertanyaan harus sesuai etika & norma sosial</li>
              <li>Data responden wajib dijaga kerahasiaannya</li>
            </ul>
          </div>
        </div>
        <div class="flex items-start gap-3 bg-blue-50 border border-blue-200 rounded-xl px-4 py-3">
          <svg class="w-4 h-4 text-blue-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          <p class="text-xs text-blue-700"><strong>Pengumuman:</strong> Survey akan diverifikasi oleh tim kami sebelum dipublikasikan.</p>
        </div>
        <div class="flex items-start gap-3 bg-amber-50 border border-amber-200 rounded-xl px-4 py-3">
          <svg class="w-4 h-4 text-amber-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          <p class="text-xs text-amber-800">Semua data wajib diisi lengkap. Formulir tidak lengkap tidak dapat diproses.</p>
        </div>
      </div>

    </div>

    {{-- RIGHT: Calculator Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

      {{-- Card Header --}}
      <div class="bg-orange-500 px-7 py-5">
        <h2 class="text-lg font-bold text-white flex items-center gap-2">
          <svg class="w-5 h-5 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M4 19h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
          Kalkulator Biaya
        </h2>
        <p class="text-white/70 text-xs mt-1">Isi form di bawah untuk melihat estimasi harga</p>
      </div>

      <div class="p-7 space-y-5">

        {{-- Title --}}
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1.5">Judul Survey</label>
          <input type="text" id="title"
            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:ring-2 focus:ring-orange-400 focus:border-transparent outline-none transition"
            placeholder="Contoh: Riset Kepuasan Pelanggan 2025">
        </div>

        {{-- Questions + Respondents (2 col) --}}
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Jml. Pertanyaan</label>
            <div class="relative">
              <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs font-bold">#</span>
              <input type="number" id="questions" min="1"
                class="w-full border border-gray-200 rounded-xl pl-7 pr-4 py-2.5 text-sm text-gray-900 focus:ring-2 focus:ring-orange-400 focus:border-transparent outline-none transition"
                placeholder="5">
            </div>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Jml. Responden</label>
            <div class="relative">
              <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs font-bold">#</span>
              <input type="number" id="respondents" min="1"
                class="w-full border border-gray-200 rounded-xl pl-7 pr-4 py-2.5 text-sm text-gray-900 focus:ring-2 focus:ring-orange-400 focus:border-transparent outline-none transition"
                placeholder="100">
            </div>
          </div>
        </div>

        {{-- Google Form Link --}}
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1.5">Link Form Survey <span class="text-red-500">*</span></label>
          <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
            <input type="text" id="googleFormLink" name="google_form_link"
              class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 text-sm text-gray-900 focus:ring-2 focus:ring-orange-400 focus:border-transparent outline-none transition"
              placeholder="https://forms.gle/...">
          </div>
          <p class="text-xs text-gray-500 mt-1.5">
            Link wajib diisi. Sistem memvalidasi URL form dan mengecek kecocokan judul.
            Platform didukung: Google Forms, Microsoft Forms, Typeform, Jotform, Tally, Formstack.
          </p>
        </div>

        {{-- User Type --}}
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1.5">Jenis Pengguna</label>
          <select id="userType"
            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:ring-2 focus:ring-orange-400 focus:border-transparent outline-none transition appearance-none bg-white">
            <option value="">— Pilih jenis pengguna —</option>
            <option value="mahasiswa">🎓 Mahasiswa  (Diskon 50%)</option>
            <option value="perusahaan">🏢 Perusahaan (Diskon 30%)</option>
            <option value="umum">👤 Umum       (Tanpa Diskon)</option>
          </select>
        </div>

        {{-- RESULT BOX --}}
        <div id="resultBox" class="hidden">
          <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 mb-4">
            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">Rincian Biaya</h3>
            <div class="space-y-2">
              <div class="flex justify-between text-sm">
                <span class="text-gray-600">Biaya Pertanyaan</span>
                <span id="questionPrice" class="font-semibold text-gray-900">Rp 0</span>
              </div>
              <div class="flex justify-between text-sm">
                <span class="text-gray-600">Jumlah Responden</span>
                <span id="respondentCount" class="font-semibold text-gray-900">0</span>
              </div>
              <div class="flex justify-between text-sm">
                <span class="text-gray-600">Diskon</span>
                <span id="discountLabel" class="font-semibold text-emerald-600">-</span>
              </div>
              <div class="border-t border-gray-200 pt-3 mt-3 flex justify-between items-center">
                <span class="text-sm font-bold text-gray-900">Total Setelah Diskon</span>
                <span id="totalCost" class="text-2xl font-extrabold text-orange-500">Rp 0</span>
              </div>
              <p id="minWarning" class="hidden text-xs text-red-500 font-medium text-right">⚠ Minimal Rp 50.000</p>
            </div>
          </div>

          {{-- Order Form --}}
          <form id="orderForm" method="POST" action="{{ route('transactions.store') }}" target="_blank">
            @csrf
            <input type="hidden" name="title" id="postTitle">
            <input type="hidden" name="question_count" id="postQuestions">
            <input type="hidden" name="respondent_count" id="postRespondents">
            <input type="hidden" name="items" id="postItems">
            <input type="hidden" name="total_cost" id="postTotalCost">
            <input type="hidden" name="link" id="postLink">
            <input type="hidden" name="user_type" id="postUserType">

            {{-- Checkbox S&K --}}
            <div class="flex items-start gap-2.5 mb-4">
              <input type="checkbox" id="agreeTerms"
                class="w-4 h-4 mt-0.5 rounded border-gray-300 text-orange-500 focus:ring-orange-400 cursor-pointer flex-shrink-0"
                onclick="handleCheckboxClick(event)">
              <label for="agreeTerms" class="text-xs text-gray-600 leading-snug select-none"
                     onclick="handleLabelClick(event)" style="cursor:pointer">
                Saya telah membaca dan menyetujui
                <span class="text-orange-500 font-semibold underline">Syarat &amp; Ketentuan</span>
                yang berlaku.
              </label>
            </div>

            <button id="submitButton" type="submit" disabled
              class="w-full py-3 rounded-xl font-bold text-sm transition-all duration-200
                     bg-orange-500 text-white hover:bg-orange-600 shadow-lg shadow-orange-500/30
                     disabled:bg-gray-200 disabled:text-gray-400 disabled:shadow-none disabled:cursor-not-allowed">
              Pesan Sekarang →
            </button>
          </form>
        </div>

        {{-- Placeholder sebelum isi form --}}
        <div id="calcPlaceholder" class="text-center py-6">
          <svg class="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M4 19h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
          <p class="text-sm text-gray-400">Isi jumlah pertanyaan & responden<br>untuk melihat estimasi harga</p>
        </div>

      </div>
    </div>
  </div>

  {{-- ── ADDITIONAL COST ── --}}
  <div class="mt-20">
    <div class="text-center mb-10">
      <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-2">Fitur <span class="text-orange-500">Tambahan</span></h2>
      <p class="text-gray-500 text-sm">Tersedia layanan tambahan dengan biaya menyesuaikan kebutuhan</p>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
      @php
        $features = [
          ['icon'=>'fa-th','label'=>'Grid Matrix Question'],
          ['icon'=>'fa-pen','label'=>'Priority Question'],
          ['icon'=>'fa-video','label'=>'Video on Question'],
          ['icon'=>'fa-mobile-alt','label'=>'Install Android App'],
          ['icon'=>'fa-camera','label'=>'Upload Photo'],
          ['icon'=>'fa-external-link-alt','label'=>'Web External Survey'],
          ['icon'=>'fa-edit','label'=>'Open Ended Short'],
          ['icon'=>'fa-file-alt','label'=>'Open Ended Long'],
          ['icon'=>'fa-globe','label'=>'See External Website'],
        ];
      @endphp
      @foreach($features as $f)
      <div class="bg-white border border-gray-200 rounded-xl p-5 text-center hover:border-orange-300 hover:shadow-md transition group">
        <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:bg-orange-100 transition">
          <i class="fas {{ $f['icon'] }} text-orange-500 text-base"></i>
        </div>
        <p class="text-xs font-semibold text-gray-700 leading-tight">{{ $f['label'] }}</p>
      </div>
      @endforeach
    </div>
    <p class="text-xs text-red-500 text-center mt-6 font-medium">* Harga dasar dapat meningkat tergantung tingkat kesulitan survey</p>
  </div>

  {{-- ── PRICE LEVEL TABLE ── --}}
  <div class="mt-20 bg-white border border-gray-200 rounded-2xl p-8 shadow-sm">
    <div class="text-center mb-8">
      <h2 class="text-2xl font-extrabold text-gray-900 mb-2">Price Level</h2>
      <p class="text-sm text-gray-500 max-w-xl mx-auto">
        Level harga berkisar antara 1× hingga 1.5×, tergantung tingkat kesulitan dalam mencapai target responden berdasarkan profil yang ditetapkan.
      </p>
    </div>
    <div class="grid md:grid-cols-2 gap-6 items-center">
      <div class="flex justify-center">
        <img src="{{ asset('assets/Harga-Survey-Center-1024x606.png') }}" alt="Price Level Table" class="rounded-xl shadow max-w-full">
      </div>
      <div class="flex justify-center">
        <img src="{{ asset('assets/incase-768x247.jpg') }}" alt="Price Formula" class="rounded-xl shadow max-w-full">
      </div>
    </div>
  </div>

</div>
</div>

{{-- ═══════════ SCRIPTS ═══════════ --}}
<script>
// ── Global: update submit state (price & checkbox) ──
function updateSubmitState() {
    const totalCostInput = document.getElementById('postTotalCost');
    const agreeTerms     = document.getElementById('agreeTerms');
    const submitButton   = document.getElementById('submitButton');
    if (!submitButton) return;
    const MIN = 50000;
    const price = parseInt(totalCostInput ? totalCostInput.value : 0) || 0;
    submitButton.disabled = !(price >= MIN && agreeTerms && agreeTerms.checked);
}

// ── Checkbox: open modal on check ──
function handleCheckboxClick(e) {
    const cb = document.getElementById('agreeTerms');
    if (!cb.checked) {
        e.preventDefault();
        openTermsModal();
    } else {
        updateSubmitState();
    }
}
function handleLabelClick(e) {
    e.preventDefault();
    const cb = document.getElementById('agreeTerms');
    if (!cb.checked) { openTermsModal(); }
    else { cb.checked = false; updateSubmitState(); }
}

// ── Calculator ──
document.addEventListener('DOMContentLoaded', () => {
    const questionInput  = document.getElementById('questions');
    const respondentInput= document.getElementById('respondents');
    const userTypeSelect = document.getElementById('userType');
    const resultBox      = document.getElementById('resultBox');
    const placeholder    = document.getElementById('calcPlaceholder');
    const MIN            = 50000;

    const fmt = n => 'Rp ' + n.toLocaleString('id-ID');

    function calculate() {
        const q = parseInt(questionInput.value) || 0;
        const r = parseInt(respondentInput.value) || 0;
        const t = userTypeSelect.value;

        if (q > 0 && r > 0) {
            const base   = q * r * 1000;
            let discount = 0, label = 'Tidak ada diskon';
            if (t === 'mahasiswa')  { discount = base * 0.5; label = 'Diskon 50% (Mahasiswa)'; }
            if (t === 'perusahaan') { discount = base * 0.3; label = 'Diskon 30% (Perusahaan)'; }
            const final = base - discount;

            document.getElementById('questionPrice').textContent   = fmt(q * 1000);
            document.getElementById('respondentCount').textContent = r;
            document.getElementById('discountLabel').textContent   = discount > 0 ? '-' + fmt(discount) + ' (' + label.split(' ')[1] + ')' : 'Tidak ada';
            document.getElementById('totalCost').textContent       = fmt(final);
            document.getElementById('minWarning').classList.toggle('hidden', final >= MIN);

            // fill hidden fields
            document.getElementById('postTitle').value      = document.getElementById('title').value;
            document.getElementById('postQuestions').value  = q;
            document.getElementById('postRespondents').value= r;
            document.getElementById('postLink').value       = document.getElementById('googleFormLink').value;
            document.getElementById('postTotalCost').value  = final;
            document.getElementById('postUserType').value   = t;
            document.getElementById('postItems').value      = JSON.stringify([
                {name:'Biaya Survey', quantity:1, unit_price: base},
                ...(discount > 0 ? [{name: label, quantity:1, unit_price: -discount}] : [])
            ]);

            resultBox.classList.remove('hidden');
            placeholder.classList.add('hidden');
            updateSubmitState();
        } else {
            resultBox.classList.add('hidden');
            placeholder.classList.remove('hidden');
        }
    }

    questionInput.addEventListener('input', calculate);
    respondentInput.addEventListener('input', calculate);
    userTypeSelect.addEventListener('change', calculate);

    // Submit guard
    document.getElementById('orderForm').addEventListener('submit', e => {
        const p = parseInt(document.getElementById('postTotalCost').value) || 0;
        if (p < MIN) { e.preventDefault(); alert('Total biaya minimal Rp 50.000'); return false; }
        const link = (document.getElementById('googleFormLink').value || '').trim();
        if (!link) { e.preventDefault(); alert('Link form wajib diisi'); return false; }
        if (!document.getElementById('agreeTerms').checked) { e.preventDefault(); alert('Setujui Syarat & Ketentuan terlebih dahulu'); return false; }
    });
});
</script>

{{-- ═══════════ MODAL SYARAT & KETENTUAN ═══════════ --}}
<div id="termsModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
  <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[85vh] flex flex-col overflow-hidden">
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 flex-shrink-0">
      <h3 class="text-lg font-bold text-gray-900">📄 Syarat &amp; Ketentuan</h3>
      <button id="modalCloseX" onclick="closeTermsModal()"
          class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:bg-gray-100"
          style="opacity:.3;cursor:not-allowed" disabled>
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>
    <div id="scrollHint" class="flex items-center gap-2 bg-orange-50 border-b border-orange-200 px-5 py-2 text-xs text-orange-700 font-medium flex-shrink-0">
      <svg class="w-3.5 h-3.5 animate-bounce flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
      Scroll sampai bawah untuk mengaktifkan tombol
    </div>
    <div id="termsBody" class="flex-1 overflow-y-auto px-6 py-5 terms-prose" onscroll="checkTermsScroll()">
      @if($terms)
        {!! $terms !!}
      @else
        <p class="text-gray-400 text-sm italic text-center py-8">Syarat &amp; ketentuan belum diatur oleh admin.</p>
      @endif
      <div class="h-6"></div>
    </div>
    <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between flex-shrink-0 bg-gray-50">
      <p id="scrollMsg" class="text-xs text-gray-400 italic">Scroll sampai bawah untuk melanjutkan</p>
      <div class="flex gap-3">
        <button id="modalCloseBtn" onclick="closeTermsModal()"
            class="px-4 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-200 transition"
            style="opacity:.3;cursor:not-allowed" disabled>Tutup</button>
        <button id="acceptBtn" onclick="acceptTerms()"
            class="px-5 py-2 text-sm font-bold text-white bg-orange-500 rounded-lg transition hover:bg-orange-600"
            style="opacity:.3;cursor:not-allowed" disabled>✅ Saya Setuju</button>
      </div>
    </div>
  </div>
</div>

<style>
.terms-prose h1{font-size:1.3rem;font-weight:700;margin-bottom:.5rem;color:#111827}
.terms-prose h2{font-size:1.1rem;font-weight:700;margin-bottom:.5rem;color:#1f2937}
.terms-prose p{margin-bottom:.75rem;color:#374151;font-size:.875rem;line-height:1.65}
.terms-prose ul{list-style:disc;padding-left:1.5rem;margin-bottom:.75rem}
.terms-prose ol{list-style:decimal;padding-left:1.5rem;margin-bottom:.75rem}
.terms-prose li{margin-bottom:.25rem;color:#374151;font-size:.875rem}
.terms-prose strong{font-weight:700}
.terms-prose blockquote{border-left:4px solid #fbbf24;padding-left:1rem;color:#6b7280;margin:.75rem 0}
</style>

<script>
function openTermsModal(){
    const m=document.getElementById('termsModal');
    m.classList.remove('hidden'); m.classList.add('flex');
    const body=document.getElementById('termsBody');
    body.scrollTop=0; setScrolledState(false);
    setTimeout(checkTermsScroll,150);
}
function closeTermsModal(){
    const m=document.getElementById('termsModal');
    m.classList.add('hidden'); m.classList.remove('flex');
}
function acceptTerms(){
    document.getElementById('agreeTerms').checked=true;
    updateSubmitState(); closeTermsModal();
}
function checkTermsScroll(){
    const b=document.getElementById('termsBody');
    if(!b) return;
    if(b.scrollTop+b.clientHeight>=b.scrollHeight-40) setScrolledState(true);
}
function setScrolledState(done){
    ['acceptBtn','modalCloseBtn','modalCloseX'].forEach(id=>{
        const el=document.getElementById(id);
        if(!el) return;
        el.disabled=!done;
        el.style.opacity=done?'1':'.3';
        el.style.cursor=done?'pointer':'not-allowed';
    });
    const hint=document.getElementById('scrollHint');
    const msg=document.getElementById('scrollMsg');
    if(done){
        if(hint) hint.classList.add('hidden');
        if(msg){msg.textContent='Anda telah membaca seluruh syarat & ketentuan.';msg.className='text-xs text-green-600 font-medium';}
    }
}
document.addEventListener('keydown',e=>{
    if(e.key==='Escape'){const b=document.getElementById('modalCloseBtn');if(b&&!b.disabled)closeTermsModal();}
});
</script>

@endsection
