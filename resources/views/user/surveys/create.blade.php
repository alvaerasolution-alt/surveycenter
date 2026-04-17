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
    });
</script>
@endpush
