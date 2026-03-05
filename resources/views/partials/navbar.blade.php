<nav x-data="{ open: false }" class="bg-white shadow-sm sticky top-0 z-50">

  <div class="max-w-6xl mx-auto flex items-center justify-between px-4 py-3 md:px-6">

    {{-- Logo --}}
    <a href="{{ route('landing') }}" class="flex items-start gap-3 flex-shrink-0">
      <img src="{{ asset('assets/logosc.png') }}" alt="SurveyCenter Logo" class="w-12 h-12 object-contain">
      <div class="leading-tight">
        <p class="text-base font-extrabold text-orange-500 leading-tight">Survey Center<br>Indonesia</p>
        <p class="text-[10px] text-gray-400 mt-0.5">PT. Market Research & Branding</p>
      </div>
    </a>

    {{-- Desktop Nav --}}
    <ul class="hidden md:flex items-center gap-8 text-sm font-semibold text-gray-700">
      <li>
        <a href="{{ route('landing') }}"
           class="{{ request()->routeIs('landing') ? 'text-orange-500 border-b-2 border-orange-500 pb-0.5' : 'hover:text-orange-500 transition' }}">Home</a>
      </li>
      <li>
        <a href="{{ route('about') }}"
           class="{{ request()->routeIs('about') ? 'text-orange-500 border-b-2 border-orange-500 pb-0.5' : 'hover:text-orange-500 transition' }}">About</a>
      </li>

      {{-- Dropdown Layanan --}}
      <li x-data="{ dropdownOpen: false }" class="relative">
        <button @click="dropdownOpen = !dropdownOpen"
                class="cursor-pointer flex items-center gap-1 transition {{ request()->routeIs('layanan.*') ? 'text-orange-500' : 'hover:text-orange-500' }}">
          Layanan
          <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 transition-transform" :class="{ 'rotate-180': dropdownOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
          </svg>
        </button>
        <div x-show="dropdownOpen" @click.away="dropdownOpen = false" x-transition
             class="absolute left-0 top-full mt-2 w-[580px] bg-white text-gray-800 p-5 rounded-xl shadow-2xl grid grid-cols-2 gap-6 z-50 border border-gray-100">
          <div>
            <h4 class="text-xs font-bold text-orange-500 uppercase tracking-wide mb-3">Jenis Survei</h4>
            @foreach ($jenis as $item)
              <a href="{{ route('layanan.show', $item->slug) }}"
                 class="block py-1.5 text-sm text-gray-600 hover:text-orange-500 hover:pl-1 transition-all">{{ $item->title }}</a>
            @endforeach
          </div>
          <div>
            <h4 class="text-xs font-bold text-orange-500 uppercase tracking-wide mb-3">Layanan Tambahan</h4>
            @foreach ($tambahan as $item)
              <a href="{{ route('layanan.show', $item->slug) }}"
                 class="block py-1.5 text-sm text-gray-600 hover:text-orange-500 hover:pl-1 transition-all">{{ $item->title }}</a>
            @endforeach
          </div>
        </div>
      </li>

      <li>
        <a href="{{ route('pricing') }}"
           class="{{ request()->routeIs('pricing') ? 'text-orange-500 border-b-2 border-orange-500 pb-0.5' : 'hover:text-orange-500 transition' }}">Harga</a>
      </li>
      <li>
        <a href="{{ route('blog.index') }}"
           class="{{ request()->routeIs('blog.*') ? 'text-orange-500 border-b-2 border-orange-500 pb-0.5' : 'hover:text-orange-500 transition' }}">Blog</a>
      </li>
      <li>
        <a href="{{ route('contact') }}"
           class="{{ request()->routeIs('contact') ? 'text-orange-500 border-b-2 border-orange-500 pb-0.5' : 'hover:text-orange-500 transition' }}">Contact Us</a>
      </li>
    </ul>


    {{-- Desktop Auth --}}
    <div class="hidden md:flex items-center gap-3">
      @auth
        <div x-data="{ open: false }" class="relative">
          <button @click="open = !open"
                  class="flex items-center gap-2 px-4 py-2 rounded-lg bg-orange-50 hover:bg-orange-100 transition focus:outline-none">
            <div class="w-7 h-7 rounded-full bg-orange-500 flex items-center justify-center flex-shrink-0">
              <span class="text-white text-xs font-bold leading-none">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
            </div>
            <span class="font-medium text-sm text-gray-700">{{ auth()->user()->name }}</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
          </button>
          <div x-show="open" @click.outside="open = false"
               class="absolute right-0 mt-2 w-48 bg-white border border-gray-100 rounded-xl shadow-xl overflow-hidden z-50">
            <a href="{{ route('user.dashboard') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-500 transition">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg>
              Dashboard
            </a>
            <a href="{{ route('user.history') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-500 transition">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              History
            </a>
            <hr class="border-gray-100">
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Logout
              </button>
            </form>
          </div>
        </div>
      @else
        <a href="{{ route('login') }}"
           class="border-2 border-orange-500 text-orange-500 px-5 py-2 rounded-full text-sm font-semibold hover:bg-orange-500 hover:text-white transition">
          Login
        </a>
        <a href="{{ route('register') }}"
           class="bg-orange-500 text-white px-5 py-2 rounded-full text-sm font-bold hover:bg-orange-600 transition shadow-sm">
          Daftar
        </a>
      @endauth
    </div>

    {{-- Hamburger (Mobile) --}}
    <button @click="open = !open" class="md:hidden text-gray-700 focus:outline-none">
      <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
      <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
  </div>

  {{-- Mobile Menu --}}
  <div x-show="open"
       x-transition:enter="transition ease-out duration-200"
       x-transition:enter-start="opacity-0 -translate-y-2"
       x-transition:enter-end="opacity-100 translate-y-0"
       x-transition:leave="transition ease-in duration-150"
       x-transition:leave-start="opacity-100 translate-y-0"
       x-transition:leave-end="opacity-0 -translate-y-2"
       class="md:hidden bg-white border-t border-gray-100 px-5 py-4 space-y-3 shadow-lg">

    <a href="{{ route('landing') }}" class="block text-orange-500 font-semibold">Home</a>
    <a href="{{ route('about') }}" class="block text-gray-700 font-semibold hover:text-orange-500">About</a>

    <div x-data="{ layananOpen: false }">
      <button @click="layananOpen = !layananOpen" class="w-full flex justify-between items-center text-gray-700 font-semibold hover:text-orange-500">
        Layanan
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform" :class="{ 'rotate-180': layananOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
      </button>
      <div x-show="layananOpen" x-transition class="mt-2 pl-4 space-y-1">
        <p class="text-xs text-orange-500 font-bold uppercase mb-1">Jenis Survei</p>
        @foreach ($jenis as $item)
          <a href="{{ route('layanan.show', $item->slug) }}" class="block text-sm text-gray-600 hover:text-orange-500 py-0.5">{{ $item->title }}</a>
        @endforeach
        <p class="text-xs text-orange-500 font-bold uppercase mt-2 mb-1">Layanan Tambahan</p>
        @foreach ($tambahan as $item)
          <a href="{{ route('layanan.show', $item->slug) }}" class="block text-sm text-gray-600 hover:text-orange-500 py-0.5">{{ $item->title }}</a>
        @endforeach
      </div>
    </div>

    <a href="{{ route('pricing') }}" class="block text-gray-700 font-semibold hover:text-orange-500">Harga</a>
    <a href="{{ route('blog.index') }}" class="block text-gray-700 font-semibold hover:text-orange-500">Blog</a>
    <a href="{{ route('contact') }}" class="block text-gray-700 font-semibold hover:text-orange-500">Contact Us</a>

    <div class="pt-3 border-t border-gray-100 flex gap-3">
      @auth
        <form method="POST" action="{{ route('logout') }}" class="w-full">@csrf
          <button type="submit" class="w-full bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-semibold">Logout</button>
        </form>
      @else
        <a href="{{ route('login') }}" class="flex-1 border-2 border-orange-500 text-orange-500 text-center px-3 py-2 rounded-lg text-sm font-semibold">Login</a>
        <a href="{{ route('register') }}" class="flex-1 bg-orange-500 text-white text-center px-3 py-2 rounded-lg text-sm font-bold">Daftar</a>
      @endauth
    </div>
  </div>

</nav>
