@extends('layouts.app')

@section('content')
    {{-- Pastikan Alpine.js ter-include di layout (lihat catatan di bawah jika belum) --}}
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <div class="bg-gray-50 min-h-screen flex items-center justify-center px-4 py-10" x-data="{
        showModal: false,
        title: '{{ old('title') }}',
        question: {{ old('question_count', 0) }},
        respond: {{ old('respond_count', 0) }},
        google_form_link: '{{ old('google_form_link') }}',
        price: 1000
    }" x-cloak>

        <div class="w-full max-w-3xl bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">

            <!-- Header Perusahaan -->
            <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 px-6 py-8 text-center">
                <img src="https://surveycenter.co.id/wp-content/uploads/2023/09/SCI2.png" alt="Survey Center Logo"
                    class="mx-auto max-h-[70px] object-contain mb-4 drop-shadow-md">
                <h1 class="text-2xl font-extrabold text-white tracking-wide">Survey Center Indonesia</h1>
                <p class="text-yellow-50 text-sm mt-2 max-w-2xl mx-auto leading-relaxed">
                    Kami membantu Anda melakukan survey online maupun offline dengan distribusi data yang terukur,
                    terpercaya, dan tepat sasaran.
                </p>
            </div>

            <!-- Body -->
            <div class="p-8 space-y-8">

                <!-- Rules & Announcements -->
                <div class="space-y-4">
                    <div class="bg-red-50 border-l-4 border-red-400 px-4 py-3 rounded-md shadow-sm">
                        <div class="flex items-center gap-2 text-red-700 font-semibold mb-1">
                            <!-- exclamation icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.29 3.86l-6.82 11.83A2 2 0 004.98 19h14.04a2 2 0 001.51-3.31L13.71 3.86a2 2 0 00-3.42 0z" />
                            </svg>
                            Larangan
                        </div>
                        <ul class="list-disc list-inside text-sm text-red-600 space-y-1">
                            <li>Dilarang mengandung SARA, pornografi, atau ujaran kebencian.</li>
                            <li>Pertanyaan harus sesuai etika & norma sosial.</li>
                            <li>Data responden wajib dijaga kerahasiaannya.</li>
                        </ul>
                    </div>

                    <div
                        class="bg-blue-50 border-l-4 border-blue-400 px-4 py-3 rounded-md shadow-sm flex items-start gap-2">
                        <!-- info icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mt-0.5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M12 20.5c4.687 0 8.5-3.813 8.5-8.5S16.687 3.5 12 3.5 3.5 7.313 3.5 12 7.313 20.5 12 20.5z" />
                        </svg>
                        <p class="text-sm text-blue-700">
                            <strong>Pengumuman:</strong> Survey akan diverifikasi oleh tim kami sebelum dipublikasikan.
                        </p>
                    </div>

                    <div
                        class="bg-yellow-50 border-l-4 border-yellow-400 px-4 py-3 rounded-md shadow-sm flex items-start gap-2">
                        <!-- note / warning icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-600 mt-0.5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M12 20.5c4.687 0 8.5-3.813 8.5-8.5S16.687 3.5 12 3.5 3.5 7.313 3.5 12 7.313 20.5 12 20.5z" />
                        </svg>
                        <p class="text-sm text-yellow-800">
                            Semua data wajib diisi lengkap. Formulir yang tidak lengkap tidak dapat diproses.
                        </p>
                    </div>
                </div>

                {{-- Error Message (server side) --}}
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Form Survey + Transaksi (single form) -->
                <form x-ref="form" action="{{ route('surveys.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Detail Survey -->
                    <div class="space-y-4">
                        <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            <!-- clipboard icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m-6-8h6M5 7h14M6 3h12a1 1 0 011 1v16a1 1 0 01-1 1H6a1 1 0 01-1-1V4a1 1 0 011-1z" />
                            </svg>
                            Detail Survey
                        </h2>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Judul Survey <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="title" x-model="title" value="{{ old('title') }}"
                                class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 text-sm px-3 py-2 shadow-sm"
                                placeholder="Contoh: Survei Kepuasan Pelanggan 2025" required>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Jumlah Pertanyaan <span
                                    class="text-red-500">*</span></label>
                            <input type="number" name="question_count" x-model.number="question"
                                value="{{ old('question_count') }}"
                                class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 text-sm px-3 py-2 shadow-sm"
                                min="1" placeholder="Masukkan jumlah pertanyaan" required>
                        </div>

                        <!-- Jumlah Responden (di bawah Jumlah Pertanyaan) -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Jumlah Responden <span
                                    class="text-red-500">*</span></label>
                            <input type="number" name="respond_count" x-model.number="respond"
                                value="{{ old('respond_count') }}"
                                class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 text-sm px-3 py-2 shadow-sm"
                                min="1" placeholder="Masukkan jumlah responden" required>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Link Form Survey <span class="text-red-500">*</span></label>
                            <input type="url" name="google_form_link" x-model="google_form_link"
                                value="{{ old('google_form_link') }}"
                                class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 text-sm px-3 py-2 shadow-sm"
                                placeholder="https://docs.google.com/forms/..." required>
                            <p class="mt-1 text-xs text-gray-500">
                                Link wajib diisi. Sistem memvalidasi domain form dan mengecek kecocokan judul.
                                Platform didukung: Google Forms, Microsoft Forms, Typeform, Jotform, Tally, Formstack.
                            </p>
                        </div>
                    </div>

                    {{-- small inline client-side hint (hidden by default) --}}
                    <p x-ref="err" class="text-sm text-red-600 hidden">Harap isi semua field dengan benar.</p>

                    <!-- Button menghitung (bukan submit) -->
                    <div class="flex justify-end pt-4">
                        <button type="button"
                            @click="if(!title || question < 1 || respond < 1 || !google_form_link) { $refs.err.classList.remove('hidden'); setTimeout(()=> $refs.err.classList.add('hidden'), 3500); return; } showModal = true"
                            class="px-6 py-2.5 bg-gradient-to-r from-yellow-400 to-yellow-500 text-white text-sm font-semibold rounded-xl shadow hover:from-yellow-500 hover:to-yellow-600 transition-all duration-200 flex items-center gap-2">
                            <!-- arrow icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 12h14M12 5l7 7-7 7" />
                            </svg>
                            Hitung & Lihat Total
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Popup -->
        <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
            <!-- overlay -->
            <div class="absolute inset-0 bg-black bg-opacity-40 transition-opacity" @click="showModal = false"></div>

            <!-- modal panel -->
            <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md p-6 mx-4"
                @keydown.escape.window="showModal = false" @click.away="showModal = false" x-transition>
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <!-- cash icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8v10" />
                    </svg>
                    Detail Transaksi
                </h3>

                <!-- Invoice Breakdown -->
                <div class="text-sm text-gray-700 mb-4 border rounded-lg overflow-hidden">
                    <!-- Header -->
                    <div class="bg-gray-100 px-4 py-2 font-semibold text-gray-800 border-b">
                        Ringkasan Biaya
                    </div>

                    <!-- Body -->
                    <div class="divide-y">
                        <div class="flex justify-between px-4 py-2">
                            <span>Biaya per pertanyaan</span>
                            <span x-text="`Rp ${price.toLocaleString('id-ID')}`"></span>
                        </div>
                        <div class="flex justify-between px-4 py-2">
                            <span>Jumlah pertanyaan</span>
                            <span x-text="question"></span>
                        </div>
                        <div class="flex justify-between px-4 py-2">
                            <span>Total per pertanyaan</span>
                            <span x-text="`Rp ${(question * price).toLocaleString('id-ID')}`"></span>
                        </div>
                        <div class="flex justify-between px-4 py-2">
                            <span>Jumlah responden</span>
                            <span x-text="respond"></span>
                        </div>
                    </div>

                    <!-- Footer / Total -->
                    <div class="bg-gray-50 px-4 py-3 flex justify-between items-center font-bold text-green-600 text-base">
                        <span>Total yang harus dibayar</span>
                        <span class="text-xl"
                            x-text="`Rp ${(question * price * respond).toLocaleString('id-ID')}`"></span>
                    </div>
                </div>


                <div class="flex justify-end gap-3 mt-4">
                    <button type="button" @click="showModal = false"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                        Batal
                    </button>

                    <!-- Konfirmasi: submit form -->
                    <button type="button" @click="$refs.form.submit()"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Konfirmasi & Proses
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
