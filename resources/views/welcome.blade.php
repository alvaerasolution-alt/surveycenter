@extends('layouts.app')

@section('title', 'Home - Survey Center Indonesia')
@section('seo_slug', 'home')

@section('content')

{{-- ===================================================================
     GLOBAL STYLES
===================================================================  --}}
<style>
  @keyframes floatSlow {
    0%, 100% { transform: translateY(0); }
    50%       { transform: translateY(-18px); }
  }
  .float-mascot { animation: floatSlow 4s ease-in-out infinite; }

  /* Marquee */
  .marquee-wrapper { overflow: hidden; white-space: nowrap; }
  .marquee-track   { display: inline-flex; white-space: nowrap; animation: marqueeLeft 30s linear infinite; }
  .marquee-track.rtl { animation: marqueeRight 30s linear infinite; }
  @keyframes marqueeLeft  { 0%{ transform: translateX(0); } 100%{ transform: translateX(-50%); } }
  @keyframes marqueeRight { 0%{ transform: translateX(-50%); } 100%{ transform: translateX(0); } }
  .marquee-wrapper:hover .marquee-track { animation-play-state: paused; }

  /* Partner logo size */
  .partner-logo { height: 5rem; margin: 0 1.5rem; }
  @media (max-width:1024px){ .partner-logo { height: 3.5rem; } }
  @media (max-width: 768px){ .partner-logo { height: 2.8rem; margin: 0 1rem; } }

  /* Survey card hover */
  .survey-card { transition: transform .25s, box-shadow .25s; }
  .survey-card:hover { transform: translateY(-6px); box-shadow: 0 12px 30px rgba(0,0,0,.12); }

  /* Insight card */
  .insight-card { min-width: 260px; max-width: 280px; }

  /* Work delivered card */
  .work-card { min-width: 280px; max-width: 300px; }
</style>


{{-- ===================================================================
     SECTION 1 — HERO (Rounded Card Style)
===================================================================  --}}
<section class="relative bg-white pt-6 pb-10">

  {{-- Outer container with padding so the card doesn't touch the edges --}}
  <div class="max-w-6xl mx-auto px-4 sm:px-6">

    {{-- Rounded orange hero card --}}
    <div class="relative isolate overflow-hidden rounded-3xl" style="background: linear-gradient(135deg, #FF8C42 0%, #FF6B1A 30%, #FFB380 70%, #FFE8D6 100%);">

      {{-- Background image overlay --}}
      <div class="absolute inset-0 -z-10">
        <img src="{{ asset('storage/assets/bg.png') }}" alt="bg" class="w-full h-full object-cover opacity-15">
      </div>

      <div class="grid md:grid-cols-[55%_45%] items-center gap-6 px-8 md:px-12 py-12 md:py-16">

        {{-- LEFT: Text + Stats --}}
        <div class="text-center md:text-left">
          <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-[3.2rem] font-extrabold leading-tight text-white drop-shadow-sm">
            Data Akurat, Keputusan Bisnis<br>Lebih Tepat
          </h1>
          <p class="mt-4 text-sm sm:text-base md:text-lg text-white/80 max-w-md italic">
            Membantu perusahaan memahami pasar melalui riset profesional dan analisis terukur.
          </p>

          {{-- CTA Buttons --}}
          <div class="mt-7 flex flex-col sm:flex-row gap-3 justify-center md:justify-start">
            <a href="/login"
               class="bg-white text-orange-600 font-bold text-sm sm:text-base px-6 sm:px-7 py-3 rounded-full shadow-md hover:bg-orange-50 hover:shadow-lg transition-all duration-300 inline-flex items-center justify-center gap-2">
              Konsultasi Gratis
            </a>
            <a href="/layanan/survei-kepuasan-pelanggan"
               class="bg-orange-600 text-white text-sm sm:text-base px-6 sm:px-7 py-3 rounded-full shadow-md hover:bg-orange-700 hover:shadow-lg transition-all duration-300 font-semibold inline-flex items-center justify-center">
              Lihat Layanan
            </a>
          </div>

          {{-- Stats Grid 2×2 — white cards with green checkmarks --}}
          <div class="mt-8 grid grid-cols-2 gap-3 max-w-md mx-auto md:mx-0">

            {{-- Stat 1 --}}
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl p-4 flex items-center gap-3 shadow-sm hover:shadow-md transition-shadow duration-300">
              <div class="w-7 h-7 rounded-full bg-green-500 flex items-center justify-center flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
              </div>
              <div>
                <p class="text-xl font-extrabold text-orange-500 leading-none">500+</p>
                <p class="text-xs text-gray-500 mt-0.5">Proyek Riset</p>
              </div>
            </div>

            {{-- Stat 2 --}}
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl p-4 flex items-center gap-3 shadow-sm hover:shadow-md transition-shadow duration-300">
              <div class="w-7 h-7 rounded-full bg-green-500 flex items-center justify-center flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
              </div>
              <div>
                <p class="text-xs font-bold text-gray-900 leading-snug">Jangkauan Nasional</p>
              </div>
            </div>

            {{-- Stat 3 --}}
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl p-4 flex items-center gap-3 shadow-sm hover:shadow-md transition-shadow duration-300">
              <div class="w-7 h-7 rounded-full bg-green-500 flex items-center justify-center flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
              </div>
              <div>
                <p class="text-xl font-extrabold text-orange-500 leading-none">120+</p>
                <p class="text-xs text-gray-500 mt-0.5">Klien Perusahaan</p>
              </div>
            </div>

            {{-- Stat 4 --}}
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl p-4 flex items-center gap-3 shadow-sm hover:shadow-md transition-shadow duration-300">
              <div class="w-7 h-7 rounded-full bg-green-500 flex items-center justify-center flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
              </div>
              <div>
                <p class="text-xs font-bold text-gray-900 leading-snug">Tim Analis<br>Berpengalaman</p>
              </div>
            </div>

          </div>
        </div>

        {{-- RIGHT: Mascot --}}
        <div class="flex justify-center md:justify-end">
          <img src="{{ asset('assets/logosc.png') }}" alt="SurveyCenter Mascot"
               class="float-mascot w-60 sm:w-72 md:w-[380px] lg:w-[420px] drop-shadow-[0_30px_30px_rgba(0,0,0,0.2)]">
        </div>

      </div><!-- /grid -->

    </div><!-- /rounded card -->

  </div><!-- /container -->

  {{-- Scroll indicator — centered below card --}}
  <div class="flex justify-center mt-6">
    <a href="#section-banner" class="w-12 h-12 rounded-full bg-white shadow-lg border border-gray-100 flex items-center justify-center animate-bounce hover:shadow-xl transition-shadow duration-300">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
      </svg>
    </a>
  </div>

</section>

{{-- Banner Slider --}}

<section id="section-banner" class="relative w-full max-w-6xl mx-auto my-8 overflow-hidden rounded-xl shadow-lg">
  <div class="relative overflow-hidden">
    <div id="discountSlider" class="flex transition-transform duration-700 ease-in-out">
      @foreach ($banners as $banner)
        <div class="relative flex items-center justify-between px-8 py-5 flex-shrink-0" style="background: {{ $banner->background }}; width: 100%;">
          {{-- Dark overlay for text contrast --}}
          <div class="absolute inset-0 bg-black/40"></div>
          <div class="relative max-w-[70%] text-white font-extrabold" style="text-shadow: 0 1px 4px rgba(0,0,0,.5);">
            <h2 class="text-xl md:text-2xl font-extrabold mb-1">{{ $banner->title }}</h2>
            <p class="text-sm md:text-base font-normal text-white/90">{{ $banner->subtitle }}</p>
            @if ($banner->button_text && $banner->button_link)
              <a href="{{ $banner->button_link }}" class="inline-block mt-3 px-5 py-2 bg-white text-gray-900 rounded-lg font-bold text-sm hover:bg-gray-100 transition shadow">
                {{ $banner->button_text }}
              </a>
            @endif
          </div>
          @if ($banner->image)
            <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}" class="relative rounded-lg shadow-md w-36 h-24 object-cover hidden sm:block">
          @endif
        </div>
      @endforeach
    </div>
    <div id="bannerDots" class="absolute bottom-2 left-1/2 -translate-x-1/2 flex gap-2 z-10"></div>
  </div>
</section>

<script>
(function(){
  const slider = document.getElementById('discountSlider');
  if(!slider) return;
  const slides = Array.from(slider.children);
  const total = slides.length;
  const dotsEl = document.getElementById('bannerDots');
  slider.style.width = `${total * 100}%`;
  slides.forEach(s => { s.style.width = `${100/total}%`; s.style.flex='0 0 auto'; });
  slides.forEach((_,i)=>{
    const btn = document.createElement('button');
    btn.className='w-2.5 h-2.5 rounded-full bg-white/50 transition';
    btn.addEventListener('click',()=>{show(i);reset();});
    dotsEl.appendChild(btn);
  });
  const dots = Array.from(dotsEl.children);
  let idx=0, timer=null;
  function show(i){ idx=(i+total)%total; slider.style.transform=`translateX(-${idx*100/total}%)`; dots.forEach((d,j)=>{ d.className='w-2.5 h-2.5 rounded-full transition '+(j===idx?'bg-white':'bg-white/40'); }); }
  function next(){ show(idx+1); }
  function reset(){ clearInterval(timer); timer=setInterval(next,4500); }
  slider.parentElement.addEventListener('mouseenter',()=>clearInterval(timer));
  slider.parentElement.addEventListener('mouseleave',reset);
  show(0); reset();
})();
</script>


{{-- ===================================================================
     SECTION 2 — OUR COMPANY (Video)
===================================================================  --}}
<section class="bg-white py-14 px-4">
  <div class="max-w-4xl mx-auto text-center">
    <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-2">Our Company</h2>
    <p class="text-gray-500 text-sm md:text-base mb-8 max-w-xl mx-auto">
      Survey Center adalah perusahaan riset dan survei terpercaya yang membantu bisnis membuat keputusan berbasis data.
    </p>

    {{-- Phone Mockup Frame --}}
    <div class="relative inline-block">
      {{-- Phone frame --}}
      <div class="relative mx-auto bg-gray-900 rounded-[2.5rem] p-3 shadow-2xl w-full max-w-2xl border-4 border-gray-800">
        <div class="absolute top-3.5 left-1/2 -translate-x-1/2 w-16 h-1.5 bg-gray-700 rounded-full z-10"></div>
        <div class="rounded-[2rem] overflow-hidden bg-black flex justify-center">
          <video class="w-full aspect-video hidden md:block" controls preload="none" poster="{{ asset('assets/bg.png') }}">
            <source src="{{ asset('assets/video.mp4') }}" type="video/mp4">
            Browser Anda tidak mendukung tag video.
          </video>
          <video class="w-[80%] aspect-[9/16] block md:hidden" controls preload="none" poster="{{ asset('assets/bg.png') }}">
            <source src="{{ asset('assets/survey1.mp4') }}" type="video/mp4">
            Browser Anda tidak mendukung tag video.
          </video>
        </div>
      </div>
      {{-- Mascot overlapping the frame --}}
      <img src="{{ asset('assets/logosc.png') }}" alt="Mascot"
           class="absolute -bottom-8 -right-4 md:-right-12 w-24 md:w-32 drop-shadow-xl pointer-events-none"
           style="animation: floatSlow 4s ease-in-out infinite;">
    </div>

    <p class="mt-14 md:mt-12 text-gray-600 text-sm md:text-base max-w-2xl mx-auto leading-relaxed">
      Survey Center membantu Anda melakukan survei online dan offline tepat sasaran,
      serta menentukan pasar sesuai kebutuhan Anda untuk pertumbuhan bisnis yang optimal.
    </p>
  </div>
</section>


{{-- ===================================================================
     SECTION 3 — BUAT SURVEI SESUAI KEBUTUHAN
===================================================================  --}}
<section class="bg-gray-50 py-16 px-4">
  <div class="max-w-6xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6 mb-12">
      <div class="md:w-1/2">
        <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 leading-tight">
          Buat Survei Sesuai<br>
          <span class="text-orange-500">dengan Kebutuhan Anda.</span>
        </h2>
      </div>
      <div class="md:w-1/2 md:pt-2">
        <p class="text-gray-500 text-sm md:text-base leading-relaxed">
          Kami menyediakan berbagai jenis survei profesional yang dapat disesuaikan dengan industri
          dan kebutuhan bisnis Anda. Dari kepuasan pelanggan hingga brand awareness,
          semua tersedia dalam satu platform yang mudah digunakan.
        </p>
      </div>
    </div>

    {{-- 3×2 Cards Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

      @php
        $surveyTypes = [
          ['icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
           'title'=>'Survey Pengukuran Indeks Kepuasan',
           'desc'=>'Mengukur kepuasan masyarakat terhadap layanan publik atau bisnis.'],
          ['icon'=>'M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
           'title'=>'Survey Kepuasan Pelanggan',
           'desc'=>'Menilai kepuasan pelanggan terhadap produk dan layanan bisnis Anda.'],
          ['icon'=>'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.963a1 1 0 00.95.693h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.287 3.963c.3.921-.755 1.688-1.538 1.118l-3.37-2.448a1 1 0 00-1.176 0l-3.37 2.448c-.783.57-1.838-.197-1.538-1.118l1.287-3.963a1 1 0 00-.364-1.118L2.05 9.393c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.693l1.286-3.963z',
           'title'=>'Survey Brand Awareness',
           'desc'=>'Mengukur tingkat kesadaran merek dan memperkuat strategi pemasaran.'],
          ['icon'=>'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7',
           'title'=>'Survey Potensi Pasar',
           'desc'=>'Menggali potensi wilayah atau produk agar tepat sasaran sesuai segmen.'],
          ['icon'=>'M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z',
           'title'=>'Survey Segmentasi dan Positioning',
           'desc'=>'Menganalisis perilaku pasar dan posisi produk di benak konsumen.'],
          ['icon'=>'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z',
           'title'=>'Survey Pengembangan Produk / Jasa',
           'desc'=>'Menilai kelayakan ide pengembangan produk/jasa berdasarkan data.'],
        ];
      @endphp

      @foreach ($surveyTypes as $index => $type)
        <div class="survey-card bg-white rounded-2xl p-6 border border-gray-100 shadow-sm cursor-pointer">
          <div class="w-12 h-12 rounded-xl bg-orange-50 flex items-center justify-center mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
              <path stroke-linecap="round" stroke-linejoin="round" d="{{ $type['icon'] }}"/>
            </svg>
          </div>
          <span class="inline-block text-xs font-bold text-orange-500 bg-orange-50 px-2 py-0.5 rounded mb-2">Survey #{{ $index + 1 }}</span>
          <h3 class="font-extrabold text-gray-900 text-sm md:text-base leading-snug mb-2">{{ $type['title'] }}</h3>
          <p class="text-xs md:text-sm text-gray-500 leading-relaxed">{{ $type['desc'] }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>


{{-- ===================================================================
     SECTION 4 — HAS BEEN TRUSTED (Logo Marquee)
===================================================================  --}}
<section class="bg-white py-14 px-4">
  <div class="max-w-6xl mx-auto">
    <h2 class="text-3xl md:text-4xl font-extrabold text-center text-gray-900 mb-10">
      Has Been <span class="text-orange-500">Trusted</span>
    </h2>
  </div>

  {{-- Row 1: Left --}}
  <div class="marquee-wrapper mb-4">
    <div class="marquee-track">
      @foreach ($partnerLogos->concat($partnerLogos) as $logo)
        <img src="{{ asset('storage/' . $logo->logo_path) }}" alt="{{ $logo->name }}"
             class="partner-logo grayscale hover:grayscale-0 transition duration-300 inline-block object-contain">
      @endforeach
    </div>
  </div>

  {{-- Row 2: Right --}}
  <div class="marquee-wrapper">
    <div class="marquee-track rtl">
      @foreach ($partnerLogos->reverse()->concat($partnerLogos->reverse()) as $logo)
        <img src="{{ asset('storage/' . $logo->logo_path) }}" alt="{{ $logo->name }}"
             class="partner-logo grayscale hover:grayscale-0 transition duration-300 inline-block object-contain">
      @endforeach
    </div>
  </div>
</section>


{{-- ===================================================================
     SECTION 5 — INSIGHT (Blog Articles Horizontal Carousel)
===================================================================  --}}
<section class="bg-gray-50 py-16 px-4">
  <div class="max-w-6xl mx-auto">
    <div class="flex items-center justify-between mb-8">
      <div>
        <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900">Insight</h2>
        <p class="text-gray-500 text-sm mt-1">Artikel dan informasi terkini seputar riset & survei</p>
      </div>
      <div class="flex gap-2">
        <button id="insightPrev"
          class="w-10 h-10 rounded-full border border-gray-300 flex items-center justify-center hover:bg-orange-500 hover:border-orange-500 hover:text-white transition text-gray-500">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <button id="insightNext"
          class="w-10 h-10 rounded-full bg-orange-500 border border-orange-500 text-white flex items-center justify-center hover:bg-orange-600 transition">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </button>
      </div>
    </div>

    {{-- Cards Horizontal --}}
    <div class="overflow-hidden">
      <div id="insightTrack" class="flex gap-5 transition-transform duration-500">
        @foreach ($articles as $article)
          <div class="insight-card flex-shrink-0 bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="relative">
              <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}"
                   class="w-full h-44 object-cover">
              <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
              <span class="absolute bottom-3 left-3 text-xs font-bold text-white bg-orange-500 px-2 py-0.5 rounded">
                {{ $article->category ?? 'Insight' }}
              </span>
            </div>
            <div class="p-4">
              <p class="text-xs text-gray-400 mb-1">{{ $article->created_at->format('d M Y') }}</p>
              <h3 class="font-bold text-gray-900 text-sm leading-snug line-clamp-2 mb-2">
                {{ Str::limit($article->title, 65) }}
              </h3>
              <p class="text-xs text-gray-500 leading-relaxed line-clamp-2">
                {{ Str::limit(strip_tags($article->excerpt ?? $article->content), 100) }}
              </p>
              @if ($article->slug)
                <a href="{{ route('blog.show', $article->slug) }}"
                   class="mt-3 inline-flex items-center gap-1 text-xs font-semibold text-orange-500 hover:text-orange-600 transition">
                  Baca selengkapnya
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
              @endif
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</section>

<script>
(function(){
  const track = document.getElementById('insightTrack');
  if(!track) return;
  const prev = document.getElementById('insightPrev');
  const next = document.getElementById('insightNext');
  let idx = 0;
  function move(){
    const cards = track.querySelectorAll('.insight-card');
    if(!cards.length) return;
    const cardW = cards[0].offsetWidth + 20; // 20 = gap
    const visible = Math.floor(track.parentElement.offsetWidth / cardW);
    const max = Math.max(0, cards.length - visible);
    idx = Math.min(Math.max(idx, 0), max);
    track.style.transform = `translateX(-${idx * cardW}px)`;
  }
  next.addEventListener('click', ()=>{ idx++; move(); });
  prev.addEventListener('click', ()=>{ idx--; move(); });
  window.addEventListener('resize', ()=>{ idx=0; move(); });
})();
</script>


{{-- ===================================================================
     SECTION 7 — TESTIMONI (Gambar dari Admin)
===================================================================  --}}
@if($testimoniImages->count() > 0)
<section class="bg-gray-50 py-16 px-4">
  <div class="max-w-6xl mx-auto">

    {{-- Header --}}
    <div class="text-center mb-12">
      <span class="inline-block bg-green-100 text-green-600 text-xs font-bold uppercase tracking-widest px-4 py-1.5 rounded-full mb-4">
        💬 Testimoni Pelanggan
      </span>
      <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-3">
        Apa Kata <span class="text-orange-500">Mereka?</span>
      </h2>
      <p class="text-gray-500 text-sm md:text-base max-w-xl mx-auto">
        Percakapan nyata dari klien dan responden setia kami — tidak direkayasa, langsung dari WhatsApp.
      </p>
    </div>

    {{-- Carousel --}}
    <div class="relative overflow-hidden">
      <div id="waTrack" class="flex gap-5 transition-transform duration-500 ease-in-out">
        @foreach ($testimoniImages as $img)
          <div class="wa-card flex-shrink-0 w-[200px] sm:w-[220px] md:w-[240px] cursor-pointer"
               onclick="openLightbox('{{ asset('storage/'.$img->image_path) }}', '{{ $img->caption ?? '' }}')">
            <div class="rounded-2xl overflow-hidden shadow-lg border border-gray-200 hover:border-orange-300 hover:shadow-xl transition-all duration-300 group">
              {{-- Image Portrait --}}
              <div class="aspect-[9/16] bg-gray-100 overflow-hidden">
                <img src="{{ asset('storage/'.$img->image_path) }}"
                     alt="{{ $img->caption ?? 'Testimoni' }}"
                     loading="lazy"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
              </div>
              {{-- Caption (jika ada) --}}
              @if($img->caption)
                <div class="bg-white px-3 py-2 border-t border-gray-100">
                  <p class="text-xs text-gray-500 truncate text-center">{{ $img->caption }}</p>
                </div>
              @endif
            </div>
          </div>
        @endforeach
      </div>

      {{-- Nav Buttons --}}
      <button id="waPrev"
        class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-0 w-10 h-10 rounded-full bg-orange-500 hover:bg-orange-600 text-white flex items-center justify-center shadow-lg transition z-10">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
      </button>
      <button id="waNext"
        class="absolute right-0 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-orange-500 hover:bg-orange-600 text-white flex items-center justify-center shadow-lg transition z-10">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
      </button>
    </div>

    {{-- Dots --}}
    <div id="waDots" class="flex justify-center gap-2 mt-6"></div>

  </div>
</section>

{{-- Lightbox Modal --}}
<div id="lightbox" class="fixed inset-0 bg-black/80 z-50 hidden items-center justify-center p-4" onclick="closeLightbox()">
  <div class="relative max-h-[90vh] flex flex-col items-center" onclick="event.stopPropagation()">
    <button onclick="closeLightbox()" class="absolute -top-10 right-0 text-white hover:text-orange-400 transition">
      <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    <img id="lightboxImg" src="" alt="Testimoni" class="max-h-[80vh] rounded-xl shadow-2xl object-contain">
    <p id="lightboxCaption" class="text-white/80 text-sm mt-3 text-center"></p>
  </div>
</div>

<script>
// Lightbox
function openLightbox(src, caption) {
  document.getElementById('lightboxImg').src = src;
  document.getElementById('lightboxCaption').textContent = caption;
  const lb = document.getElementById('lightbox');
  lb.classList.remove('hidden');
  lb.classList.add('flex');
}
function closeLightbox() {
  const lb = document.getElementById('lightbox');
  lb.classList.add('hidden');
  lb.classList.remove('flex');
}
document.addEventListener('keydown', e => { if(e.key === 'Escape') closeLightbox(); });

// Carousel
(function(){
  const track = document.getElementById('waTrack');
  if(!track) return;
  const cards = track.querySelectorAll('.wa-card');
  const dotsEl = document.getElementById('waDots');
  const btnPrev = document.getElementById('waPrev');
  const btnNext = document.getElementById('waNext');
  const total = cards.length;
  let idx = 0, timer;
  const GAP = 20;

  for(let i=0;i<total;i++){
    const d = document.createElement('button');
    d.className='w-2 h-2 rounded-full transition-colors duration-300 '+(i===0?'bg-orange-500':'bg-gray-300');
    d.addEventListener('click',()=>{go(i);reset();});
    dotsEl.appendChild(d);
  }

  function updateDots(){
    dotsEl.querySelectorAll('button').forEach((d,i)=>{
      d.className='w-2 h-2 rounded-full transition-colors duration-300 '+(i===idx?'bg-orange-500':'bg-gray-300');
    });
  }

  function go(i){
    const cw = cards[0].offsetWidth + GAP;
    const vis = Math.max(1, Math.floor((track.parentElement.offsetWidth + GAP) / cw));
    const max = Math.max(0, total - vis);
    idx = Math.max(0, Math.min(i, max));
    track.style.transform = `translateX(-${idx * cw}px)`;
    updateDots();
  }

  function reset(){
    clearInterval(timer);
    timer = setInterval(()=>{
      const cw = cards[0].offsetWidth + GAP;
      const vis = Math.max(1, Math.floor((track.parentElement.offsetWidth + GAP) / cw));
      const max = Math.max(0, total - vis);
      go(idx >= max ? 0 : idx + 1);
    }, 4500);
  }

  btnNext.addEventListener('click',()=>{go(idx+1);reset();});
  btnPrev.addEventListener('click',()=>{go(idx-1);reset();});
  window.addEventListener('resize',()=>{go(idx);});
  go(0); reset();
})();
</script>
@endif


{{-- ===================================================================
     SECTION 8 — CTA BOTTOM (optional, matches design's last orange element)
===================================================================  --}}

<section class="relative overflow-hidden bg-gradient-to-br from-orange-600 to-orange-400 py-16 px-4 text-white text-center">
  <div class="relative z-10 max-w-2xl mx-auto">
    <h2 class="text-3xl md:text-4xl font-extrabold mb-3">Data Akurat, Keputusan Tepat</h2>
    <p class="text-white/90 text-sm md:text-base mb-8 leading-relaxed">
      SurveyCenter Indonesia membantu bisnis, organisasi, dan lembaga membuat keputusan
      berbasis data melalui riset dan survei terpercaya.
    </p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
      <a href="/login"
         class="bg-yellow-300 hover:bg-yellow-400 text-black font-extrabold px-8 py-3 rounded-2xl shadow-lg hover:scale-105 transition transform duration-300">
        Mulai Survei
      </a>
      <a href="#" onclick="document.getElementById('toggleFormBtn')?.click();"
         class="border-2 border-white hover:bg-white hover:text-orange-700 text-white font-extrabold px-8 py-3 rounded-2xl hover:scale-105 transition transform duration-300">
        Hubungi Kami
      </a>
    </div>
  </div>
  {{-- Decorative circles --}}
  <div class="absolute -top-20 -left-20 w-64 h-64 bg-white/10 rounded-full pointer-events-none"></div>
  <div class="absolute -bottom-16 -right-16 w-56 h-56 bg-white/10 rounded-full pointer-events-none"></div>
</section>

@endsection
