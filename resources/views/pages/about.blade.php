@extends('layouts.app')
@section('seo_slug', 'about')

@section('content')
<div class="w-full min-h-screen bg-white">
    <div class="max-w-6xl mx-auto px-6 py-12">
        <!-- Judul -->
        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-10 text-center">
            Selamat Datang di <br>
            <span class="text-yellow-500">Survey Center Indonesia (Veycat Apps)</span>
        </h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
            <!-- Kiri: Teks -->
            <div class="space-y-6 text-gray-700 leading-relaxed">
                <p>
                    Survey Center Indonesia adalah sebuah perusahaan yang membantu anda untuk melakukan survey online, 
                    Menyebarkan Kuesioner secara online maupun offline melalui komunitas dan sesuai dengan responden yang anda inginkan. 
                    Melakukan distribusi data dan pemetaan Pasar.
                </p>

                <div>
                    <h2 class="text-xl font-bold text-gray-900 mb-2">Visi:</h2>
                    <p>
                        Menjadi perusahaan survei terbaik di Indonesia dan menjadi mitra utama dalam mengubah industri survei 
                        melalui penelitian untuk meningkatkan pendapatan bisnis.
                    </p>
                </div>

                <div>
                    <h2 class="text-xl font-bold text-gray-900 mb-2">Misi:</h2>
                    <p>
                        Kami berkomitmen untuk membantu banyak pelanggan dengan melakukan berbagai macam survei yang akurat, beragam, 
                        dan efisien, sehingga dapat membantu pengusaha dan peneliti dalam memetakan pasar, membuat strategi yang baik, 
                        dan menjalankan penelitian yang efektif.
                    </p>
                    <p class="mt-2">
                        Kami adalah partner penelitian yang fleksibel, berpengalaman, dan berkontribusi pada pertumbuhan bisnis anda.
                    </p>
                </div>
            </div>

            <!-- Kanan: Gambar -->
            <div class="flex justify-center">
                <img src="{{ asset('assets/owl-mascot.png') }}" 
                     alt="Survey Center Indonesia" 
                     class="w-80 md:w-[420px] object-contain">
            </div>
        </div>
    </div>
</div>
@endsection
