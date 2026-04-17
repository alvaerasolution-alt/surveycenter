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
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="bg-white border border-gray-200 rounded-lg p-3 shadow-sm">
                            <p class="text-xs text-gray-500">Harga Dasar</p>
                            <p class="text-lg font-bold text-orange-600">Rp 1.000</p>
                            <p class="text-[11px] text-gray-500">per pertanyaan / responden</p>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-3 shadow-sm">
                            <p class="text-xs text-gray-500">Diskon</p>
                            <p class="text-sm font-semibold text-gray-800">Mahasiswa 50% • Perusahaan 30%</p>
                            <p class="text-[11px] text-gray-500">Umum tanpa diskon</p>
                        </div>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-3 shadow-sm">
                            <p class="text-xs text-red-600">Minimum Order</p>
                            <p class="text-sm font-bold text-red-700">Rp 50.000 / survey</p>
                            <p class="text-[11px] text-red-600">Wajib terpenuhi saat checkout</p>
                        </div>
                    </div>

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

                            <div class="mt-3 flex items-center gap-2">
                                <button type="button" id="analyzeFormButton"
                                    class="inline-flex items-center gap-2 rounded-lg border border-blue-200 bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700 hover:bg-blue-100 transition">
                                    Analisa AI Form
                                </button>
                                <span id="analyzeFormLoading" class="hidden text-xs text-blue-600">Menganalisa...</span>
                            </div>

                            <div id="aiAnalyzerCard" class="hidden mt-3 rounded-xl border border-gray-200 bg-gray-50 p-4 space-y-3">
                                <div class="flex items-center justify-between">
                                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">AI Form Analyzer</p>
                                    <span id="aiAcceptedBadge" class="inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-semibold"></span>
                                </div>

                                <div class="space-y-1">
                                    <p class="text-[11px] text-gray-500">Judul Form</p>
                                    <p id="aiDetectedTitle" class="text-sm font-semibold text-gray-900">-</p>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div class="rounded-lg border border-gray-200 bg-white p-3">
                                        <p class="text-[11px] text-gray-500">Persentase Kemiripan Judul</p>
                                        <p id="aiTitlePercent" class="text-base font-bold text-gray-900">-</p>
                                        <p id="aiTitleStatus" class="text-xs mt-1"></p>
                                    </div>
                                    <div class="rounded-lg border border-gray-200 bg-white p-3">
                                        <p class="text-[11px] text-gray-500">Jumlah Soal (Input vs Form)</p>
                                        <p id="aiQuestionCount" class="text-base font-bold text-gray-900">-</p>
                                        <p id="aiQuestionStatus" class="text-xs mt-1"></p>
                                    </div>
                                </div>

                                <div class="rounded-lg border border-gray-200 bg-white p-3">
                                    <p class="text-[11px] text-gray-500">Review Pertanyaan</p>
                                    <p id="aiQuestionReview" class="text-sm font-medium text-gray-800 mt-1">-</p>
                                </div>

                                <div class="rounded-lg border border-gray-200 bg-white p-3">
                                    <div class="flex items-center justify-between gap-2">
                                        <p class="text-[11px] text-gray-500">Pertanyaan Terdeteksi</p>
                                        <span id="aiDetectedQuestionsMeta" class="text-[11px] text-gray-400">0 item</span>
                                    </div>
                                    <input type="text" id="aiDetectedQuestionsSearch"
                                        class="hidden mt-2 w-full rounded-lg border border-gray-200 px-2.5 py-1.5 text-xs focus:border-blue-400 focus:ring-blue-400"
                                        placeholder="Cari pertanyaan...">
                                    <ul id="aiDetectedQuestions" class="mt-1 list-disc list-inside text-xs text-gray-700 space-y-1">
                                        <li>-</li>
                                    </ul>
                                    <button type="button" id="aiDetectedQuestionsToggle"
                                        class="hidden mt-2 text-[11px] font-semibold text-blue-600 hover:text-blue-700">
                                        Lihat semua
                                    </button>
                                </div>

                                <div class="rounded-lg border border-gray-200 bg-white p-3">
                                    <p class="text-[11px] text-gray-500 mb-2">Tipe Soal Terdeteksi</p>
                                    <div id="aiQuestionTypeSummary" class="flex flex-wrap gap-2">
                                        <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-1 text-[11px] text-gray-600">Belum ada data</span>
                                    </div>
                                </div>

                                <details class="rounded-lg border border-gray-200 bg-white p-3">
                                    <summary class="cursor-pointer text-[11px] font-semibold text-gray-600">Debug Analyzer</summary>
                                    <div class="mt-2 space-y-1 text-[11px] text-gray-600">
                                        <p>Entry ID terdeteksi: <span id="aiDebugEntryCount" class="font-semibold text-gray-800">0</span></p>
                                        <p>Load data tersedia: <span id="aiDebugLoadData" class="font-semibold text-gray-800">Tidak</span></p>
                                        <p>Judul pertanyaan terpetakan: <span id="aiDebugQuestionTitleCount" class="font-semibold text-gray-800">0</span></p>
                                        <p class="break-all">Entry IDs: <span id="aiDebugEntryIds" class="font-mono text-[10px] text-gray-700">-</span></p>
                                    </div>
                                </details>

                                <p id="aiAnalyzerError" class="hidden text-xs text-red-600"></p>
                            </div>
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const button = document.getElementById('analyzeFormButton');
            const loading = document.getElementById('analyzeFormLoading');
            const card = document.getElementById('aiAnalyzerCard');
            const err = document.getElementById('aiAnalyzerError');
            const badge = document.getElementById('aiAcceptedBadge');
            const detectedTitle = document.getElementById('aiDetectedTitle');
            const titlePercent = document.getElementById('aiTitlePercent');
            const titleStatus = document.getElementById('aiTitleStatus');
            const questionCount = document.getElementById('aiQuestionCount');
            const questionStatus = document.getElementById('aiQuestionStatus');
            const questionReview = document.getElementById('aiQuestionReview');
            const detectedQuestions = document.getElementById('aiDetectedQuestions');
            const detectedQuestionsMeta = document.getElementById('aiDetectedQuestionsMeta');
            const detectedQuestionsToggle = document.getElementById('aiDetectedQuestionsToggle');
            const detectedQuestionsSearch = document.getElementById('aiDetectedQuestionsSearch');
            const questionTypeSummary = document.getElementById('aiQuestionTypeSummary');
            const debugEntryCount = document.getElementById('aiDebugEntryCount');
            const debugLoadData = document.getElementById('aiDebugLoadData');
            const debugQuestionTitleCount = document.getElementById('aiDebugQuestionTitleCount');
            const debugEntryIds = document.getElementById('aiDebugEntryIds');
            const form = button.closest('form');
            const titleInput = form.querySelector('input[name="title"]');
            const questionInput = form.querySelector('input[name="question_count"]');
            const linkInput = form.querySelector('input[name="google_form_link"]');
            const tokenInput = form.querySelector('input[name="_token"]');
            const detectedPreviewLimit = 8;
            let detectedQuestionsData = [];
            let detectedQuestionsExpanded = false;
            let detectedQuestionsKeyword = '';
            let detectedQuestionItems = [];
            let autoAnalyzeTimer = null;
            let inFlightController = null;

            if (!button) {
                return;
            }

            function resetView() {
                card.classList.remove('hidden');
                err.classList.add('hidden');
                err.textContent = '';
            }

            function formatQuestionType(type) {
                const map = {
                    short_text: 'Short Text',
                    paragraph: 'Paragraph',
                    multiple_choice: 'Multiple Choice',
                    dropdown: 'Dropdown',
                    checkbox: 'Checkbox',
                    linear_scale: 'Linear Scale',
                    multiple_choice_grid: 'MC Grid',
                    checkbox_grid: 'Checkbox Grid',
                    date: 'Date',
                    time: 'Time',
                    date_time: 'Date Time',
                    unknown: 'Unknown',
                };

                return map[type] || type.replace(/_/g, ' ');
            }

            function renderQuestionTypeSummary() {
                questionTypeSummary.innerHTML = '';

                if (!Array.isArray(detectedQuestionItems) || detectedQuestionItems.length === 0) {
                    questionTypeSummary.innerHTML = '<span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-1 text-[11px] text-gray-600">Belum ada data</span>';
                    return;
                }

                const typeMap = {};
                detectedQuestionItems.forEach((item) => {
                    const key = item.type || 'unknown';
                    typeMap[key] = (typeMap[key] || 0) + 1;
                });

                Object.keys(typeMap).sort().forEach((key) => {
                    const badge = document.createElement('span');
                    badge.className = 'inline-flex items-center rounded-full bg-blue-50 px-2 py-1 text-[11px] font-medium text-blue-700 border border-blue-100';
                    badge.textContent = `${formatQuestionType(key)}: ${typeMap[key]}`;
                    questionTypeSummary.appendChild(badge);
                });
            }

            function renderDetectedQuestions() {
                detectedQuestions.innerHTML = '';

                const filteredQuestions = detectedQuestionsData.filter((item) =>
                    item.toLowerCase().includes(detectedQuestionsKeyword.toLowerCase())
                );

                if (!Array.isArray(detectedQuestionsData) || detectedQuestionsData.length === 0) {
                    const li = document.createElement('li');
                    li.textContent = 'Belum terdeteksi';
                    detectedQuestions.appendChild(li);
                    detectedQuestionsMeta.textContent = '0 item';
                    detectedQuestionsToggle.classList.add('hidden');
                    detectedQuestionsSearch.classList.add('hidden');
                    return;
                }

                detectedQuestionsSearch.classList.remove('hidden');

                if (filteredQuestions.length === 0) {
                    const li = document.createElement('li');
                    li.textContent = 'Tidak ada pertanyaan yang cocok';
                    detectedQuestions.appendChild(li);
                    detectedQuestionsMeta.textContent = `0 dari ${detectedQuestionsData.length} item`;
                    detectedQuestionsToggle.classList.add('hidden');
                    return;
                }

                const total = filteredQuestions.length;
                const visibleItems = detectedQuestionsExpanded
                    ? filteredQuestions
                    : filteredQuestions.slice(0, detectedPreviewLimit);

                const escapedKeyword = detectedQuestionsKeyword
                    .replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
                const keywordRegex = escapedKeyword ? new RegExp(`(${escapedKeyword})`, 'ig') : null;

                visibleItems.forEach((item) => {
                    const li = document.createElement('li');
                    if (keywordRegex) {
                        li.innerHTML = item.replace(keywordRegex, '<mark class="bg-yellow-200 px-0.5 rounded">$1</mark>');
                    } else {
                        li.textContent = item;
                    }
                    detectedQuestions.appendChild(li);
                });

                detectedQuestionsMeta.textContent = detectedQuestionsKeyword
                    ? `${total} dari ${detectedQuestionsData.length} item`
                    : `${total} item`;

                if (total > detectedPreviewLimit) {
                    detectedQuestionsToggle.classList.remove('hidden');
                    detectedQuestionsToggle.textContent = detectedQuestionsExpanded
                        ? 'Sembunyikan'
                        : `Lihat semua (${total})`;
                } else {
                    detectedQuestionsToggle.classList.add('hidden');
                }
            }

            detectedQuestionsToggle.addEventListener('click', function () {
                detectedQuestionsExpanded = !detectedQuestionsExpanded;
                renderDetectedQuestions();
            });

            detectedQuestionsSearch.addEventListener('input', function (event) {
                detectedQuestionsKeyword = event.target.value || '';
                detectedQuestionsExpanded = false;
                renderDetectedQuestions();
            });

            function canAnalyze() {
                const title = titleInput.value.trim();
                const link = linkInput.value.trim();
                const question = Number(questionInput.value || 0);

                return title !== '' && link !== '' && question > 0;
            }

            async function runAnalysis(showValidationMessage = false) {
                const title = titleInput.value.trim();
                const question = questionInput.value;
                const link = linkInput.value.trim();
                const token = tokenInput.value;

                if (!title || !question || !link) {
                    if (showValidationMessage) {
                        resetView();
                        err.classList.remove('hidden');
                        err.textContent = 'Isi judul, jumlah pertanyaan, dan link form terlebih dahulu.';
                    }
                    return;
                }

                if (inFlightController) {
                    inFlightController.abort();
                }

                inFlightController = new AbortController();
                loading.classList.remove('hidden');
                button.disabled = true;

                try {
                    const response = await fetch("{{ route('form-analyzer.preview') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token,
                        },
                        body: JSON.stringify({
                            title: title,
                            question_count: Number(question),
                            form_link: link,
                        }),
                        signal: inFlightController.signal,
                    });

                    const data = await response.json();
                    resetView();

                    if (!response.ok || !data.ok) {
                        err.classList.remove('hidden');
                        err.textContent = data.message || 'Gagal menganalisa link form.';
                        return;
                    }

                    detectedTitle.textContent = data.title.detected || '-';
                    titlePercent.textContent = `${data.title.similarity_percent}%`;
                    titleStatus.textContent = data.title.is_match ? 'Cocok' : 'Tidak cocok';
                    titleStatus.className = `text-xs mt-1 ${data.title.is_match ? 'text-emerald-600' : 'text-red-600'}`;

                    questionCount.textContent = `${data.question_count.input} vs ${data.question_count.detected ?? '-'}`;
                    questionStatus.textContent = data.question_count.is_match ? 'Cocok' : 'Tidak cocok';
                    questionStatus.className = `text-xs mt-1 ${data.question_count.is_match ? 'text-emerald-600' : 'text-red-600'}`;

                    questionReview.textContent = data.question_review || '-';
                    questionReview.className = `text-sm font-medium mt-1 ${data.question_count.is_match ? 'text-emerald-700' : 'text-amber-700'}`;

                    detectedQuestionsData = Array.isArray(data.detected_questions) ? data.detected_questions : [];
                    detectedQuestionItems = Array.isArray(data.detected_question_items) ? data.detected_question_items : [];
                    detectedQuestionsExpanded = false;
                    detectedQuestionsKeyword = '';
                    detectedQuestionsSearch.value = '';
                    renderDetectedQuestions();
                    renderQuestionTypeSummary();

                    const debug = data.debug || {};
                    debugEntryCount.textContent = String(debug.entry_ids_count ?? 0);
                    debugLoadData.textContent = debug.has_load_data ? 'Ya' : 'Tidak';
                    debugQuestionTitleCount.textContent = String(debug.question_titles_count ?? 0);
                    debugEntryIds.textContent = Array.isArray(debug.entry_ids) && debug.entry_ids.length > 0
                        ? debug.entry_ids.join(', ')
                        : '-';

                    badge.textContent = data.accepted ? 'DITERIMA' : 'DITOLAK';
                    badge.className = `inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-semibold ${data.accepted ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700'}`;
                } catch (error) {
                    if (error.name !== 'AbortError') {
                        resetView();
                        err.classList.remove('hidden');
                        err.textContent = 'Terjadi kesalahan saat menghubungi analyzer.';
                    }
                } finally {
                    loading.classList.add('hidden');
                    button.disabled = false;
                    inFlightController = null;
                }
            }

            function scheduleAutoAnalyze() {
                if (autoAnalyzeTimer) {
                    clearTimeout(autoAnalyzeTimer);
                }

                autoAnalyzeTimer = setTimeout(() => {
                    if (canAnalyze()) {
                        runAnalysis(false);
                    }
                }, 800);
            }

            button.addEventListener('click', function () {
                runAnalysis(true);
            });

            titleInput.addEventListener('input', scheduleAutoAnalyze);
            questionInput.addEventListener('input', scheduleAutoAnalyze);
            linkInput.addEventListener('input', scheduleAutoAnalyze);

            if (canAnalyze()) {
                scheduleAutoAnalyze();
            }
        });
    </script>
@endsection
