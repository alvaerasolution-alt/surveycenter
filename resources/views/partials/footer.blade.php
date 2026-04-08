@php
  $settings = \App\Models\Setting::whereIn('key', [
      'footer_alamat',
      'footer_whatsapp',
      'footer_email',
      'sosmed_facebook',
      'sosmed_twitter',
      'sosmed_linkedin',
      'sosmed_instagram'
  ])->pluck('value', 'key');
@endphp
{{-- Footer --}}
<footer class="bg-orange-500 text-white pt-12 pb-6 px-4 sm:px-8">
  <div class="max-w-6xl mx-auto">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-10">

      {{-- Kolom 1: Company Info + Layanan --}}
      <div>
        <div class="flex items-center gap-3 mb-4">
          <img src="{{ asset('assets/logosc.png') }}" alt="Logo" class="w-12 h-12 object-contain drop-shadow">
          <div>
            <h3 class="font-extrabold text-base leading-tight">Survey Center<br>Indonesia</h3>
            <p class="text-xs text-orange-200 mt-0.5">PT. Market Research & Branding</p>
          </div>
        </div>
        <p class="text-sm text-orange-100 leading-relaxed mb-4">
          Survey Center membantu anda melakukan survey online maupun offline sesuai target responden, serta distribusi data dan pemetaan pasar.
        </p>
      </div>

      {{-- Kolom 2: Layanan Penelitian --}}
      <div>
        <h3 class="font-extrabold text-base mb-4">Layanan Penelitian</h3>
        <ul class="space-y-2 text-sm text-orange-100">
          <li><a href="/layanan/survei-kepuasan-pelanggan" class="hover:text-white transition">Survei Kepuasan Pelanggan</a></li>
          <li><a href="/layanan/survei-potensi-pasar" class="hover:text-white transition">Survei Potensi Pasar</a></li>
          <li><a href="/layanan/survei-loyalitas-pelanggan" class="hover:text-white transition">Survei Loyalitas Pelanggan</a></li>
          <li><a href="/layanan/survei-pengembangan-produk-jasa" class="hover:text-white transition">Survei Pengembangan Produk/Jasa</a></li>
          <li><a href="/layanan/survei-pengukuran-indeks-kepusasan-masyarakat" class="hover:text-white transition">Survei Pengukuran Indeks Kepuasan</a></li>
          <li><a href="/layanan/survei-brand-awareness" class="hover:text-white transition">Survei Brand Awareness</a></li>
          <li><a href="/layanan/survei-segmentasi-dan-positioning" class="hover:text-white transition">Survei Segmentasi Dan Positioning</a></li>
        </ul>
      </div>

      {{-- Kolom 3: PT. Contact + Social --}}
      <div>
        <h3 class="font-extrabold text-base mb-2">PT. Market Research & Branding</h3>
        <p class="text-xs text-orange-200 mb-3">(Survey Center Indonesia)</p>
        <p class="text-sm text-orange-100 leading-relaxed mb-4 whitespace-pre-line">
          {{ $settings['footer_alamat'] ?? "Scientia Residences Tower C, Lantai II,\nJl. Scientia Square Utara,\nKel. Curug Sangereng, Kec. Kelapa Dua,\nKab. Tangerang, Banten, 15810" }}
        </p>

        <h4 class="font-bold text-sm mb-3">Ikuti Kami</h4>
        <div class="flex gap-3">
          @if(!empty($settings['sosmed_instagram']))
          <a href="{{ $settings['sosmed_instagram'] }}" target="_blank"
             class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center hover:bg-white/40 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
              <path d="M7.75 2C4.7 2 2 4.7 2 7.75v8.5C2 19.3 4.7 22 7.75 22h8.5c3.05 0 5.75-2.7 5.75-5.75v-8.5C22 4.7 19.3 2 16.25 2h-8.5zM12 7a5 5 0 110 10A5 5 0 0112 7zm0 1.8a3.2 3.2 0 100 6.4 3.2 3.2 0 000-6.4zm4.95-.7a1.05 1.05 0 110 2.1 1.05 1.05 0 010-2.1z"/>
            </svg>
          </a>
          @endif
          @if(!empty($settings['footer_whatsapp']))
          <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['footer_whatsapp']) }}" target="_blank"
             class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center hover:bg-white/40 transition">
            <svg fill="currentColor" class="w-4 h-4 text-white" viewBox="0 0 32 32">
              <path d="M26.576 5.363c-2.69-2.69-6.406-4.354-10.511-4.354-8.209 0-14.865 6.655-14.865 14.865 0 2.732.737 5.291 2.022 7.491l-2.147 7.702 7.879-2.067c2.051 1.139 4.498 1.809 7.102 1.809h.006c8.209-.003 14.862-6.659 14.862-14.868 0-4.103-1.662-7.817-4.349-10.507v0zM16.062 28.228h-.005c-2.319 0-4.489-.64-6.342-1.753l-.451-.267-4.675 1.227 1.247-4.559-.294-.467c-1.185-1.862-1.889-4.131-1.889-6.565 0-6.822 5.531-12.353 12.353-12.353s12.353 5.531 12.353 12.353c0 6.822-5.53 12.353-12.353 12.353v0zM22.838 18.977c-.371-.186-2.197-1.083-2.537-1.208s-.589-.185-.837.187c-.246.371-.958 1.207-1.175 1.455s-.434.279-.805.094c-1.15-.466-2.138-1.087-2.997-1.852-0.799-.74-1.484-1.587-2.037-2.521-.216-.371-.023-.572.162-.757.167-.166.372-.434.557-.65.146-.179.271-.384.366-.604.043-.087.068-.188.068-.296 0-.131-.037-.253-.101-.357-.094-.186-.836-2.014-1.145-2.758-.302-.724-.609-.625-.836-.637-.216-.01-.464-.012-.712-.012-.395.01-.746.188-.988.463-.802.761-1.3 1.834-1.3 3.023 0 .026 0 .053.001.079.131 1.467.681 2.784 1.527 3.857.548.248 1.25.513 1.968.74.442.14.951.221 1.479.221.303 0 .601-.027.889-.078 1.069-.223 1.956-.868 2.497-1.749.165-.366.261-.793.261-1.242 0-.185-.016-.366-.047-.542-.092-.155-.34-.247-.712-.434z"/>
            </svg>
          </a>
          @else
          <a href="https://wa.me/+6285198887963" target="_blank"
             class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center hover:bg-white/40 transition">
            <svg fill="currentColor" class="w-4 h-4 text-white" viewBox="0 0 32 32">
              <path d="M26.576 5.363c-2.69-2.69-6.406-4.354-10.511-4.354-8.209 0-14.865 6.655-14.865 14.865 0 2.732.737 5.291 2.022 7.491l-2.147 7.702 7.879-2.067c2.051 1.139 4.498 1.809 7.102 1.809h.006c8.209-.003 14.862-6.659 14.862-14.868 0-4.103-1.662-7.817-4.349-10.507v0zM16.062 28.228h-.005c-2.319 0-4.489-.64-6.342-1.753l-.451-.267-4.675 1.227 1.247-4.559-.294-.467c-1.185-1.862-1.889-4.131-1.889-6.565 0-6.822 5.531-12.353 12.353-12.353s12.353 5.531 12.353 12.353c0 6.822-5.53 12.353-12.353 12.353v0zM22.838 18.977c-.371-.186-2.197-1.083-2.537-1.208s-.589-.185-.837.187c-.246.371-.958 1.207-1.175 1.455s-.434.279-.805.094c-1.15-.466-2.138-1.087-2.997-1.852-0.799-.74-1.484-1.587-2.037-2.521-.216-.371-.023-.572.162-.757.167-.166.372-.434.557-.65.146-.179.271-.384.366-.604.043-.087.068-.188.068-.296 0-.131-.037-.253-.101-.357-.094-.186-.836-2.014-1.145-2.758-.302-.724-.609-.625-.836-.637-.216-.01-.464-.012-.712-.012-.395.01-.746.188-.988.463-.802.761-1.3 1.834-1.3 3.023 0 .026 0 .053.001.079.131 1.467.681 2.784 1.527 3.857.548.248 1.25.513 1.968.74.442.14.951.221 1.479.221.303 0 .601-.027.889-.078 1.069-.223 1.956-.868 2.497-1.749.165-.366.261-.793.261-1.242 0-.185-.016-.366-.047-.542-.092-.155-.34-.247-.712-.434z"/>
            </svg>
          </a>
          @endif
          @if(!empty($settings['sosmed_facebook']))
          <a href="{{ $settings['sosmed_facebook'] }}" target="_blank"
             class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center hover:bg-white/40 transition">
            <svg fill="currentColor" class="w-4 h-4 text-white" viewBox="0 0 24 24">
              <path d="M12 2C6.48 2 2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c5.05-.5 9-4.76 9-9.95 0-5.52-4.48-10-10-10z"/>
            </svg>
          </a>
          @endif
          @if(!empty($settings['sosmed_twitter']))
          <a href="{{ $settings['sosmed_twitter'] }}" target="_blank"
             class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center hover:bg-white/40 transition">
             <form style="display:none"></form>
            <svg fill="currentColor" class="w-4 h-4 text-white" viewBox="0 0 24 24">
              <path d="M24 4.557a9.83 9.83 0 01-2.828.775 4.932 4.932 0 002.165-2.724 9.864 9.864 0 01-3.127 1.195 4.916 4.916 0 00-8.384 4.482A13.94 13.94 0 011.671 3.149a4.916 4.916 0 001.523 6.574 4.903 4.903 0 01-2.229-.616c-.054 2.281 1.581 4.415 3.949 4.89a4.935 4.935 0 01-2.224.084 4.92 4.92 0 004.588 3.417A9.867 9.867 0 010 19.54a13.94 13.94 0 007.548 2.212c9.057 0 14.01-7.502 14.01-14.01 0-.213-.005-.425-.014-.636A10.025 10.025 0 0024 4.557z"/>
            </svg>
          </a>
          @endif
          @if(!empty($settings['sosmed_linkedin']))
          <a href="{{ $settings['sosmed_linkedin'] }}" target="_blank"
             class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center hover:bg-white/40 transition">
             <form style="display:none"></form>
            <svg fill="currentColor" class="w-4 h-4 text-white" viewBox="0 0 24 24">
              <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
            </svg>
          </a>
          @endif

        </div>
      </div>

    </div>

    {{-- Bottom bar --}}
    <div class="border-t border-white/20 mt-10 pt-5 text-center">
      <p class="text-xs text-orange-200">© 2026 Survey Center Indonesia. All rights reserved.</p>
    </div>
  </div>
</footer>