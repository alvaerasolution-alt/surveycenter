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
    });
</script>
@endpush
