<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="robots" content="index,follow,max-snippet:-1,max-image-preview:large,max-video-preview:-1">
    <title>{{ $seoTitle ?? 'Jasa Survey Pasar - Jasa Sebar Kuesioner - Survey Brand Awareness | SurveyCenter' }}</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('assets/logosc.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/logosc.png') }}">
    <link rel="shortcut icon" href="{{ asset('assets/logosc.png') }}" type="image/png">

    {{-- SEO Meta --}}
    <meta name="description" content="{{ $seoDesc ?? 'Jasa Sebar Kuesioner - Jasa Survey Pasar - Market Riset. Temukan solusi profesional untuk pengumpulan data dan analisis pasar dengan layanan jasa yang terpercaya.' }}">
    @if(!empty($seoKeywords))
    <meta name="keywords" content="{{ $seoKeywords }}">
    @endif

    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:locale" content="id_ID">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="SurveyCenter">
    <meta property="og:title" content="{{ $seoTitle ?? 'Jasa Survey Pasar - Jasa Sebar Kuesioner - Survey Brand Awareness | SurveyCenter' }}">
    <meta property="og:description" content="{{ $seoDesc ?? 'Jasa Sebar Kuesioner - Jasa Survey Pasar - Market Riset. Temukan solusi profesional untuk pengumpulan data dan analisis pasar dengan layanan jasa yang terpercaya.' }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('assets/logosc.png') }}">
    <meta property="og:image:alt" content="SurveyCenter">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seoTitle ?? 'Jasa Survey Pasar - Jasa Sebar Kuesioner - Survey Brand Awareness | SurveyCenter' }}">
    <meta name="twitter:description" content="{{ $seoDesc ?? 'Jasa Sebar Kuesioner - Jasa Survey Pasar - Market Riset. Temukan solusi profesional untuk pengumpulan data dan analisis pasar dengan layanan jasa yang terpercaya.' }}">
    <meta name="twitter:image" content="{{ asset('assets/logosc.png') }}">

    @php
        $schemaTitle = $seoTitle ?? 'Jasa Survey Pasar - Jasa Sebar Kuesioner - Survey Brand Awareness | SurveyCenter';
        $schemaDesc = $seoDesc ?? 'Jasa Sebar Kuesioner - Jasa Survey Pasar - Market Riset. Temukan solusi profesional untuk pengumpulan data dan analisis pasar dengan layanan jasa yang terpercaya.';
        $schemaUrl = url()->current();
        $schemaLogo = asset('assets/logosc.png');
        $schemaSearchTarget = route('blog.index') . '?q={search_term_string}';
    @endphp

    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'SurveyCenter',
            'url' => config('app.url') ?: url('/'),
            'logo' => $schemaLogo,
            'sameAs' => [],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => $schemaTitle,
            'url' => config('app.url') ?: url('/'),
            'description' => $schemaDesc,
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => $schemaSearchTarget,
                'query-input' => 'required name=search_term_string',
            ],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>

    @stack('jsonld')

    <link rel="alternate" type="application/rss+xml"
        title="Jasa Survey Pasar - Jasa Sebar Kuesioner - Survey Brand Awarness » Umpan Komentar"
        href="https://surveycenter.co.id/comments/feed/">

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- alpine js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    {{-- Stacked Styles --}}
    @stack('styles')

    {{-- Custom Inline CSS --}}
    <style>
        /* Banner Animations */
        /* Semua slide */
        .banner-slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            z-index: 0;
            transition: opacity 0.5s ease-in-out;
        }

        /* Slide aktif */
        .banner-slide.active {
            position: relative;
            opacity: 1;
            z-index: 10;
        }


        .indicators span {
            width: 10px;
            height: 10px;
            background: white;
            border-radius: 50%;
            display: inline-block;
            cursor: pointer;
            opacity: 0.5;
            transition: opacity 0.3s;
        }

        .indicators span.active {
            opacity: 1;
            background: #002244;
            /* Warna aktif */
        }

        #success-carousel {
            position: relative;
            height: 380px;
            overflow: hidden;
        }

        #success-carousel .slide {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 700px;
            height: 350px;
            transform: translate(-50%, -50%) scale(0.8);
            transition: transform 0.6s ease, opacity 0.6s ease, z-index 0.3s;
            opacity: 0;
            z-index: 0;
        }

        /* Tengah */
        #success-carousel .slide.center {
            transform: translate(-50%, -50%) scale(1);
            opacity: 1;
            z-index: 30;
        }

        /* Kiri */
        #success-carousel .slide.left {
            transform: translate(-110%, -50%) scale(0.9);
            opacity: 0.8;
            z-index: 20;
        }

        /* Kanan */
        #success-carousel .slide.right {
            transform: translate(10%, -50%) scale(0.9);
            opacity: 0.8;
            z-index: 20;
        }

        /* Hidden */
        #success-carousel .slide.hidden {
            transform: translate(-50%, -50%) scale(0.8);
            opacity: 0;
            z-index: 0;
        }

        /* Animasi masuk dari kanan */
        #success-carousel .slide.enter-right {
            transform: translate(10%, -50%) scale(0.9);
            opacity: 0;
        }

        #success-carousel .slide.enter-right.center {
            transform: translate(-50%, -50%) scale(1);
            opacity: 1;
        }

        /* Animasi masuk dari kiri */
        #success-carousel .slide.enter-left {
            transform: translate(-110%, -50%) scale(0.9);
            opacity: 0;
        }

        #success-carousel .slide.enter-left.center {
            transform: translate(-50%, -50%) scale(1);
            opacity: 1;
        }

        /* Animasi keluar ke kiri */
        #success-carousel .slide.exit-left {
            transform: translate(-110%, -50%) scale(0.9);
            opacity: 0;
            z-index: 20;
        }

        /* Animasi keluar ke kanan */
        #success-carousel .slide.exit-right {
            transform: translate(10%, -50%) scale(0.9);
            opacity: 0;
            z-index: 20;
        }
    </style>
</head>

<body
    class="bg-white">
    {{-- Navbar --}}
    @include('partials.navbar')

    {{-- Main Content --}}
    <main class="flex-grow">
    @yield('content')
    </main>

    {{-- Footer --}}
    @include('partials.footer')

    {{-- Floating WhatsApp Button (Available on all pages) --}}
    @include('components.floating-whatsapp-button')

    {{-- Inline JS --}}
    <script>
        // Navbar Dropdown Toggle
        document.querySelectorAll('nav ul li').forEach(item => {
            const arrow = item.querySelector('.arrow');
            item.addEventListener('click', e => {
                e.stopPropagation();
                document.querySelectorAll('nav ul li').forEach(li => {
                    if (li !== item) {
                        li.classList.remove('show');
                        li.querySelector('.arrow')?.classList.remove('rotate-180');
                    }
                });
                item.classList.toggle('show');
                arrow?.classList.toggle('rotate-180');
            });
        });

        document.addEventListener('click', () => {
            document.querySelectorAll('nav ul li').forEach(li => {
                li.classList.remove('show');
                li.querySelector('.arrow')?.classList.remove('rotate-180');
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            /** ---------------------------
             * Banner Carousel
             * --------------------------- */
            const slides = document.querySelectorAll(".banner-slide");
            const indicatorsContainer = document.querySelector(".indicators");
            let currentIndex = 0;

            if (slides.length) {
                // Buat indikator
                slides.forEach((_, i) => {
                    const dot = document.createElement("span");
                    dot.className = "w-3 h-3 bg-white rounded-full opacity-50 cursor-pointer";
                    if (i === 0) dot.classList.add("opacity-100", "bg-[#002244]");
                    dot.addEventListener("click", () => showSlide(i));
                    indicatorsContainer.appendChild(dot);
                });

                const indicators = indicatorsContainer.querySelectorAll("span");

                function showSlide(index) {
                    console.log("Switching to slide:", index);

                    slides[currentIndex].classList.remove("active");
                    indicators[currentIndex].classList.remove("opacity-100", "bg-[#002244]");
                    indicators[currentIndex].classList.add("opacity-50", "bg-white");

                    slides[index].classList.add("active");
                    indicators[index].classList.add("opacity-100", "bg-[#002244]");
                    indicators[index].classList.remove("opacity-50", "bg-white");

                    currentIndex = index;
                }

                setInterval(() => {
                    const nextIndex = (currentIndex + 1) % slides.length;
                    showSlide(nextIndex);
                }, 5000);
            }

            /** ---------------------------
             * Tabs Interaction
             * --------------------------- */
            const tabButtons = document.querySelectorAll('.tab-btn');
            const tabPanels = document.querySelectorAll('.tab-panel');

            if (tabButtons.length) {
                tabButtons.forEach((btn, index) => {
                    btn.addEventListener('click', () => {
                        tabButtons.forEach(b => {
                            b.classList.remove('bg-[#FB8500]', 'text-white', 'active');
                            b.classList.add('bg-gray-800', 'text-black');
                        });

                        tabPanels.forEach(panel => {
                            panel.classList.add('hidden');
                            panel.classList.remove('opacity-100');
                        });

                        btn.classList.remove('bg-gray-800', 'text-black');
                        btn.classList.add('bg-[#FB8500]', 'text-white', 'active');

                        const activePanel = tabPanels[index];
                        activePanel.classList.remove('hidden');
                        setTimeout(() => activePanel.classList.add('opacity-100'), 50);
                    });
                });
            }

            /** ---------------------------
             * Success Carousel
             * --------------------------- */
            const carousel = document.getElementById('success-carousel');

            if (carousel) {
                let slides = Array.from(carousel.querySelectorAll('.slide'));
                const nextBtn = document.getElementById('next-slide');
                const prevBtn = document.getElementById('prev-slide');
                let current = 0;
                let isAnimating = false;

                function resetSlides() {
                    slides.forEach(slide => {
                        slide.classList.remove('center', 'left', 'right', 'hidden', 'enter-left',
                            'enter-right', 'exit-left', 'exit-right');
                    });
                }

                function updateSlides(direction) {
                    slides = Array.from(carousel.querySelectorAll('.slide'));
                    const total = slides.length;

                    resetSlides();

                    slides.forEach((slide, index) => {
                        if (index === current) {
                            slide.classList.add('center');
                            if (direction === 'next') slide.classList.add('enter-right');
                            if (direction === 'prev') slide.classList.add('enter-left');
                        } else if (index === (current - 1 + total) % total) {
                            slide.classList.add('left');
                            if (direction === 'next') slide.classList.add('exit-left');
                            if (direction === 'prev') slide.classList.add('enter-left');
                        } else if (index === (current + 1) % total) {
                            slide.classList.add('right');
                            if (direction === 'next') slide.classList.add('enter-right');
                            if (direction === 'prev') slide.classList.add('exit-right');
                        } else {
                            slide.classList.add('hidden');
                        }
                    });

                    // Biarkan animasi berjalan 600ms sesuai CSS, lalu reset kelas enter/exit
                    isAnimating = true;
                    setTimeout(() => {
                        slides.forEach(slide => slide.classList.remove('enter-left', 'enter-right',
                            'exit-left', 'exit-right'));
                        isAnimating = false;
                    }, 600);
                }

                function changeSlide(direction) {
                    if (isAnimating) return;
                    const total = slides.length;

                    if (direction === 'next') {
                        current = (current + 1) % total;
                    } else {
                        current = (current - 1 + total) % total;
                    }

                    updateSlides(direction);
                }

                nextBtn?.addEventListener('click', () => changeSlide('next'));
                prevBtn?.addEventListener('click', () => changeSlide('prev'));

                setInterval(() => changeSlide('next'), 5000);

                updateSlides();
            }
        });
    </script>
</body>

</html>
