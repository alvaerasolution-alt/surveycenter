@php
  $settings = \App\Models\Setting::whereIn('key', [
      'footer_whatsapp',
      'sosmed_instagram',
      'sosmed_tiktok'
  ])->pluck('value', 'key');
@endphp

<!-- Floating Social Buttons - Right Side -->
<div class="fixed top-1/2 right-0 z-50 transform -translate-y-1/2 flex flex-col items-end gap-2">

  <!-- WhatsApp Button -->
  @if(!empty($settings['footer_whatsapp']))
  <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['footer_whatsapp']) }}" target="_blank"
     aria-label="Chat on WhatsApp" class="group flex items-center justify-end">
    <div class="bg-green-500 w-10 h-10 rounded-l-xl rounded-r-none flex items-center justify-center shadow-lg
                group-hover:w-12 group-hover:brightness-110 transition-all duration-300">
      <i class="fab fa-whatsapp text-white text-lg"></i>
    </div>
  </a>
  @else
  <a href="https://wa.me/+6285198887963" target="_blank"
     aria-label="Chat on WhatsApp" class="group flex items-center justify-end">
    <div class="bg-green-500 w-10 h-10 rounded-l-xl rounded-r-none flex items-center justify-center shadow-lg
                group-hover:w-12 group-hover:brightness-110 transition-all duration-300">
      <i class="fab fa-whatsapp text-white text-lg"></i>
    </div>
  </a>
  @endif

  <!-- Instagram Button -->
  @if(!empty($settings['sosmed_instagram']))
  <a href="{{ $settings['sosmed_instagram'] }}" target="_blank"
     aria-label="Follow on Instagram" class="group flex items-center justify-end">
    <div class="w-10 h-10 rounded-l-xl rounded-r-none flex items-center justify-center shadow-lg
                bg-gradient-to-br from-purple-500 via-pink-500 to-orange-400
                group-hover:w-12 group-hover:brightness-110 transition-all duration-300">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
        <path d="M7.75 2h8.5A5.75 5.75 0 0 1 22 7.75v8.5A5.75 5.75 0 0 1 16.25 22h-8.5A5.75 5.75 0 0 1 2 16.25v-8.5A5.75 5.75 0 0 1 7.75 2zm0 1.5A4.25 4.25 0 0 0 3.5 7.75v8.5A4.25 4.25 0 0 0 7.75 20.5h8.5a4.25 4.25 0 0 0 4.25-4.25v-8.5A4.25 4.25 0 0 0 16.25 3.5h-8.5zm8.75 2.75a.75.75 0 1 1 0 1.5.75.75 0 0 1 0-1.5zm-4.75 1a5 5 0 1 1 0 10 5 5 0 0 1 0-10zm0 1.5a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7z"/>
      </svg>
    </div>
  </a>
  @endif

  <!-- TikTok Button -->
  @if(!empty($settings['sosmed_tiktok']))
  <a href="{{ $settings['sosmed_tiktok'] }}" target="_blank"
     aria-label="Follow on TikTok" class="group flex items-center justify-end">
    <div class="w-10 h-10 rounded-l-xl rounded-r-none flex items-center justify-center shadow-lg
                bg-gray-900
                group-hover:w-12 group-hover:brightness-125 transition-all duration-300">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" class="w-5 h-5" fill="white">
        <path d="M176.35,24H152a8,8,0,0,0-8,8V156a20,20,0,1,1-20-20,8,8,0,0,0,8-8V104a8,8,0,0,0-8-8A52,52,0,1,0,184,148V98.22a91.12,91.12,0,0,0,40,9.47,8,8,0,0,0,8-8V76.32a8,8,0,0,0-7.12-8A56,56,0,0,1,176.35,24Z"/>
      </svg>
    </div>
  </a>
  @endif

</div>

<!-- Font Awesome CSS untuk WhatsApp icon -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

