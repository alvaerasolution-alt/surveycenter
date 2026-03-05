@extends('layouts.app')
@section('seo_slug', 'contact')

@section('content')

{{-- ═══ HERO ═══ --}}
<section class="bg-orange-500 py-16 px-4">
  <div class="max-w-2xl mx-auto text-center">
    <span class="inline-block bg-white/20 border border-white/30 text-white text-xs font-bold uppercase tracking-widest px-4 py-1.5 rounded-full mb-4">
      📩 Hubungi Kami
    </span>
    <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-3">Ada yang bisa kami bantu?</h1>
    <p class="text-white/80 text-base md:text-lg">
      Tim kami siap membantu kebutuhan riset dan survei Anda. Kirim pesan atau hubungi langsung via WhatsApp.
    </p>
  </div>
</section>

{{-- ═══ MAIN CONTENT ═══ --}}
<div class="bg-gray-50 py-16 px-4">
<div class="max-w-6xl mx-auto">

  {{-- Success alert --}}
  @if (session('success'))
    <div class="mb-8 flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 px-5 py-3 rounded-xl text-sm max-w-2xl mx-auto">
      <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      {{ session('success') }}
    </div>
  @endif

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">

    {{-- LEFT: Contact Info --}}
    <div class="space-y-6">
      <div>
        <h2 class="text-2xl font-extrabold text-gray-900 mb-2">Informasi Kontak</h2>
        <p class="text-gray-500 text-sm leading-relaxed">
          Kami adalah tim riset profesional siap membantu Anda merancang, mendistribusikan, dan menganalisis survei secara akurat.
        </p>
      </div>

      {{-- Info Cards --}}
      <div class="space-y-4">

        {{-- WhatsApp --}}
        <a href="https://wa.me/6285198887963" target="_blank"
           class="flex items-center gap-4 bg-white border border-gray-200 rounded-xl p-5 hover:border-orange-300 hover:shadow-md transition group">
          <div class="w-11 h-11 rounded-xl bg-green-100 flex items-center justify-center flex-shrink-0 group-hover:bg-green-200 transition">
            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 32 32">
              <path d="M26.576 5.363c-2.69-2.69-6.406-4.354-10.511-4.354-8.209 0-14.865 6.655-14.865 14.865 0 2.732.737 5.291 2.022 7.491l-2.147 7.702 7.879-2.067c2.051 1.139 4.498 1.809 7.102 1.809h.006c8.209-.003 14.862-6.659 14.862-14.868 0-4.103-1.662-7.817-4.349-10.507v0zM16.062 28.228h-.005c-2.319 0-4.489-.64-6.342-1.753l-.451-.267-4.675 1.227 1.247-4.559-.294-.467c-1.185-1.862-1.889-4.131-1.889-6.565 0-6.822 5.531-12.353 12.353-12.353s12.353 5.531 12.353 12.353c0 6.822-5.53 12.353-12.353 12.353v0z"/>
            </svg>
          </div>
          <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-0.5">WhatsApp</p>
            <p class="text-sm font-bold text-gray-900 group-hover:text-green-600 transition">+62 851-9888-7963</p>
            <p class="text-xs text-gray-400">Klik untuk chat langsung</p>
          </div>
          <svg class="w-4 h-4 text-gray-300 ml-auto group-hover:text-orange-500 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>

        {{-- Alamat --}}
        <div class="flex items-start gap-4 bg-white border border-gray-200 rounded-xl p-5">
          <div class="w-11 h-11 rounded-xl bg-orange-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
          </div>
          <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-0.5">Alamat</p>
            <p class="text-sm font-semibold text-gray-900 leading-snug">Scientia Residences Tower C, Lantai II</p>
            <p class="text-xs text-gray-500 leading-relaxed mt-0.5">Jl. Scientia Square Utara, Kel. Curug Sangereng,<br>Kec. Kelapa Dua, Kab. Tangerang, Banten, 15810</p>
          </div>
        </div>

        {{-- Email / Instagram --}}
        <div class="grid grid-cols-2 gap-4">
          <a href="https://www.instagram.com/surveycenterindonesia/" target="_blank"
             class="flex flex-col items-center gap-2 bg-white border border-gray-200 rounded-xl p-4 hover:border-pink-300 hover:shadow-sm transition group text-center">
            <div class="w-9 h-9 rounded-xl bg-pink-100 flex items-center justify-center group-hover:bg-pink-200 transition">
              <svg class="w-4 h-4 text-pink-600" fill="currentColor" viewBox="0 0 24 24"><path d="M7.75 2C4.7 2 2 4.7 2 7.75v8.5C2 19.3 4.7 22 7.75 22h8.5c3.05 0 5.75-2.7 5.75-5.75v-8.5C22 4.7 19.3 2 16.25 2h-8.5zM12 7a5 5 0 110 10A5 5 0 0112 7zm0 1.8a3.2 3.2 0 100 6.4 3.2 3.2 0 000-6.4zm4.95-.7a1.05 1.05 0 110 2.1 1.05 1.05 0 010-2.1z"/></svg>
            </div>
            <p class="text-xs font-semibold text-gray-700">@surveycenterindonesia</p>
          </a>
          <a href="https://www.tiktok.com/@surveycenter.indonesia" target="_blank"
             class="flex flex-col items-center gap-2 bg-white border border-gray-200 rounded-xl p-4 hover:border-gray-400 hover:shadow-sm transition group text-center">
            <div class="w-9 h-9 rounded-xl bg-gray-100 flex items-center justify-center group-hover:bg-gray-200 transition">
              <svg class="w-4 h-4 text-gray-800" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.18 8.18 0 004.78 1.52V6.74a4.85 4.85 0 01-1.01-.05z"/></svg>
            </div>
            <p class="text-xs font-semibold text-gray-700">@surveycenter.indonesia</p>
          </a>
        </div>
      </div>

      {{-- CTA WA --}}
      <a href="https://wa.me/6285198887963?text=Halo%2C%20saya%20ingin%20konsultasi%20gratis%20tentang%20layanan%20survey." target="_blank"
         class="flex items-center justify-center gap-3 w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-orange-500/30 transition text-sm">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 32 32"><path d="M26.576 5.363c-2.69-2.69-6.406-4.354-10.511-4.354-8.209 0-14.865 6.655-14.865 14.865 0 2.732.737 5.291 2.022 7.491l-2.147 7.702 7.879-2.067c2.051 1.139 4.498 1.809 7.102 1.809h.006c8.209-.003 14.862-6.659 14.862-14.868 0-4.103-1.662-7.817-4.349-10.507v0z"/></svg>
        Konsultasi Gratis via WhatsApp
      </a>
    </div>

    {{-- RIGHT: Contact Form --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
      <div class="bg-orange-500 px-7 py-5">
        <h2 class="text-lg font-bold text-white">Kirim Pesan</h2>
        <p class="text-white/70 text-xs mt-0.5">Kami akan membalas dalam 1×24 jam kerja</p>
      </div>
      <div class="p-7">
        <form action="{{ route('contact.store') }}" method="POST" class="space-y-4">
          @csrf
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap</label>
            <input type="text" name="name" required
              class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:ring-2 focus:ring-orange-400 focus:border-transparent outline-none transition"
              placeholder="Masukkan nama Anda">
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nomor WhatsApp</label>
            <div class="relative">
              <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-gray-400 font-semibold">+62</span>
              <input type="text" name="phone" required
                class="w-full border border-gray-200 rounded-xl pl-12 pr-4 py-2.5 text-sm text-gray-900 focus:ring-2 focus:ring-orange-400 focus:border-transparent outline-none transition"
                placeholder="8xx-xxxx-xxxx">
            </div>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Pesan</label>
            <textarea name="message" rows="5" required
              class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:ring-2 focus:ring-orange-400 focus:border-transparent outline-none transition resize-none"
              placeholder="Ceritakan kebutuhan survei Anda..."></textarea>
          </div>
          <button type="submit"
            class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-xl shadow-lg shadow-orange-500/20 transition text-sm">
            Kirim Pesan →
          </button>
        </form>
      </div>
    </div>

  </div>

  {{-- Google Maps --}}
  <div class="mt-16 rounded-2xl overflow-hidden shadow-sm border border-gray-200">
    <div class="bg-white px-6 py-4 border-b border-gray-200 flex items-center gap-2">
      <svg class="w-4 h-4 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
      <span class="text-sm font-semibold text-gray-700">Lokasi Kami</span>
    </div>
    <iframe
      src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.370083356635!2d106.832949!3d-6.222123!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3dcb75f4639%3A0xf1afcb3e8d7ef1a1!2sGARDEN%20AVENUE%20RASUNA!5e0!3m2!1sid!2sid!4v1694966400000!5m2!1sid!2sid"
      width="100%" height="380" style="border:0;" allowfullscreen="" loading="lazy"
      referrerpolicy="no-referrer-when-downgrade">
    </iframe>
  </div>

</div>
</div>

@endsection
