@php
  $settings = \App\Models\Setting::whereIn('key', [
      'footer_whatsapp',
      'sosmed_instagram',
      'sosmed_tiktok',
      'popup_wa_enabled',
      'popup_wa_title',
      'popup_wa_subtitle',
      'popup_admin_number',
  ])->pluck('value', 'key');

  $waNumber     = preg_replace('/[^0-9]/', '', $settings['footer_whatsapp'] ?? '6285198887963');
  $adminNumber  = preg_replace('/[^0-9]/', '', $settings['popup_admin_number'] ?? $waNumber);
  $popupEnabled = ($settings['popup_wa_enabled'] ?? '1') === '1';
  $popupTitle   = $settings['popup_wa_title']    ?? 'Hubungi via WhatsApp';
  $popupSubtitle= $settings['popup_wa_subtitle'] ?? 'Isi data berikut untuk melanjutkan';
@endphp

{{-- ===== FLOATING SOCIAL BUTTONS ===== --}}
<div class="fixed top-1/2 right-0 z-50 transform -translate-y-1/2 flex flex-col items-end gap-2">

  {{-- WhatsApp --}}
  @if($popupEnabled)
  <button onclick="openWaPopup()" aria-label="Chat on WhatsApp"
          class="group flex items-center justify-end focus:outline-none">
    <div class="bg-green-500 w-10 h-10 rounded-l-xl rounded-r-none flex items-center justify-center shadow-lg
                group-hover:w-12 group-hover:brightness-110 transition-all duration-300">
      <i class="fab fa-whatsapp text-white text-lg"></i>
    </div>
  </button>
  @else
  <a href="https://wa.me/{{ $waNumber }}" target="_blank" aria-label="Chat on WhatsApp"
     class="group flex items-center justify-end">
    <div class="bg-green-500 w-10 h-10 rounded-l-xl rounded-r-none flex items-center justify-center shadow-lg
                group-hover:w-12 group-hover:brightness-110 transition-all duration-300">
      <i class="fab fa-whatsapp text-white text-lg"></i>
    </div>
  </a>
  @endif

  {{-- Instagram --}}
  @if(!empty($settings['sosmed_instagram']))
  <a href="{{ $settings['sosmed_instagram'] }}" target="_blank" aria-label="Follow on Instagram"
     class="group flex items-center justify-end">
    <div class="w-10 h-10 rounded-l-xl rounded-r-none flex items-center justify-center shadow-lg
                bg-gradient-to-br from-purple-500 via-pink-500 to-orange-400
                group-hover:w-12 group-hover:brightness-110 transition-all duration-300">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
        <path d="M7.75 2h8.5A5.75 5.75 0 0 1 22 7.75v8.5A5.75 5.75 0 0 1 16.25 22h-8.5A5.75 5.75 0 0 1 2 16.25v-8.5A5.75 5.75 0 0 1 7.75 2zm0 1.5A4.25 4.25 0 0 0 3.5 7.75v8.5A4.25 4.25 0 0 0 7.75 20.5h8.5a4.25 4.25 0 0 0 4.25-4.25v-8.5A4.25 4.25 0 0 0 16.25 3.5h-8.5zm8.75 2.75a.75.75 0 1 1 0 1.5.75.75 0 0 1 0-1.5zm-4.75 1a5 5 0 1 1 0 10 5 5 0 0 1 0-10zm0 1.5a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7z"/>
      </svg>
    </div>
  </a>
  @endif

  {{-- TikTok --}}
  @if(!empty($settings['sosmed_tiktok']))
  <a href="{{ $settings['sosmed_tiktok'] }}" target="_blank" aria-label="Follow on TikTok"
     class="group flex items-center justify-end">
    <div class="w-10 h-10 rounded-l-xl rounded-r-none flex items-center justify-center shadow-lg bg-gray-900
                group-hover:w-12 group-hover:brightness-125 transition-all duration-300">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" class="w-5 h-5" fill="white">
        <path d="M176.35,24H152a8,8,0,0,0-8,8V156a20,20,0,1,1-20-20,8,8,0,0,0,8-8V104a8,8,0,0,0-8-8A52,52,0,1,0,184,148V98.22a91.12,91.12,0,0,0,40,9.47,8,8,0,0,0,8-8V76.32a8,8,0,0,0-7.12-8A56,56,0,0,1,176.35,24Z"/>
      </svg>
    </div>
  </a>
  @endif

</div>

{{-- ===== POPUP FORM WHATSAPP ===== --}}
@if($popupEnabled)
<div id="waPopupOverlay"
     class="fixed inset-0 z-[9999] bg-black/50 backdrop-blur-sm hidden items-end sm:items-center justify-center p-4"
     onclick="closeWaPopup(event)">

  <div id="waPopupCard"
       class="bg-white w-full max-w-sm rounded-2xl shadow-2xl overflow-hidden
              translate-y-8 opacity-0 transition-all duration-300"
       onclick="event.stopPropagation()">

    {{-- Header --}}
    <div class="bg-gradient-to-r from-green-500 to-green-600 px-5 py-4 flex items-start gap-3 relative">
      <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
        <i class="fab fa-whatsapp text-white text-xl"></i>
      </div>
      <div>
        <h3 class="text-white font-bold text-base leading-tight">{{ $popupTitle }}</h3>
        <p class="text-green-100 text-sm mt-0.5">{{ $popupSubtitle }}</p>
      </div>
      <button onclick="closeWaPopup()" class="absolute top-3 right-3 text-white/70 hover:text-white transition p-1">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>

    {{-- Form Body --}}
    <div class="p-5 space-y-4">

      {{-- Nama --}}
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
          Nama Lengkap <span class="text-red-500">*</span>
        </label>
        <input id="waName" type="text" placeholder="Masukkan nama Anda"
               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800
                      focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent
                      placeholder-gray-400 transition">
      </div>

      {{-- Nomor WA --}}
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
          Nomor WhatsApp <span class="text-red-500">*</span>
        </label>
        <div class="relative">
          <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
            </svg>
          </div>
          <input id="waPhone" type="tel" placeholder="08xxxxxxxxxx"
                 class="w-full border border-gray-200 rounded-xl pl-9 pr-4 py-2.5 text-sm text-gray-800
                        focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent
                        placeholder-gray-400 transition">
        </div>
      </div>

      {{-- Nama Perusahaan --}}
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
          Nama Perusahaan <span class="text-xs font-normal text-gray-400">(opsional)</span>
        </label>
        <input id="waCompany" type="text" placeholder="Nama perusahaan Anda"
               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800
                      focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent
                      placeholder-gray-400 transition">
      </div>

      {{-- Error message --}}
      <p id="waError" class="text-red-500 text-xs hidden">Nama dan nomor WhatsApp wajib diisi.</p>

      {{-- Submit --}}
      <button id="waSubmitBtn" onclick="submitWaForm()"
              class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-xl
                     flex items-center justify-center gap-2 transition-all duration-200 shadow-md hover:shadow-lg">
        <i class="fab fa-whatsapp text-lg"></i>
        Kirim & Buka WhatsApp
      </button>

      <p class="text-center text-xs text-gray-400 leading-relaxed">
        Dengan mengirim formulir ini, Anda menyetujui kami untuk<br>menghubungi Anda.
      </p>
    </div>
  </div>
</div>

<script>
  function openWaPopup() {
    const overlay = document.getElementById('waPopupOverlay');
    const card    = document.getElementById('waPopupCard');
    overlay.classList.remove('hidden');
    overlay.classList.add('flex');
    document.body.style.overflow = 'hidden';
    requestAnimationFrame(() => {
      card.classList.remove('translate-y-8', 'opacity-0');
      card.classList.add('translate-y-0', 'opacity-100');
    });
  }

  function closeWaPopup(e) {
    if (e && e.target !== document.getElementById('waPopupOverlay')) return;
    const overlay = document.getElementById('waPopupOverlay');
    const card    = document.getElementById('waPopupCard');
    card.classList.add('translate-y-8', 'opacity-0');
    card.classList.remove('translate-y-0', 'opacity-100');
    setTimeout(() => {
      overlay.classList.add('hidden');
      overlay.classList.remove('flex');
      document.body.style.overflow = '';
    }, 300);
  }

  // Close with Escape key
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
      const overlay = document.getElementById('waPopupOverlay');
      if (!overlay.classList.contains('hidden')) {
        overlay.dispatchEvent(new MouseEvent('click', { bubbles: false }));
        const card = document.getElementById('waPopupCard');
        card.classList.add('translate-y-8', 'opacity-0');
        card.classList.remove('translate-y-0', 'opacity-100');
        setTimeout(() => {
          overlay.classList.add('hidden');
          overlay.classList.remove('flex');
          document.body.style.overflow = '';
        }, 300);
      }
    }
  });

  function submitWaForm() {
    const name    = document.getElementById('waName').value.trim();
    const phone   = document.getElementById('waPhone').value.trim();
    const company = document.getElementById('waCompany').value.trim();
    const errEl   = document.getElementById('waError');
    const btn     = document.getElementById('waSubmitBtn');

    if (!name || !phone) {
      errEl.classList.remove('hidden');
      return;
    }
    errEl.classList.add('hidden');

    // Loading state
    btn.disabled = true;
    btn.innerHTML = '<svg class="animate-spin w-5 h-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path></svg> Mengirim...';

    // Kirim ke CRM
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

    fetch('{{ route("whatsapp.lead.store") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': csrfToken,
        'X-API-KEY': 'MXuMiiKBC898/dclL1g0+Hy1wyUgvXMI3KiUdCCuG8U=',
      },
      body: JSON.stringify({
        full_name: name,
        phone:     phone,
        company:   company,
        source:    'popup_wa',
      }),
    })
    .then(r => r.json())
    .then(data => {
      if (data.redirect) {
        window.open(data.redirect, '_blank');
      }
      // Reset & tutup popup
      document.getElementById('waName').value    = '';
      document.getElementById('waPhone').value   = '';
      document.getElementById('waCompany').value = '';
      _closeWaAnim();
    })
    .catch(() => {
      // Fallback: buka WA langsung tanpa simpan CRM
      const adminNumber = '{{ $adminNumber }}';
      const msg = `Halo, saya ingin bertanya.%0ANama: *${name}*%0ANo. HP: ${phone}${company ? '%0APerusahaan: ' + company : ''}`;
      window.open(`https://wa.me/${adminNumber}?text=${msg}`, '_blank');
      _closeWaAnim();
    })
    .finally(() => {
      btn.disabled = false;
      btn.innerHTML = '<i class="fab fa-whatsapp text-lg"></i> Kirim & Buka WhatsApp';
    });
  }

  function _closeWaAnim() {
    const overlay = document.getElementById('waPopupOverlay');
    const card    = document.getElementById('waPopupCard');
    card.classList.add('translate-y-8', 'opacity-0');
    card.classList.remove('translate-y-0', 'opacity-100');
    setTimeout(() => {
      overlay.classList.add('hidden');
      overlay.classList.remove('flex');
      document.body.style.overflow = '';
    }, 300);
  }
</script>
@endif

{{-- Font Awesome for WhatsApp icon --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
