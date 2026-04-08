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
<footer id="contact" class="bg-gray-900 text-white py-10">
  <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-3 gap-8">
    <div>
      <h3 class="font-bold mb-4 text-yellow-500">SurveyCenter Indonesia</h3>
      <p class="whitespace-pre-line">{{ $settings['footer_alamat'] ?? "Garden Avenue Rasuna, The Habitat Lt. 5\nJakarta Selatan" }}</p>
      <p>WhatsApp: {{ $settings['footer_whatsapp'] ?? '+62 851-9888-7963' }}</p>
      <p>Email: {{ $settings['footer_email'] ?? 'info@surveycenter.co.id' }}</p>
    </div>
    <div>
      <h3 class="font-bold mb-4 text-yellow-500">Navigasi</h3>
      <ul class="space-y-2">
        <li><a href="#about" class="hover:text-yellow-500">Tentang</a></li>
        <li><a href="#services" class="hover:text-yellow-500">Layanan</a></li>
        <li><a href="#projects" class="hover:text-yellow-500">Proyek</a></li>
        <li><a href="#insights" class="hover:text-yellow-500">Insights</a></li>
      </ul>
    </div>
    <div>
      <h3 class="font-bold mb-4 text-yellow-500">Ikuti Kami</h3>
      <div class="flex space-x-4">
        @if(!empty($settings['sosmed_facebook']))
        <a href="{{ $settings['sosmed_facebook'] }}"><i class="fab fa-facebook text-2xl hover:text-yellow-500"></i></a>
        @endif
        @if(!empty($settings['sosmed_twitter']))
        <a href="{{ $settings['sosmed_twitter'] }}"><i class="fab fa-twitter text-2xl hover:text-yellow-500"></i></a>
        @endif
        @if(!empty($settings['sosmed_linkedin']))
        <a href="{{ $settings['sosmed_linkedin'] }}"><i class="fab fa-linkedin text-2xl hover:text-yellow-500"></i></a>
        @endif
        @if(!empty($settings['sosmed_instagram']))
        <a href="{{ $settings['sosmed_instagram'] }}"><i class="fab fa-instagram text-2xl hover:text-yellow-500"></i></a>
        @endif
      </div>
    </div>
  </div>
  <div class="text-center text-sm text-gray-500 mt-6">© 2025 SurveyCenter Indonesia. All rights reserved.</div>
</footer>
