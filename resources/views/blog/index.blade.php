@extends('layouts.app')
@section('seo_slug', 'blog')

@section('content')
  <div class="bg-white py-8 md:py-10">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <div class="md:col-span-3">
            {{-- Main Content --}}
            <div class="md:col-span-3 min-w-0 grid grid-cols-1 md:grid-cols-2 gap-6">
              @foreach ($articles as $article)
                <article class="bg-white dark:bg-background-dark rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 group flex flex-col">
                  {{-- Thumbnail --}}
                  @if ($article->image)
                    <a href="{{ route('blog.show', $article->slug) }}" class="block h-48 overflow-hidden relative">
                      <img src="{{ url('storage/' . $article->image) }}" alt="{{ $article->title }}" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500" loading="lazy" style="max-width: 600px !important;">
                    </a>
                  @endif
    
                  <div class="p-5 flex flex-col flex-grow">
                      {{-- Title --}}
                      <h2 class="font-bold text-2xl mb-2 text-orange-600 line-clamp-2">
                        <a href="{{ route('blog.show', $article->slug) }}" class="hover:underline">
                          {{ $article->title }}
                        </a>
                      </h2>
        
                      {{-- Meta Info --}}
                      <p class="text-xs sm:text-sm text-gray-500 mt-1 mb-3 break-words">
                        oleh <span class="font-medium">{{ $article->author ?? 'Admin' }}</span>
                        <span class="mx-1">•</span>
                        {{ $article->created_at->format('M d, Y') }}
                        <span class="mx-1">•</span>
                        <a href="{{ route('blog.category', $article->category) }}" class="text-orange-600 hover:underline">
                          {{ $article->category }}
                        </a>
                      </p>
        
                      {{-- Excerpt --}}
                      <p class="text-sm sm:text-base text-gray-700 leading-relaxed mb-3 line-clamp-2">
                        {{ Str::limit(strip_tags($article->excerpt ?? $article->content), 180) }}
                      </p>
        
                      <a href="{{ route('blog.show', $article->slug) }}" class="inline-flex items-center text-orange-600 font-semibold hover:underline">
                        Baca selengkapnya <span class="ml-1">→</span>
                      </a>
                  </div>
                </article>
              @endforeach
            </div>
            
            {{-- Pagination --}}
            <div class="mt-6">
              <div class="flex justify-center md:justify-start">
                {{ $articles->links() }}
              </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <aside class="md:col-span-1 space-y-6 min-w-0">
          {{-- Search --}}
          <div class="rounded-2xl border border-gray-200 p-4 shadow-sm">
            <form action="{{ route('blog.index') }}" method="GET" class="flex flex-col gap-2 sm:flex-row sm:gap-0">
              <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari..."
                class="w-full px-3 py-2 border border-gray-300 rounded-xl sm:rounded-l-xl sm:rounded-r-none focus:outline-none focus:ring-2 focus:ring-orange-500">
              <button type="submit" class="w-full sm:w-auto bg-orange-600 text-white px-4 py-2 rounded-xl sm:rounded-r-xl sm:rounded-l-none font-semibold hover:bg-orange-700 transition">
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
        </aside>
      </div>
    </div>
  </div>
@endsection
