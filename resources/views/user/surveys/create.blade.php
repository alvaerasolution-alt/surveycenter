@extends('layouts.user')

@section('title', 'Buat Survey Baru')
@section('page-title', 'Buat Survey Baru')
@section('page-description', 'Buat survey baru untuk mengumpulkan data dari responden')

@section('content')
<div class="max-w-2xl mx-auto">

    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route('user.surveys.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-orange-600 transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Kembali ke Daftar Survey
        </a>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-xl border border-gray-200/80 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">Informasi Survey</h2>
            <p class="text-sm text-gray-500 mt-1">Lengkapi form berikut untuk membuat survey baru</p>
        </div>

        <div class="px-6 pt-5">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-2">
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                    <p class="text-[11px] text-gray-500">Harga Dasar</p>
                    <p class="text-sm font-bold text-orange-600">Rp 1.000</p>
                    <p class="text-[11px] text-gray-500">per pertanyaan / responden</p>
                </div>
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                    <p class="text-[11px] text-gray-500">Diskon</p>
                    <p class="text-sm font-semibold text-gray-800">Mahasiswa 50% • Perusahaan 30%</p>
                    <p class="text-[11px] text-gray-500">Umum tanpa diskon</p>
                </div>
                <div class="rounded-lg border border-red-200 bg-red-50 p-3">
                    <p class="text-[11px] text-red-600">Minimum Order</p>
                    <p class="text-sm font-bold text-red-700">Rp 50.000 / survey</p>
                    <p class="text-[11px] text-red-600">Wajib saat checkout</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('user.surveys.store') }}" class="p-6 space-y-6">
            @csrf

            {{-- Title --}}
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    Judul Survey <span class="text-red-500">*</span>
                </label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition @error('title') border-red-500 @enderror"
                    placeholder="Contoh: Survei Kepuasan Pelanggan 2024">
                @error('title')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description --}}
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Deskripsi
                </label>
                <textarea name="description" id="description" rows="3"
                    class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition resize-none @error('description') border-red-500 @enderror"
                    placeholder="Deskripsi singkat tentang survey ini (opsional)">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Question & Respondent Count --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                {{-- Question Count --}}
                <div>
                    <label for="question_count" class="block text-sm font-medium text-gray-700 mb-2">
                        Jumlah Pertanyaan <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="question_count" id="question_count" value="{{ old('question_count', 10) }}" 
                        min="1" max="100" required
                        class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition @error('question_count') border-red-500 @enderror"
                        placeholder="Contoh: 10">
                    <p class="mt-1.5 text-xs text-gray-500">Minimal 1, maksimal 100 pertanyaan</p>
                    @error('question_count')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Respondent Count --}}
                <div>
                    <label for="respondent_count" class="block text-sm font-medium text-gray-700 mb-2">
                        Jumlah Responden <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="respondent_count" id="respondent_count" value="{{ old('respondent_count', 100) }}" 
                        min="1" max="10000" required
                        class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition @error('respondent_count') border-red-500 @enderror"
                        placeholder="Contoh: 100">
                    <p class="mt-1.5 text-xs text-gray-500">Minimal 1, maksimal 10,000 responden</p>
                    @error('respondent_count')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Survey Link --}}
            <div>
                <label for="form_link" class="block text-sm font-medium text-gray-700 mb-2">
                    Link Survey <span class="text-red-500">*</span>
                </label>
                <input type="url" name="form_link" id="form_link" value="{{ old('form_link') }}" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition @error('form_link') border-red-500 @enderror"
                    placeholder="https://docs.google.com/forms/...">
                <p class="mt-1.5 text-xs text-gray-500">
                    Link wajib diisi. Sistem memvalidasi URL form dan memastikan judul form sama dengan judul survey.
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
                @error('form_link')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Cost Estimation --}}
            <div class="bg-gradient-to-br from-orange-50 to-amber-50 rounded-xl p-5 border border-orange-100">
                <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i data-lucide="calculator" class="w-4 h-4 text-orange-600"></i>
                    Estimasi Biaya
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Biaya pertanyaan</span>
                        <span class="text-gray-900" id="question-cost">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Biaya responden</span>
                        <span class="text-gray-900" id="respondent-cost">Rp 0</span>
                    </div>
                    <div class="border-t border-orange-200 pt-3 flex justify-between">
                        <span class="font-semibold text-gray-900">Total</span>
                        <span class="font-bold text-orange-600 text-lg" id="total-cost">Rp 0</span>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-4">
                    * Biaya: Rp 1.000/pertanyaan + Rp 1.000/responden
                </p>
            </div>

            {{-- Submit Button --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('user.surveys.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-orange-600 rounded-lg hover:bg-orange-700 transition shadow-sm">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Buat Survey
                </button>
            </div>
        </form>
    </div>

    {{-- Info Card --}}
    <div class="mt-6 bg-blue-50 rounded-xl p-5 border border-blue-100">
        <div class="flex gap-3">
            <div class="flex-shrink-0">
                <i data-lucide="info" class="w-5 h-5 text-blue-600"></i>
            </div>
            <div>
                <h4 class="text-sm font-medium text-blue-900">Langkah Selanjutnya</h4>
                <p class="text-sm text-blue-700 mt-1">
                    Setelah membuat survey, Anda akan diarahkan ke halaman pembayaran. Survey akan mulai diproses setelah pembayaran dikonfirmasi.
                </p>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        const questionInput = document.getElementById('question_count');
        const respondentInput = document.getElementById('respondent_count');
        const questionCostEl = document.getElementById('question-cost');
        const respondentCostEl = document.getElementById('respondent-cost');
        const totalCostEl = document.getElementById('total-cost');

        const COST_PER_QUESTION = 1000;
        const COST_PER_RESPONDENT = 1000;

        function formatCurrency(value) {
            return 'Rp ' + value.toLocaleString('id-ID');
        }

        function calculateCost() {
            const questions = parseInt(questionInput.value) || 0;
            const respondents = parseInt(respondentInput.value) || 0;

            const questionCost = questions * COST_PER_QUESTION;
            const respondentCost = respondents * COST_PER_RESPONDENT;
            const total = questionCost + respondentCost;

            questionCostEl.textContent = formatCurrency(questionCost);
            respondentCostEl.textContent = formatCurrency(respondentCost);
            totalCostEl.textContent = formatCurrency(total);
        }

        questionInput.addEventListener('input', calculateCost);
        respondentInput.addEventListener('input', calculateCost);

        // Initial calculation
        calculateCost();

        const analyzeButton = document.getElementById('analyzeFormButton');
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
        const form = analyzeButton ? analyzeButton.closest('form') : null;
        const titleInput = form ? form.querySelector('input[name="title"]') : null;
        const questionInputAnalyze = form ? form.querySelector('input[name="question_count"]') : null;
        const linkInput = form ? form.querySelector('input[name="form_link"]') : null;
        const tokenInput = form ? form.querySelector('input[name="_token"]') : null;
        const detectedPreviewLimit = 8;
        let detectedQuestionsData = [];
        let detectedQuestionsExpanded = false;
        let detectedQuestionsKeyword = '';
        let detectedQuestionItems = [];
        let autoAnalyzeTimer = null;
        let inFlightController = null;

        function resetAnalyzerView() {
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
            if (!titleInput || !questionInputAnalyze || !linkInput) {
                return false;
            }

            const title = titleInput.value.trim();
            const link = linkInput.value.trim();
            const question = Number(questionInputAnalyze.value || 0);

            return title !== '' && link !== '' && question > 0;
        }

        async function runAnalysis(showValidationMessage = false) {
            if (!form || !tokenInput || !titleInput || !questionInputAnalyze || !linkInput) {
                return;
            }

            const token = tokenInput.value;
            const title = titleInput.value.trim();
            const question = questionInputAnalyze.value;
            const link = linkInput.value.trim();

            if (!title || !question || !link) {
                if (showValidationMessage) {
                    resetAnalyzerView();
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
            analyzeButton.disabled = true;

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
                resetAnalyzerView();

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
                    resetAnalyzerView();
                    err.classList.remove('hidden');
                    err.textContent = 'Terjadi kesalahan saat menghubungi analyzer.';
                }
            } finally {
                loading.classList.add('hidden');
                analyzeButton.disabled = false;
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

        if (analyzeButton) {
            analyzeButton.addEventListener('click', function () {
                runAnalysis(true);
            });

            titleInput?.addEventListener('input', scheduleAutoAnalyze);
            questionInputAnalyze?.addEventListener('input', scheduleAutoAnalyze);
            linkInput?.addEventListener('input', scheduleAutoAnalyze);

            if (canAnalyze()) {
                scheduleAutoAnalyze();
            }
        }
    });
</script>
@endpush
