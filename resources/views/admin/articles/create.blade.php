@extends('layouts.admin')

@section('content')
  <div class="max-w-3xl mx-auto py-10 px-6 md:px-2">
    <h2 class="text-3xl font-bold mb-6">{{ isset($article) ? 'Edit' : 'Tambah' }} Article</h2>

    <form action="{{ isset($article) ? route('admin.articles.update', $article->id) : route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      @if (isset($article))
        @method('PUT')
      @endif

      {{-- Judul --}}
      <div class="mb-4">
        <label class="block text-sm font-semibold mb-1">Judul</label>
        <input type="text" name="title" value="{{ $article->title ?? old('title') }}" class="w-full border px-3 py-2 rounded">
        @error('title')
          <p class="text-red-600 text-xs">{{ $message }}</p>
        @enderror
      </div>

      {{-- Excerpt --}}
      <div class="mb-4">
        <label class="block text-sm font-semibold mb-1">Excerpt</label>
        <textarea name="excerpt" class="w-full border px-3 py-2 rounded">{{ $article->excerpt ?? old('excerpt') }}</textarea>
        @error('excerpt')
          <p class="text-red-600 text-xs">{{ $message }}</p>
        @enderror
      </div>

      {{-- Konten (CKEditor full toolbar seperti WordPress/Microsoft Word) --}}
      <div class="mb-4">
        <label class="block text-sm font-semibold mb-1">Konten</label>
        <textarea id="content" name="content" class="w-full border px-3 py-2 rounded h-60">{{ $article->content ?? old('content') }}</textarea>
        @error('content')
          <p class="text-red-600 text-xs">{{ $message }}</p>
        @enderror
      </div>

      {{-- Kategori --}}
      <div class="mb-4">
        <label class="block text-sm font-semibold mb-1">Kategori</label>
        <input type="text" name="category" value="{{ $article->category ?? old('category') }}" class="w-full border px-3 py-2 rounded">
        @error('category')
          <p class="text-red-600 text-xs">{{ $message }}</p>
        @enderror
      </div>

      <div class="mb-4">
        <label class="inline-flex items-center gap-2 text-sm font-semibold mb-2">
          <input type="checkbox" name="is_published" value="1" @checked(old('is_published', isset($article) ? $article->is_published : false))>
          Publish artikel
        </label>
        <input type="datetime-local" name="published_at"
          value="{{ old('published_at', isset($article) && $article->published_at ? $article->published_at->format('Y-m-d\TH:i') : '') }}"
          class="w-full border px-3 py-2 rounded">
        <p class="text-xs text-gray-500 mt-1">Biarkan kosong untuk menggunakan waktu saat dipublish.</p>
        @error('published_at')
          <p class="text-red-600 text-xs">{{ $message }}</p>
        @enderror
      </div>

      {{-- Gambar --}}
      <div class="mb-4">
        <label class="block text-sm font-semibold mb-1">Gambar</label>
        <input type="file" name="image" class="w-full border px-3 py-2 rounded">
        @if (isset($article) && $article->image)
          <img src="{{ url($article->image) }}" alt="thumb" class="w-32 h-32 object-cover mt-2 rounded">
        @endif
        @error('image')
          <p class="text-red-600 text-xs">{{ $message }}</p>
        @enderror
      </div>

      {{-- Submit --}}
      <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
        {{ isset($article) ? 'Update' : 'Simpan' }}
      </button>
    </form>
  </div>
@endsection

@push('scripts')
  {{-- <script src="https://cdn.ckeditor.com/ckeditor5/41.2.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#content'), {
                toolbar: [
                    'heading', 'bold', 'italic', 'underline', 'strikethrough',
                    'link', 'bulletedList', 'numberedList', 'blockQuote',
                    'undo', 'redo', 'alignment', 'insertTable', 'mediaEmbed'
                ],
                heading: {
                    options: [{
                            model: 'paragraph',
                            title: 'Paragraph',
                            class: 'ck-heading_paragraph'
                        },
                        {
                            model: 'heading1',
                            view: 'h1',
                            title: 'Heading 1',
                            class: 'ck-heading_heading1'
                        },
                        {
                            model: 'heading2',
                            view: 'h2',
                            title: 'Heading 2',
                            class: 'ck-heading_heading2'
                        },
                        {
                            model: 'heading3',
                            view: 'h3',
                            title: 'Heading 3',
                            class: 'ck-heading_heading3'
                        }
                    ]
                },
                link: {
                    decorators: {
                        openInNewTab: {
                            mode: 'manual',
                            label: 'Buka di tab baru',
                            attributes: {
                                target: '_blank',
                                rel: 'noopener noreferrer'
                            }
                        }
                    }
                }
            })
            .catch(error => {
                console.error(error);
            });
    </script> --}}

  <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>

  <style>
    /* Perbaiki z-index untuk Summernote modal */
    .note-editor .note-toolbar {
      z-index: 800 !important;
    }

    .note-editor.note-frame {
      z-index: 1;
    }

    .note-modal {
      z-index: 9999 !important;
    }

    .note-modal-backdrop {
      z-index: 9998 !important;
    }

    .note-popover {
      z-index: 9997 !important;
    }

    /* Khusus untuk link dialog dan image dialog */
    .note-link-dialog,
    .note-image-dialog {
      z-index: 10000 !important;
    }

    /* Pastikan modal bisa diklik */
    .modal-backdrop {
      z-index: 9998;
    }

    .modal {
      z-index: 9999;
    }
	  
	.note-editor .note-editable h1 { font-size: 2rem; font-weight: 700; }
    .note-editor .note-editable h2 { font-size: 1.75rem; font-weight: 700; }
    .note-editor .note-editable h3 { font-size: 1.5rem; font-weight: 700; }
    .note-editor .note-editable h4 { font-size: 1.25rem; font-weight: 700; }
    .note-editor .note-editable h5 { font-size: 1.1rem; font-weight: 700; }
    .note-editor .note-editable h6 { font-size: 1rem; font-weight: 700; }

    .note-editor .note-editable blockquote {
       border-left: 4px solid #d1d5db;
       padding-left: 12px;
       color: #6b7280;
       font-style: italic;
     }
  </style>

  <script>
    $(document).ready(function() {
      $('#content').summernote({
        height: 300,
        dialogsInBody: true, // Penting untuk mengatasi masalah z-index
        dialogsFade: true,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['fontname', ['fontname']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'video']],
          ['view', ['codeview', 'help']]
        ],
        callbacks: {
          onInit: function() {
            console.log('Summernote initialized');
          }
        }
      });
    });
  </script>
@endpush
