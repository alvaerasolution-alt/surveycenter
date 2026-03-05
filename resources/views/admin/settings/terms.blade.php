@extends('layouts.admin')

@section('title', 'Syarat & Ketentuan')
@section('page-title', 'Kelola Syarat & Ketentuan')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h2 class="text-xl font-semibold text-gray-900">Syarat & Ketentuan</h2>
        <p class="text-sm text-gray-500 mt-1">Kelola isi syarat dan ketentuan yang ditampilkan di halaman pricing.</p>
    </div>

    @if(session('success'))
        <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
            <i data-lucide="check-circle" class="w-4 h-4 flex-shrink-0"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Form --}}
    <form action="{{ route('admin.terms.update') }}" method="POST" class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Isi Syarat & Ketentuan</label>

            {{-- Quill toolbar --}}
            <div id="toolbar" class="border border-gray-300 rounded-t-lg bg-gray-50">
                <span class="ql-formats">
                    <select class="ql-header">
                        <option value="1">Heading 1</option>
                        <option value="2">Heading 2</option>
                        <option value="3">Heading 3</option>
                        <option selected>Normal</option>
                    </select>
                </span>
                <span class="ql-formats">
                    <button class="ql-bold"></button>
                    <button class="ql-italic"></button>
                    <button class="ql-underline"></button>
                    <button class="ql-strike"></button>
                </span>
                <span class="ql-formats">
                    <button class="ql-list" value="ordered"></button>
                    <button class="ql-list" value="bullet"></button>
                </span>
                <span class="ql-formats">
                    <button class="ql-link"></button>
                    <button class="ql-blockquote"></button>
                </span>
                <span class="ql-formats">
                    <select class="ql-color"></select>
                    <select class="ql-background"></select>
                </span>
                <span class="ql-formats">
                    <button class="ql-clean"></button>
                </span>
            </div>

            {{-- Quill editor container --}}
            <div id="quill-editor" class="border border-t-0 border-gray-300 rounded-b-lg min-h-[400px] text-gray-800 text-sm">
                {!! $terms !!}
            </div>

            {{-- Hidden input for form submission --}}
            <input type="hidden" name="terms_content" id="terms-content-input">
        </div>

        <div class="flex justify-end pt-2">
            <button type="submit"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition shadow-sm">
                <i data-lucide="save" class="w-4 h-4"></i>
                Simpan Perubahan
            </button>
        </div>
    </form>

    {{-- Preview --}}
    @if($terms)
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
            <i data-lucide="eye" class="w-4 h-4 text-orange-500"></i>
            Preview Tampilan
        </h3>
        <div class="prose prose-sm max-w-none text-gray-700 border border-dashed border-gray-200 rounded-lg p-4 bg-gray-50">
            {!! $terms !!}
        </div>
    </div>
    @endif

</div>
@endsection

@push('styles')
{{-- Quill CSS --}}
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
<style>
    .ql-editor { min-height: 400px; font-size: 14px; }
    .ql-toolbar.ql-snow { border: none; }
    .ql-container.ql-snow { border: none; }
    /* Prose styling for preview */
    .prose h1 { font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem; }
    .prose h2 { font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem; }
    .prose h3 { font-size: 1.1rem; font-weight: 600; margin-bottom: 0.5rem; }
    .prose p { margin-bottom: 0.75rem; }
    .prose ul { list-style: disc; padding-left: 1.5rem; margin-bottom: 0.75rem; }
    .prose ol { list-style: decimal; padding-left: 1.5rem; margin-bottom: 0.75rem; }
    .prose li { margin-bottom: 0.25rem; }
    .prose strong { font-weight: 700; }
    .prose em { font-style: italic; }
    .prose blockquote { border-left: 4px solid #e5e7eb; padding-left: 1rem; color: #6b7280; margin: 0.75rem 0; }
    .prose a { color: #f97316; text-decoration: underline; }
</style>
@endpush

@push('scripts')
{{-- Quill JS --}}
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof lucide !== 'undefined') lucide.createIcons();

    const quill = new Quill('#quill-editor', {
        theme: 'snow',
        modules: { toolbar: '#toolbar' },
        placeholder: 'Tulis syarat dan ketentuan di sini...'
    });

    // Copy existing HTML into Quill
    const existingContent = document.querySelector('#quill-editor').innerHTML;
    // Quill parses it on init via the pre-populated div

    // Before form submit, copy Quill HTML to hidden input
    document.querySelector('form').addEventListener('submit', function() {
        // use .root.innerHTML to get the full raw HTML from quill editor correctly
        document.getElementById('terms-content-input').value = quill.root.innerHTML;
    });
});
</script>
@endpush
