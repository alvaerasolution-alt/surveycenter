@extends('layouts.app')

@section('content')
  <div class="bg-white py-8 md:py-10">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8">

        {{-- Main Article --}}
        <article class="md:col-span-3 space-y-5 md:space-y-6 min-w-0">
          {{-- Title --}}
          <h1 class="text-xl sm:text-2xl md:text-3xl font-extrabold text-gray-900 leading-snug break-words">
            {{ $article->title }}
          </h1>

          {{-- Meta Info --}}
          <p class="text-xs sm:text-sm text-gray-500 break-words">
            oleh <span class="font-medium">{{ $article->author ?? 'Admin' }}</span>
            <span class="mx-1">•</span>
            {{ $article->created_at->format('M d, Y') }}
            <span class="mx-1">•</span>
            <a href="{{ route('blog.category', $article->category) }}" class="text-orange-600 hover:underline">
              {{ $article->category }}
            </a>
          </p>

          {{-- Featured Image --}}
          @if ($article->image)
            <div class="w-full rounded-2xl overflow-hidden shadow border border-gray-100">
              <div class="w-full h-56 sm:h-72 md:h-96 lg:h-[500px] bg-gray-100">
                <img src="{{ url('storage/' . $article->image) }}" alt="{{ $article->title }}" class="w-full h-full object-cover" loading="lazy">
              </div>
            </div>
          @endif

          {{-- CSS: Force Justify (rata kanan-kiri) --}}
          <style>
            .article-content {
              text-align: justify;
              text-justify: inter-word;
              hyphens: auto;
              -webkit-hyphens: auto;
              word-break: normal;
              overflow-wrap: anywhere;
            }

            /* Pastikan paragraf/list ikut justify */
            .article-content p,
            .article-content li,
            .article-content blockquote {
              text-align: justify;
              text-justify: inter-word;
              hyphens: auto;
              -webkit-hyphens: auto;
            }

            /* Media dari editor biar aman di mobile */
            .article-content img,
            .article-content iframe,
            .article-content video {
              max-width: 100%;
              height: auto;
            }

            /* Table dari editor biar tidak merusak layout */
            .article-content table {
              display: block;
              width: 100%;
              overflow-x: auto;
              -webkit-overflow-scrolling: touch;
            }
			  
	        .article-content h1 { font-size: 2rem; font-weight: 700; }
            .article-content h2 { font-size: 1.75rem; font-weight: 700; }
            .article-content h3 { font-size: 1.5rem; font-weight: 700; }
            .article-content h4 { font-size: 1.25rem; font-weight: 700; }
            .article-content h5 { font-size: 1.1rem; font-weight: 700; }
            .article-content h6 { font-size: 1rem; font-weight: 700; }

            .article-content blockquote {
              border-left: 4px solid #d1d5db;
              padding-left: 12px;
              color: #6b7280;
              font-style: italic;
             }
          </style>

          {{-- Content --}}
          <div
            class="article-content prose prose-sm sm:prose-base md:prose-lg prose-gray max-w-none leading-relaxed
                      [&_a]:text-blue-600 [&_a:hover]:underline [&_a]:font-medium
                      [&_img]:rounded-xl [&_img]:mx-auto [&_img]:h-auto [&_img]:max-w-full
                      [&_iframe]:w-full [&_iframe]:max-w-full">
            {!! $article->content !!}
          </div>
        </article>

        {{-- Sidebar --}}
        <aside class="md:col-span-1 space-y-6 min-w-0">
          {{-- Search --}}
          <div class="rounded-2xl border border-gray-200 p-4 shadow-sm">
            <form action="{{ route('blog.index') }}" method="GET" class="flex flex-col gap-2 sm:flex-row sm:gap-0">
              <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari..."
                class="w-full px-3 py-2 border border-gray-300 rounded-xl sm:rounded-l-xl sm:rounded-r-none
                       focus:outline-none focus:ring-2 focus:ring-orange-500">
              <button type="submit" class="w-full sm:w-auto bg-orange-600 text-white px-4 py-2 rounded-xl sm:rounded-r-xl sm:rounded-l-none
                       font-semibold hover:bg-orange-700 transition">
                Cari
              </button>
            </form>
          </div>

          {{-- Recent Posts --}}
          <div class="rounded-2xl border border-gray-200 p-4 shadow-sm">
            <h3 class="font-extrabold text-base mb-3">Pos-pos Terbaru</h3>
            <ul class="space-y-2">
              @foreach ($recent as $post)
                <li class="min-w-0">
                  <a href="{{ route('blog.show', $post->slug) }}" class="block text-orange-600 hover:underline text-sm break-words">
                    {{ $post->title }}
                  </a>
                </li>
              @endforeach
            </ul>
          </div>

          {{-- Categories --}}
          <div class="rounded-2xl border border-gray-200 p-4 shadow-sm">
            <h3 class="font-extrabold text-base mb-3">Kategori</h3>
            <ul class="space-y-2">
              @foreach ($categories as $cat)
                <li class="min-w-0">
                  <a href="{{ route('blog.category', $cat->category) }}" class="block text-orange-600 hover:underline text-sm break-words">
                    {{ $cat->category }}
                  </a>
                </li>
              @endforeach
            </ul>
          </div>

          {{-- About Us --}}
          <div class="rounded-2xl border border-gray-200 p-4 shadow-sm">
            <h3 class="font-extrabold text-base mb-3">ABOUT US</h3>
            <p class="text-gray-600 text-sm leading-relaxed">
              Survey Center adalah sebuah platform survei online yang membantu
              bisnis dalam mengumpulkan data akurat, cepat, dan efisien.
              Kami menyediakan riset pasar, mystery shopper, dan layanan konsultasi.
            </p>
          </div>
        </aside>

      </div>
    </div>
  </div>
@endsection
