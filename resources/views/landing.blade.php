@extends('layouts.app')

@section('title', 'Home - Alteryx ONE')

@section('content')
    {{-- Hero Section --}}
    <section class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between py-20 px-6">
        <div class="max-w-xl mb-10 md:mb-0">
            <h1 class="text-4xl text-white md:text-5xl font-bold mb-6 leading-tight">
                PT.Market Research & Branding
            </h1>
            <p class="text-lg text-white mb-8">
                Kami bantu Anda ubah data jadi keputusan tepat.
            </p>
            <div class="flex gap-4">
                <button
                    class="bg-white text-black font-bold px-6 py-3 rounded-lg hover:bg-black hover:text-white transition">
                    Start Trial
                </button>
                <button
                    class="border-2 border-white text-white px-6 py-3 rounded-lg hover:bg-white hover:text-black transition">
                    Contact Sales
                </button>
            </div>
        </div>
        <div>
            <img src="https://www.alteryx.com/wp-content/uploads/new-homepage/alteryx-one.jpg" alt="Alteryx ONE Logo"
                class="w-48 h-48 rounded-full shadow-lg">
        </div>
    </section>

    {{-- Banner Section --}}
    <section class="relative w-full max-w-7xl mx-auto overflow-hidden rounded-xl shadow-xl my-10 h-36">
        {{-- Slide 1 --}}
        <div
            class="banner-slide active flex items-center justify-between px-10 bg-gradient-to-r from-yellow-300 to-red-500 h-36">
            <div class="max-w-[70%]">
                <h3 class="text-xl font-bold mb-2">FAST ANSWER</h3>
                <p class="text-sm text-black">Buat survei profesional dengan mudah, dapatkan hasil cepat dan terpercaya.</p>
            </div>
            <button class="bg-[#002244] text-white px-4 py-2 rounded hover:bg-[#004b7a]">
                Dapatkan Sekarang
            </button>
        </div>

        {{-- Slide 2 --}}
        <div class="banner-slide flex items-center justify-between px-10 bg-gradient-to-r from-green-300 to-blue-600 h-36">
            <div class="max-w-[70%]">
                <h3 class="text-xl font-bold mb-2">UNLIMITED SURVEY</h3>
                <p class="text-sm text-black">Didukung tim ahli berpengalaman dan terpercaya untuk hasil terbaik Anda.
                </p>
            </div>
            <button class="bg-[#002244] text-white px-4 py-2 rounded hover:bg-[#004b7a]">
                Pelajari Lebih Lanjut
            </button>
        </div>

        {{-- Slide 3 --}}
        <div class="banner-slide flex items-center justify-between px-10 bg-gradient-to-r from-purple-400 to-pink-500 h-36">
            <div class="max-w-[70%]">
                <h3 class="text-xl font-bold mb-2">REALTIME RESULT</h3>
                <p class="text-sm text-black">Pantau hasil survei real-time dari perangkat apa pun, kapan saja..</p>
            </div>
            <button class="bg-[#002244] text-white px-4 py-2 rounded hover:bg-[#004b7a]">
                Hubungi Kami
            </button>
        </div>

        {{-- Indicators --}}
        <div class="indicators absolute bottom-3 left-5 flex gap-2"></div>
    </section>

    {{-- Tabbed Section --}}
    <section class="w-11/12 max-w-6xl mx-auto mt-12">
        <!-- Tabs -->
        <div class="flex space-x-2 mb-0 border-b border-gray-300">
            <button
                class="tab-btn active px-6 py-3 bg-gradient-to-r from-[#FFB703] to-[#FB8500] text-white font-semibold rounded-t-md shadow-md transition-all  hover:scale-105 ">
                Unified Analytics
            </button>
            <button
                class="tab-btn px-6 py-3 bg-gradient-to-r from-[#FFDD00] to-[#FFB703] text-black font-semibold rounded-t-md shadow-md transition-all  hover:scale-105">
                AI-Ready Data
            </button>
            <button
                class="tab-btn px-6 py-3 bg-gradient-to-r from-[#FFD60A] to-[#FFA500] text-black font-semibold rounded-t-md shadow-md transition-all hover:scale-105">
                Secure Data Access
            </button>
        </div>



        <!-- Tab Content -->
        <div
            class="border border-white tab-content bg-gradient-to-br from-[#FFF3B0] via-[#FFD60A] to-[#FFA500] p-8 flex items-center justify-between rounded-b-md shadow-lg relative transition-all duration-300">
            <!-- Content for Unified Analytics -->
            <div class="tab-panel active w-1/2 pr-6 transition-opacity duration-300">
                <h2 class="text-2xl font-bold text-[#002244] mb-4">Data to Decisions, Faster</h2>
                <p class="text-gray-800 text-base mb-5">
                    Alteryx One empowers every team to turn data into action with AI-powered workflows,
                    self-service analytics, and unified access — driving smarter decisions at scale.
                </p>
                <button class="bg-white hover:bg-gray-100 text-black px-5 py-2 rounded font-semibold shadow-md">
                    Discover Alteryx One
                </button>
            </div>

            <!-- Content for AI-Ready Data -->
            <div class="tab-panel hidden w-1/2 pr-6 transition-opacity duration-300">
                <h2 class="text-2xl font-bold text-[#002244] mb-4">AI-Ready Data</h2>
                <p class="text-gray-800 text-base mb-5">
                    Prepare, enrich, and transform your data to make it AI-ready, ensuring accuracy and
                    governance for advanced analytics.
                </p>
                <button class="bg-white hover:bg-gray-100 text-black px-5 py-2 rounded font-semibold shadow-md">
                    Learn More
                </button>
            </div>

            <!-- Content for Secure Data Access -->
            <div class="tab-panel hidden w-1/2 pr-6 transition-opacity duration-300">
                <h2 class="text-2xl font-bold text-[#002244] mb-4">Secure Data Access</h2>
                <p class="text-gray-800 text-base mb-5">
                    Access your data securely with full governance, permissions, and enterprise-grade
                    compliance for peace of mind.
                </p>
                <button class="bg-white hover:bg-gray-100 text-black px-5 py-2 rounded font-semibold shadow-md">
                    Explore Security
                </button>
            </div>

            <!-- Image Area -->
            <div class="w-1/2 flex justify-center">
                <img src="https://www.alteryx.com/sites/default/files/2023-12/alteryx-one-platform.png"
                    alt="Platform Display"
                    class="rounded-lg shadow-xl max-w-sm transition-transform duration-300 hover:scale-105">
            </div>
        </div>
    </section>


    {{-- Partner Logos Section --}}
    <section class="py-12">
        <div class="w-11/12 max-w-6xl mx-auto text-center">
            <!-- Partner Logos -->
            <div class="flex flex-wrap justify-center items-center gap-12 mb-8">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/53/BT_Group_logo.svg/2560px-BT_Group_logo.svg.png"
                    alt="BT Logo" class="h-12 grayscale hover:grayscale-0 transition duration-300" />
                <img src="https://upload.wikimedia.org/wikipedia/commons/2/20/Fender_logo.png" alt="Fender Logo"
                    class="h-12 grayscale hover:grayscale-0 transition duration-300" />
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/1e/IQVIA_logo.svg/2560px-IQVIA_logo.svg.png"
                    alt="IQVIA Logo" class="h-12 grayscale hover:grayscale-0 transition duration-300" />
                <img src="https://upload.wikimedia.org/wikipedia/commons/0/05/DoorDash_Logo.png" alt="DoorDash Logo"
                    class="h-12 grayscale hover:grayscale-0 transition duration-300" />
                <img src="https://upload.wikimedia.org/wikipedia/commons/f/f9/Siemens_Energy_logo.svg"
                    alt="Siemens Energy Logo" class="h-12 grayscale hover:grayscale-0 transition duration-300" />
            </div>

            <!-- Link -->
            <a href="#" class="inline-block text-blue-400 hover:text-blue-300 text-sm font-semibold mb-10 transition">
                Read More Customer Stories &rarr;
            </a>

            <!-- Headline -->
            <h2 class="text-2xl md:text-3xl font-bold text-white mb-4">
                Analyze, Transform, and Prepare Your Data for AI — All in One Platform
            </h2>
            <p class="text-gray-200 max-w-2xl mx-auto text-sm md:text-base leading-relaxed">
                Get the AI, automation and insights your business wants with the security and governance
                you need to empower faster, smarter decision making.
            </p>
        </div>
    </section>

    {{-- Customer Success Stories --}}
    <section class="py-16 bg-transparent relative z-50">
        <div class="w-11/12 max-w-7xl mx-auto">
            <!-- Title & Subtitle -->
            <div class="text-center mb-10">
                <h2 class="text-3xl font-bold text-white">Customer Success Stories</h2>
                <p class="text-gray-300 text-sm md:text-base mt-2">
                    Organizations across the globe use the Alteryx AI-powered data analytics platform
                    to improve efficiencies, reduce costs and mitigate risk.
                </p>
            </div>

            <!-- Carousel Wrapper -->
            <div id="success-carousel" class="relative w-full h-[380px] overflow-hidden">
                <div id="success-carousel" class="relative h-full">
                    <!-- Card 1 -->
                    <div class="slide bg-white rounded-xl shadow-lg flex overflow-hidden">
                        <!-- Left Image -->
                        <div class="w-1/2">
                            <img src="https://www.alteryx.com/sites/default/files/2023-08/mclaren.jpg" alt="McLaren"
                                class="w-full h-full object-cover">
                        </div>
                        <!-- Right Content -->
                        <div class="w-1/2 p-6 flex flex-col justify-between">
                            <div>
                                <h3 class="text-yellow-600 text-3xl font-bold mb-1">11.8 billion</h3>
                                <p class="uppercase text-xs text-gray-500 mb-3">Data Points Consolidated</p>
                                <h4 class="text-lg font-bold text-gray-900 mb-2">
                                    McLaren Racing Fast Tracks Data Analytics
                                </h4>
                                <p class="text-sm text-gray-600">
                                    McLaren Racing uses Alteryx AI data analytics to collect and analyze
                                    real-time insights that improve race performance and car design.
                                </p>
                            </div>
                            <div class="flex gap-3 mt-4">
                                <button class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded">
                                    Read More
                                </button>
                                <button class="bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm px-4 py-2 rounded">
                                    Watch Now
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="slide bg-white rounded-xl shadow-lg flex overflow-hidden">
                        <div class="w-1/2">
                            <img src="https://www.alteryx.com/sites/default/files/2023-08/doordash.jpg" alt="DoorDash"
                                class="w-full h-full object-cover">
                        </div>
                        <div class="w-1/2 p-6 flex flex-col justify-between">
                            <div>
                                <h3 class="text-red-500 text-3xl font-bold mb-1">25,000</h3>
                                <p class="uppercase text-xs text-gray-500 mb-3">Hours Saved</p>
                                <h4 class="text-lg font-bold text-gray-900 mb-2">
                                    DoorDash Accelerates Revenue Insights
                                </h4>
                                <p class="text-sm text-gray-600">
                                    The accounting team at DoorDash uses Alteryx AI to automate financial
                                    processes and meet rigorous compliance requirements.
                                </p>
                            </div>
                            <div class="flex gap-3 mt-4">
                                <button class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded">
                                    Read More
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="slide bg-white rounded-xl shadow-lg flex overflow-hidden">
                        <div class="w-1/2">
                            <img src="https://www.alteryx.com/sites/default/files/2023-08/nielsen.jpg" alt="Nielsen"
                                class="w-full h-full object-cover">
                        </div>
                        <div class="w-1/2 p-6 flex flex-col justify-between">
                            <div>
                                <h3 class="text-blue-500 text-3xl font-bold mb-1">2,000+</h3>
                                <p class="uppercase text-xs text-gray-500 mb-3">Manual Processes Automated</p>
                                <h4 class="text-lg font-bold text-gray-900 mb-2">
                                    Nielsen Enables Faster Service Data
                                </h4>
                                <p class="text-sm text-gray-600">
                                    Nielsen leverages Alteryx automation for time savings and faster client
                                    data delivery, driving business-wide impact.
                                </p>
                            </div>
                            <div class="flex gap-3 mt-4">
                                <button class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded">
                                    Read More
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <button id="prev-slide"
                    class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-yellow-500 hover:bg-yellow-600 text-white w-10 h-10 rounded-full flex items-center justify-center shadow-md z-50">
                    &#10094;
                </button>
                <button id="next-slide"
                    class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-yellow-500 hover:bg-yellow-600 text-white w-10 h-10 rounded-full flex items-center justify-center shadow-md z-50">
                    &#10095;
                </button>
            </div>

            <!-- Footer Link -->
            <div class="text-center mt-8">
                <a href="#" class="inline-block text-blue-400 hover:text-blue-300 text-sm font-semibold transition">
                    Read More Customer Stories &rarr;
                </a>
            </div>
        </div>
    </section>


    {{-- ===== TESTIMONI SECTION ===== --}}
    <section class="py-16 relative overflow-hidden" id="testimoni">
        {{-- Background Decoration --}}
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-10 left-10 w-40 h-40 bg-green-400/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-56 h-56 bg-green-500/10 rounded-full blur-3xl"></div>
        </div>

        <div class="w-11/12 max-w-6xl mx-auto relative z-10">
            {{-- Header --}}
            <div class="text-center mb-12">
                <span class="inline-block bg-green-500/20 text-green-400 text-xs font-semibold uppercase tracking-widest px-4 py-1.5 rounded-full mb-4">
                    💬 Testimoni Pelanggan
                </span>
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-3">
                    Apa Kata Mereka?
                </h2>
                <p class="text-gray-400 text-sm md:text-base max-w-xl mx-auto">
                    Kepuasan klien adalah prioritas kami. Berikut percakapan nyata dari pelanggan setia kami.
                </p>
            </div>

            {{-- Carousel Wrapper --}}
            <div class="relative">
                <div id="testimoni-track" class="flex gap-6 transition-transform duration-500 ease-in-out">

                    {{-- Card 1 --}}
                    <div class="testimoni-card flex-none w-full sm:w-[320px]">
                        <div class="bg-[#111B21] rounded-2xl overflow-hidden shadow-2xl border border-white/10" style="min-height:480px">
                            {{-- WA Header --}}
                            <div class="bg-[#1F2C34] px-4 py-3 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-yellow-400 flex items-center justify-center text-black font-bold text-sm flex-shrink-0">SC</div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-white text-sm font-semibold truncate">Testimoni</p>
                                    <p class="text-green-400 text-xs">Budi Pertamax - VITAMIN</p>
                                </div>
                                <div class="flex items-center gap-3 text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01"/></svg>
                                </div>
                            </div>
                            {{-- WA Chat Body --}}
                            <div class="px-3 py-4 space-y-2 overflow-y-auto" style="background-image: url('data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22100%22%20height%3D%22100%22%3E%3Crect%20width%3D%22100%25%22%20height%3D%22100%25%22%20fill%3D%22%23111B21%22%2F%3E%3C%2Fsvg%3E')">
                                {{-- Incoming --}}
                                <div class="flex justify-start">
                                    <div class="bg-[#1F2C34] text-white text-sm rounded-lg rounded-tl-none px-3 py-2 max-w-[75%] shadow">
                                        <p>halo soalnya aku sebar di twitter juga kak jadi ngga tau 😅😁</p>
                                        <p class="text-[10px] text-gray-400 text-right mt-1">15.25</p>
                                    </div>
                                </div>
                                {{-- Incoming --}}
                                <div class="flex justify-start">
                                    <div class="bg-[#1F2C34] text-white text-sm rounded-lg rounded-tl-none px-3 py-2 max-w-[75%] shadow">
                                        <p>thankyou ya kakkkk</p>
                                        <p class="text-[10px] text-gray-400 text-right mt-1">15.26</p>
                                    </div>
                                </div>
                                {{-- Outgoing --}}
                                <div class="flex justify-end">
                                    <div class="bg-[#005C4B] text-white text-sm rounded-lg rounded-tr-none px-3 py-2 max-w-[75%] shadow">
                                        <p>Jd data kita di KK 40 ?</p>
                                        <p class="text-[10px] text-gray-400 text-right mt-1 flex items-center justify-end gap-1">16.17 <svg class="w-3 h-3 text-blue-400" fill="currentColor" viewBox="0 0 16 16"><path d="M12.354 4.354a.5.5 0 0 0-.708-.708L5 11.293 2.354 8.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l7-7zm-4.208 7-.896-.897.707-.707.896.896 6.5-6.5.707.707-7.914 7.2z"/></svg></p>
                                    </div>
                                </div>
                                {{-- Incoming --}}
                                <div class="flex justify-start">
                                    <div class="bg-[#1F2C34] text-white text-sm rounded-lg rounded-tl-none px-3 py-2 max-w-[75%] shadow">
                                        <p>untuk yang mana ini kak?</p>
                                        <p class="text-[10px] text-gray-400 text-right mt-1">16.17</p>
                                    </div>
                                </div>
                                {{-- Incoming --}}
                                <div class="flex justify-start">
                                    <div class="bg-[#1F2C34] text-white text-sm rounded-lg rounded-tl-none px-3 py-2 max-w-[75%] shadow">
                                        <p>20 yang lama kemarin</p>
                                        <p class="text-[10px] text-gray-400 text-right mt-1">16.20</p>
                                    </div>
                                </div>
                                {{-- Incoming --}}
                                <div class="flex justify-start">
                                    <div class="bg-[#1F2C34] text-white text-sm rounded-lg rounded-tl-none px-3 py-2 max-w-[75%] shadow">
                                        <p>20 yang ini kan ya</p>
                                        <p class="text-[10px] text-gray-400 text-right mt-1">16.21</p>
                                    </div>
                                </div>
                                {{-- Outgoing --}}
                                <div class="flex justify-end">
                                    <div class="bg-[#005C4B] text-white text-sm rounded-lg rounded-tr-none px-3 py-2 max-w-[75%] shadow">
                                        <p>Ok k</p>
                                        <p class="text-[10px] text-gray-400 text-right mt-1 flex items-center justify-end gap-1">16.21 <svg class="w-3 h-3 text-blue-400" fill="currentColor" viewBox="0 0 16 16"><path d="M12.354 4.354a.5.5 0 0 0-.708-.708L5 11.293 2.354 8.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l7-7zm-4.208 7-.896-.897.707-.707.896.896 6.5-6.5.707.707-7.914 7.2z"/></svg></p>
                                    </div>
                                </div>
                                {{-- Incoming --}}
                                <div class="flex justify-start">
                                    <div class="bg-[#1F2C34] text-white text-sm rounded-lg rounded-tl-none px-3 py-2 max-w-[75%] shadow">
                                        <p>kak ini aku baru hitung nambahnya hari ini 2 kan ya</p>
                                        <p class="text-[10px] text-gray-400 text-right mt-1">16.23</p>
                                    </div>
                                </div>
                            </div>
                            {{-- WA Footer --}}
                            <div class="bg-[#1F2C34] px-4 py-2 text-gray-400 text-xs flex items-center gap-2 border-t border-white/5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 6h18M3 14h18M3 18h18"/></svg>
                                <span class="truncate">Balas kepada surveycenterindonesia...</span>
                            </div>
                        </div>
                        {{-- Name Badge --}}
                        <div class="mt-3 text-center">
                            <span class="text-white font-semibold text-sm">Budi Pertamax</span>
                            <span class="text-gray-500 text-xs ml-2">· Klien VITAMIN</span>
                        </div>
                    </div>

                    {{-- Card 2 --}}
                    <div class="testimoni-card flex-none w-full sm:w-[320px]">
                        <div class="bg-[#111B21] rounded-2xl overflow-hidden shadow-2xl border border-white/10" style="min-height:480px">
                            <div class="bg-[#1F2C34] px-4 py-3 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">AN</div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-white text-sm font-semibold truncate">Testimoni</p>
                                    <p class="text-green-400 text-xs">Anita Rahma - Responden</p>
                                </div>
                                <div class="flex items-center gap-3 text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01"/></svg>
                                </div>
                            </div>
                            <div class="px-3 py-4 space-y-2 bg-[#111B21]">
                                <div class="flex justify-start">
                                    <div class="bg-[#1F2C34] text-white text-sm rounded-lg rounded-tl-none px-3 py-2 max-w-[75%] shadow">
                                        <p>kak linknya sudah bisa diakses</p>
                                        <p class="text-[10px] text-gray-400 text-right mt-1">10.05</p>
                                    </div>
                                </div>
                                <div class="flex justify-start">
                                    <div class="bg-[#1F2C34] text-white text-sm rounded-lg rounded-tl-none px-3 py-2 max-w-[75%] shadow">
                                        <p>udah aku isi semua kak, gampang banget 😊</p>
                                        <p class="text-[10px] text-gray-400 text-right mt-1">10.06</p>
                                    </div>
                                </div>
                                <div class="flex justify-end">
                                    <div class="bg-[#005C4B] text-white text-sm rounded-lg rounded-tr-none px-3 py-2 max-w-[75%] shadow">
                                        <p>Makasih kak Anita, hadiahnya segera dikirim ya 🎁</p>
                                        <p class="text-[10px] text-gray-400 text-right mt-1 flex items-center justify-end gap-1">10.08 <svg class="w-3 h-3 text-blue-400" fill="currentColor" viewBox="0 0 16 16"><path d="M12.354 4.354a.5.5 0 0 0-.708-.708L5 11.293 2.354 8.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l7-7zm-4.208 7-.896-.897.707-.707.896.896 6.5-6.5.707.707-7.914 7.2z"/></svg></p>
                                    </div>
                                </div>
                                <div class="flex justify-start">
                                    <div class="bg-[#1F2C34] text-white text-sm rounded-lg rounded-tl-none px-3 py-2 max-w-[75%] shadow">
                                        <p>waaah makasih kak! kapan kapan mau ikut lagi 🙏</p>
                                        <p class="text-[10px] text-gray-400 text-right mt-1">10.09</p>
                                    </div>
                                </div>
                                <div class="flex justify-start">
                                    <div class="bg-[#1F2C34] text-white text-sm rounded-lg rounded-tl-none px-3 py-2 max-w-[75%] shadow">
                                        <p>surveynya seru, pertanyaannya jelas ga ribet</p>
                                        <p class="text-[10px] text-gray-400 text-right mt-1">10.10</p>
                                    </div>
                                </div>
                                <div class="flex justify-end">
                                    <div class="bg-[#005C4B] text-white text-sm rounded-lg rounded-tr-none px-3 py-2 max-w-[75%] shadow">
                                        <p>Senang denger itu kak! Nantikan survey berikutnya 😊</p>
                                        <p class="text-[10px] text-gray-400 text-right mt-1 flex items-center justify-end gap-1">10.11 <svg class="w-3 h-3 text-blue-400" fill="currentColor" viewBox="0 0 16 16"><path d="M12.354 4.354a.5.5 0 0 0-.708-.708L5 11.293 2.354 8.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l7-7zm-4.208 7-.896-.897.707-.707.896.896 6.5-6.5.707.707-7.914 7.2z"/></svg></p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-[#1F2C34] px-4 py-2 text-gray-400 text-xs flex items-center gap-2 border-t border-white/5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 6h18M3 14h18M3 18h18"/></svg>
                                <span class="truncate">Balas kepada surveycenterindonesia...</span>
                            </div>
                        </div>
                        <div class="mt-3 text-center">
                            <span class="text-white font-semibold text-sm">Anita Rahma</span>
                            <span class="text-gray-500 text-xs ml-2">· Responden Setia</span>
                        </div>
                    </div>

                    {{-- Card 3 --}}
                    <div class="testimoni-card flex-none w-full sm:w-[320px]">
                        <div class="bg-[#111B21] rounded-2xl overflow-hidden shadow-2xl border border-white/10" style="min-height:480px">
                            <div class="bg-[#1F2C34] px-4 py-3 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-purple-500 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">RD</div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-white text-sm font-semibold truncate">Testimoni</p>
                                    <p class="text-green-400 text-xs">Rudi Santoso - Klien</p>
                                </div>
                                <div class="flex items-center gap-3 text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01"/></svg>
                                </div>
                            </div>
                            <div class="px-3 py-4 space-y-2 bg-[#111B21]">
                                <div class="flex justify-start">
                                    <div class="bg-[#1F2C34] text-white text-sm rounded-lg rounded-tl-none px-3 py-2 max-w-[75%] shadow">
                                        <p>kak data sudah masuk semua?</p>
                                        <p class="text-[10px] text-gray-400 text-right mt-1">09.30</p>
                                    </div>
                                </div>
                                <div class="flex justify-end">
                                    <div class="bg-[#005C4B] text-white text-sm rounded-lg rounded-tr-none px-3 py-2 max-w-[75%] shadow">
                                        <p>Sudah kak, total 150 responden valid 👍</p>
                                        <p class="text-[10px] text-gray-400 text-right mt-1 flex items-center justify-end gap-1">09.32 <svg class="w-3 h-3 text-blue-400" fill="currentColor" viewBox="0 0 16 16"><path d="M12.354 4.354a.5.5 0 0 0-.708-.708L5 11.293 2.354 8.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l7-7zm-4.208 7-.896-.897.707-.707.896.896 6.5-6.5.707.707-7.914 7.2z"/></svg></p>
                                    </div>
                                </div>
                                <div class="flex justify-start">
                                    <div class="bg-[#1F2C34] text-white text-sm rounded-lg rounded-tl-none px-3 py-2 max-w-[75%] shadow">
                                        <p>wah cepet banget! deadline kita masih 2 hari lagi</p>
                                        <p class="text-[10px] text-gray-400 text-right mt-1">09.33</p>
                                    </div>
                                </div>
                                <div class="flex justify-start">
                                    <div class="bg-[#1F2C34] text-white text-sm rounded-lg rounded-tl-none px-3 py-2 max-w-[75%] shadow">
                                        <p>laporan analisisnya kapan siap kak?</p>
                                        <p class="text-[10px] text-gray-400 text-right mt-1">09.34</p>
                                    </div>
                                </div>
                                <div class="flex justify-end">
                                    <div class="bg-[#005C4B] text-white text-sm rounded-lg rounded-tr-none px-3 py-2 max-w-[75%] shadow">
                                        <p>Bisa besok pagi kak, kami kebut malam ini 💪</p>
                                        <p class="text-[10px] text-gray-400 text-right mt-1 flex items-center justify-end gap-1">09.35 <svg class="w-3 h-3 text-blue-400" fill="currentColor" viewBox="0 0 16 16"><path d="M12.354 4.354a.5.5 0 0 0-.708-.708L5 11.293 2.354 8.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l7-7zm-4.208 7-.896-.897.707-.707.896.896 6.5-6.5.707.707-7.914 7.2z"/></svg></p>
                                    </div>
                                </div>
                                <div class="flex justify-start">
                                    <div class="bg-[#1F2C34] text-white text-sm rounded-lg rounded-tl-none px-3 py-2 max-w-[75%] shadow">
                                        <p>mantap kak, nanti saya rekomendasikan ke teman 🔥</p>
                                        <p class="text-[10px] text-gray-400 text-right mt-1">09.36</p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-[#1F2C34] px-4 py-2 text-gray-400 text-xs flex items-center gap-2 border-t border-white/5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 6h18M3 14h18M3 18h18"/></svg>
                                <span class="truncate">Balas kepada surveycenterindonesia...</span>
                            </div>
                        </div>
                        <div class="mt-3 text-center">
                            <span class="text-white font-semibold text-sm">Rudi Santoso</span>
                            <span class="text-gray-500 text-xs ml-2">· Klien Riset Produk</span>
                        </div>
                    </div>

                    {{-- Card 4 --}}
                    <div class="testimoni-card flex-none w-full sm:w-[320px]">
                        <div class="bg-[#111B21] rounded-2xl overflow-hidden shadow-2xl border border-white/10" style="min-height:480px">
                            <div class="bg-[#1F2C34] px-4 py-3 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-red-500 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">LY</div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-white text-sm font-semibold truncate">Testimoni</p>
                                    <p class="text-green-400 text-xs">Lily - Brand Research</p>
                                </div>
                                <div class="flex items-center gap-3 text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01"/></svg>
                                </div>
                            </div>
                            <div class="px-3 py-4 space-y-2 bg-[#111B21]">
                                <div class="flex justify-start">
                                    <div class="bg-[#1F2C34] text-white text-sm rounded-lg rounded-tl-none px-3 py-2 max-w-[75%] shadow">
                                        <p>kak mau tanya, bisa minta sample surveynya dulu ga?</p>
                                        <p class="text-[10px] text-gray-400 text-right mt-1">14.00</p>
                                    </div>
                                </div>
                                <div class="flex justify-end">
                                    <div class="bg-[#005C4B] text-white text-sm rounded-lg rounded-tr-none px-3 py-2 max-w-[75%] shadow">
                                        <p>Bisa kak, ini link demo-nya 👇</p>
                                        <p class="text-[10px] text-gray-400 text-right mt-1 flex items-center justify-end gap-1">14.01 <svg class="w-3 h-3 text-blue-400" fill="currentColor" viewBox="0 0 16 16"><path d="M12.354 4.354a.5.5 0 0 0-.708-.708L5 11.293 2.354 8.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l7-7zm-4.208 7-.896-.897.707-.707.896.896 6.5-6.5.707.707-7.914 7.2z"/></svg></p>
                                    </div>
                                </div>
                                <div class="flex justify-end">
                                    <div class="bg-[#005C4B] text-white text-sm rounded-lg rounded-tr-none px-3 py-2 max-w-[75%] shadow">
                                        <p class="text-blue-300 underline text-xs">surveycenter.co.id/demo</p>
                                        <p class="text-[10px] text-gray-400 text-right mt-1 flex items-center justify-end gap-1">14.01 <svg class="w-3 h-3 text-blue-400" fill="currentColor" viewBox="0 0 16 16"><path d="M12.354 4.354a.5.5 0 0 0-.708-.708L5 11.293 2.354 8.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l7-7zm-4.208 7-.896-.897.707-.707.896.896 6.5-6.5.707.707-7.914 7.2z"/></svg></p>
                                    </div>
                                </div>
                                <div class="flex justify-start">
                                    <div class="bg-[#1F2C34] text-white text-sm rounded-lg rounded-tl-none px-3 py-2 max-w-[75%] shadow">
                                        <p>wah keren banget tampilannya! profesional 👍</p>
                                        <p class="text-[10px] text-gray-400 text-right mt-1">14.05</p>
                                    </div>
                                </div>
                                <div class="flex justify-start">
                                    <div class="bg-[#1F2C34] text-white text-sm rounded-lg rounded-tl-none px-3 py-2 max-w-[75%] shadow">
                                        <p>langsung order kak untuk 200 responden ya</p>
                                        <p class="text-[10px] text-gray-400 text-right mt-1">14.06</p>
                                    </div>
                                </div>
                                <div class="flex justify-end">
                                    <div class="bg-[#005C4B] text-white text-sm rounded-lg rounded-tr-none px-3 py-2 max-w-[75%] shadow">
                                        <p>Siap kak! Invoice segera kami kirimkan 🎉</p>
                                        <p class="text-[10px] text-gray-400 text-right mt-1 flex items-center justify-end gap-1">14.07 <svg class="w-3 h-3 text-blue-400" fill="currentColor" viewBox="0 0 16 16"><path d="M12.354 4.354a.5.5 0 0 0-.708-.708L5 11.293 2.354 8.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l7-7zm-4.208 7-.896-.897.707-.707.896.896 6.5-6.5.707.707-7.914 7.2z"/></svg></p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-[#1F2C34] px-4 py-2 text-gray-400 text-xs flex items-center gap-2 border-t border-white/5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 6h18M3 14h18M3 18h18"/></svg>
                                <span class="truncate">Balas kepada surveycenterindonesia...</span>
                            </div>
                        </div>
                        <div class="mt-3 text-center">
                            <span class="text-white font-semibold text-sm">Lily</span>
                            <span class="text-gray-500 text-xs ml-2">· Brand Research Client</span>
                        </div>
                    </div>

                </div>{{-- /#testimoni-track --}}

                {{-- Prev Button --}}
                <button id="testimoni-prev"
                    class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 bg-green-500 hover:bg-green-600 text-white w-10 h-10 rounded-full shadow-lg flex items-center justify-center z-20 transition">
                    &#10094;
                </button>
                {{-- Next Button --}}
                <button id="testimoni-next"
                    class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 bg-green-500 hover:bg-green-600 text-white w-10 h-10 rounded-full shadow-lg flex items-center justify-center z-20 transition">
                    &#10095;
                </button>
            </div>

            {{-- Dots --}}
            <div id="testimoni-dots" class="flex justify-center gap-2 mt-8"></div>
        </div>
    </section>

    <style>
        #testimoni-track {
            display: flex;
            gap: 1.5rem;
            will-change: transform;
        }
        .testimoni-card {
            flex: 0 0 auto;
        }
        @media (max-width: 640px) {
            .testimoni-card { width: 90vw; }
        }
    </style>

    <script>
        (function () {
            const track = document.getElementById('testimoni-track');
            const cards = document.querySelectorAll('.testimoni-card');
            const dotsContainer = document.getElementById('testimoni-dots');
            const btnPrev = document.getElementById('testimoni-prev');
            const btnNext = document.getElementById('testimoni-next');

            let current = 0;
            const total = cards.length;
            let cardWidth = 0;
            let gapWidth = 24; // gap-6 = 1.5rem = 24px
            let visibleCount = 1;
            let autoTimer;

            // Build dots
            for (let i = 0; i < total; i++) {
                const dot = document.createElement('button');
                dot.className = 'w-2.5 h-2.5 rounded-full transition-colors duration-300 ' + (i === 0 ? 'bg-green-400' : 'bg-gray-600');
                dot.addEventListener('click', () => goTo(i));
                dotsContainer.appendChild(dot);
            }

            function updateDots() {
                dotsContainer.querySelectorAll('button').forEach((d, i) => {
                    d.className = 'w-2.5 h-2.5 rounded-full transition-colors duration-300 ' + (i === current ? 'bg-green-400' : 'bg-gray-600');
                });
            }

            function calcDimensions() {
                cardWidth = cards[0].offsetWidth;
                const container = track.parentElement;
                visibleCount = Math.max(1, Math.floor((container.offsetWidth + gapWidth) / (cardWidth + gapWidth)));
            }

            function goTo(index) {
                calcDimensions();
                const maxIndex = Math.max(0, total - visibleCount);
                current = Math.max(0, Math.min(index, maxIndex));
                const offset = current * (cardWidth + gapWidth);
                track.style.transform = `translateX(-${offset}px)`;
                updateDots();
            }

            btnNext.addEventListener('click', () => { goTo(current + 1); resetAuto(); });
            btnPrev.addEventListener('click', () => { goTo(current - 1); resetAuto(); });

            function resetAuto() {
                clearInterval(autoTimer);
                autoTimer = setInterval(() => {
                    calcDimensions();
                    const maxIndex = Math.max(0, total - visibleCount);
                    const next = current >= maxIndex ? 0 : current + 1;
                    goTo(next);
                }, 4000);
            }

            window.addEventListener('resize', () => goTo(current));
            calcDimensions();
            updateDots();
            resetAuto();
        })();
    </script>

@endsection
