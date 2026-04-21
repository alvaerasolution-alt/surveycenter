@extends('layouts.admin')

@section('title', 'Pengaturan Website')
@section('page-title', 'Pengaturan')

@section('content')
    <div class="max-w-2xl">
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Pengaturan Website</h2>
                <p class="text-sm text-gray-500 mt-1">Atur konfigurasi umum website SurveyCenter</p>
            </div>

            <form method="POST" action="{{ route('settings.update') }}" class="p-6 space-y-6">
                @csrf

                <div class="space-y-6">
                    <h3 class="text-md font-semibold text-gray-800 border-b pb-2">Umum</h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">URL Video</label>
                        <input type="text" name="video_url" value="{{ $settings['video_url'] ?? '' }}"
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                            placeholder="https://youtube.com/watch?v=...">
                        <p class="text-xs text-gray-400 mt-1.5">Masukkan URL video YouTube untuk ditampilkan di halaman utama</p>
                    </div>

                    <h3 class="text-md font-semibold text-gray-800 border-b pb-2 mt-6">Footer Kontak</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Alamat</label>
                            <textarea name="footer_alamat" rows="3"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                                placeholder="Masukkan alamat perusahaan">{{ $settings['footer_alamat'] ?? '' }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">WhatsApp / Telepon</label>
                            <input type="text" name="footer_whatsapp" value="{{ $settings['footer_whatsapp'] ?? '' }}"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                                placeholder="Cth: +62 851-9888-7963">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                            <input type="email" name="footer_email" value="{{ $settings['footer_email'] ?? '' }}"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                                placeholder="Cth: info@surveycenter.co.id">
                        </div>
                    </div>

                    <h3 class="text-md font-semibold text-gray-800 border-b pb-2 mt-6">Media Sosial</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Facebook URL</label>
                            <input type="text" name="sosmed_facebook" value="{{ $settings['sosmed_facebook'] ?? '' }}"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                                placeholder="URL Profile Facebook">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Twitter / X URL</label>
                            <input type="text" name="sosmed_twitter" value="{{ $settings['sosmed_twitter'] ?? '' }}"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                                placeholder="URL Profile Twitter">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">LinkedIn URL</label>
                            <input type="text" name="sosmed_linkedin" value="{{ $settings['sosmed_linkedin'] ?? '' }}"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                                placeholder="URL Profile LinkedIn">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Instagram URL</label>
                            <input type="text" name="sosmed_instagram" value="{{ $settings['sosmed_instagram'] ?? '' }}"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                                placeholder="URL Profile Instagram">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">TikTok URL</label>
                            <input type="text" name="sosmed_tiktok" value="{{ $settings['sosmed_tiktok'] ?? '' }}"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                                placeholder="URL Profile TikTok">
                        </div>
                    </div>

                    {{-- ══════════════════════════════════════════ --}}
                    {{-- POPUP WHATSAPP CMS --}}
                    {{-- ══════════════════════════════════════════ --}}
                    <div class="mt-8 p-5 bg-green-50 border border-green-200 rounded-xl space-y-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-md font-semibold text-gray-800 flex items-center gap-2">
                                    <span class="inline-flex w-7 h-7 bg-green-500 rounded-lg items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                    </span>
                                    Popup WhatsApp
                                </h3>
                                <p class="text-xs text-gray-500 mt-1">Form popup muncul saat tombol WhatsApp floating diklik</p>
                            </div>
                            {{-- Toggle --}}
                            <label class="relative inline-flex items-center cursor-pointer" title="Aktifkan Popup WA">
                                <input type="checkbox" name="popup_wa_enabled" value="1"
                                       id="popupToggle"
                                       {{ ($settings['popup_wa_enabled'] ?? '1') === '1' ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 rounded-full peer
                                            peer-checked:bg-green-500
                                            after:content-[''] after:absolute after:top-[2px] after:left-[2px]
                                            after:bg-white after:rounded-full after:h-5 after:w-5
                                            after:transition-all peer-checked:after:translate-x-full
                                            transition-colors duration-200"></div>
                                <span class="ml-2 text-sm font-medium text-gray-700" id="popupToggleLabel">
                                    {{ ($settings['popup_wa_enabled'] ?? '1') === '1' ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </label>
                        </div>

                        <div id="popupFields" class="{{ ($settings['popup_wa_enabled'] ?? '1') !== '1' ? 'opacity-50 pointer-events-none' : '' }} space-y-4 transition-opacity duration-200">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Judul Popup</label>
                                    <input type="text" name="popup_wa_title"
                                           value="{{ $settings['popup_wa_title'] ?? 'Hubungi via WhatsApp' }}"
                                           class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                                           placeholder="Hubungi via WhatsApp">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Subjudul Popup</label>
                                    <input type="text" name="popup_wa_subtitle"
                                           value="{{ $settings['popup_wa_subtitle'] ?? 'Isi data berikut untuk melanjutkan' }}"
                                           class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                                           placeholder="Isi data berikut untuk melanjutkan">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Nomor Admin WhatsApp
                                    <span class="text-xs text-gray-400 font-normal">(untuk menerima pesan dari popup)</span>
                                </label>
                                <input type="text" name="popup_admin_number"
                                       value="{{ $settings['popup_admin_number'] ?? '' }}"
                                       class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                                       placeholder="Cth: +62 851-9888-7963">
                                <p class="text-xs text-gray-400 mt-1">Kosongkan untuk menggunakan nomor WhatsApp footer di atas.</p>
                            </div>

                            {{-- Preview mini --}}
                            <div class="mt-3 p-4 bg-white rounded-xl border border-green-200 shadow-sm">
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Preview Popup</p>
                                <div class="flex items-start gap-2">
                                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-800" id="previewTitle">{{ $settings['popup_wa_title'] ?? 'Hubungi via WhatsApp' }}</p>
                                        <p class="text-xs text-gray-500" id="previewSubtitle">{{ $settings['popup_wa_subtitle'] ?? 'Isi data berikut untuk melanjutkan' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-6 border-t mt-6">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition shadow-sm">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        // Toggle popup fields
        const toggle = document.getElementById('popupToggle');
        const fields = document.getElementById('popupFields');
        const label  = document.getElementById('popupToggleLabel');

        toggle?.addEventListener('change', function() {
            if (this.checked) {
                fields.classList.remove('opacity-50', 'pointer-events-none');
                label.textContent = 'Aktif';
            } else {
                fields.classList.add('opacity-50', 'pointer-events-none');
                label.textContent = 'Nonaktif';
            }
        });

        // Live preview
        document.querySelector('[name="popup_wa_title"]')?.addEventListener('input', function() {
            document.getElementById('previewTitle').textContent = this.value || 'Hubungi via WhatsApp';
        });
        document.querySelector('[name="popup_wa_subtitle"]')?.addEventListener('input', function() {
            document.getElementById('previewSubtitle').textContent = this.value || 'Isi data berikut untuk melanjutkan';
        });
    });
</script>
@endpush

