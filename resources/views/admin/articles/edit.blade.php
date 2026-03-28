@extends('layouts.admin')

@section('title', 'Edit Artikel')

@section('content')
  <div class="max-w-4xl mx-auto px-6 py-10">
    <div class="bg-white shadow-xl rounded-2xl p-8 border border-gray-100">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">✏️ Edit Artikel</h1>
        <a href="{{ route('admin.articles.index') }}" class="text-sm text-gray-600 hover:text-gray-900 flex items-center gap-1 transition">
          ← Kembali ke Daftar
        </a>
      </div>

      {{-- Notifikasi sukses --}}
      @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-5">
          ✅ {{ session('success') }}
        </div>
      @endif

      {{-- Error --}}
      @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-5">
          <ul class="list-disc list-inside space-y-1">
            @foreach ($errors->all() as $error)
              <li>⚠️ {{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      {{-- Form Edit --}}
      <form action="{{ route('admin.articles.update', $article->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Judul --}}
        <div>
          <label for="title" class="block font-semibold text-gray-700 mb-2">Judul Artikel <span class="text-red-500">*</span></label>
          <input type="text" name="title" id="title" value="{{ old('title', $article->title) }}" placeholder="Masukkan judul artikel..."
            class="w-full border-gray-300 rounded-lg px-4 py-2 shadow-sm focus:ring-2 focus:ring-blue-300 focus:border-blue-500" required>
        </div>

        {{-- Ringkasan --}}
        <div>
          <label for="excerpt" class="block font-semibold text-gray-700 mb-2">Ringkasan</label>
          <textarea name="excerpt" id="excerpt" rows="3" placeholder="Tuliskan ringkasan singkat isi artikel..."
            class="w-full border-gray-300 rounded-lg px-4 py-2 shadow-sm focus:ring-2 focus:ring-blue-300 focus:border-blue-500">{{ old('excerpt', $article->excerpt) }}</textarea>
        </div>

        {{-- Konten --}}
        <div>
          <label for="content" class="block font-semibold text-gray-700 mb-2">Konten <span class="text-red-500">*</span></label>
          <textarea name="content" id="content" rows="12" placeholder="Tulis isi lengkap artikel di sini..." class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-300 focus:border-blue-500"
            required>{{ old('content', $article->content) }}</textarea>
        </div>

        {{-- Kategori --}}
        <div>
          <label for="category" class="block font-semibold text-gray-700 mb-2">Kategori</label>
          <input type="text" name="category" id="category" value="{{ old('category', $article->category) }}" placeholder="Contoh: Teknologi, Bisnis, Lifestyle"
            class="w-full border-gray-300 rounded-lg px-4 py-2 shadow-sm focus:ring-2 focus:ring-blue-300 focus:border-blue-500">
        </div>

        {{-- Gambar --}}
        <div>
          <label for="image" class="block font-semibold text-gray-700 mb-2">Gambar Artikel</label>

          @if ($article->image)
            <div class="mb-4">
              <p class="text-sm text-gray-500 mb-2">Gambar saat ini:</p>
              <img src="{{ url('storage/' . $article->image) }}" alt="Current Image" class="w-52 h-32 object-cover rounded-lg border border-gray-200 shadow-sm">
            </div>
          @endif

          <input type="file" name="image" id="image" class="w-full border-gray-300 rounded-lg px-3 py-2 shadow-sm focus:ring-2 focus:ring-blue-300 focus:border-blue-500">
          <p class="text-sm text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengganti gambar.</p>
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex justify-end items-center gap-3 pt-6 border-t border-gray-200">
          <a href="{{ route('admin.articles.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2 rounded-lg font-medium transition">
            Batal
          </a>
          <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg shadow transition flex items-center gap-2">
            💾 Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
  </div>

  {{-- CKEditor --}}
  @push('scripts')
    {{-- <script src="https://cdn.ckeditor.com/4.25.1-lts/standard/ckeditor.js"></script>
    <script>
      CKEDITOR.replace('content', {
        height: 350,
        removeButtons: 'PasteFromWord',
        toolbar: [
          ['Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink'],
          ['Format', 'FontSize', '-', 'TextColor', 'BGColor'],
          ['Maximize', 'Source']
        ]
      });
    </script> --}}
    {{-- <script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
    <script>
      CKEDITOR.replace('content', {
        height: 350,
        removeButtons: 'PasteFromWord',
        toolbar: [
          ['Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink'],
          ['Format', 'FontSize', '-', 'TextColor', 'BGColor'],
          ['Maximize', 'Source']
        ]
      });
    </script> --}}

    {{-- <script src="https://cdn.ckeditor.com/ckeditor5/41.2.0/classic/ckeditor.js"></script>
    <script>
      ClassicEditor
        .create(document.querySelector('#content'), {
          toolbar: [
            'heading', 'bold', 'italic', 'underline', 'strikethrough',
            'link', 'bulletedList', 'numberedList', 'blockQuote',
            'undo', 'redo', 'alignment', 'insertTable', 'mediaEmbed'
          ],
          alignment: {
            options: ['left', 'center', 'right', 'justify']
          },
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

    <!-- include summernote css/js -->
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
          height: 350,
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
@endsection
