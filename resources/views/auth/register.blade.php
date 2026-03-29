@extends('layouts.auth')
@section('seo_slug', 'register')

@push('styles')
<style>
    body { font-family: 'Inter', sans-serif; }
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-[#f2f6f9] flex flex-col items-center justify-center py-16 px-4 sm:px-6 relative z-10">
    
    <div class="w-full max-w-[480px]">
        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-sm border border-slate-100 flex flex-col px-10 py-12 relative z-20">
            
            <h1 class="text-[32px] font-extrabold text-[#071D49] tracking-tight text-center mb-[20px]">Registrasi akun</h1>
            
            <!-- Banner Notification -->
            <div class="bg-[#f0f7ff] border-l-4 border-[#1e40af] text-[#0f4492] text-[12px] px-5 py-4 rounded-sm mb-8 leading-relaxed font-medium">
                E-mail & nomor telepon dibutuhkan untuk menggunakan dashboard SurveyCenter, manajemen survei klien, pencatatan layanan, dll.
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-[12.5px] mb-6 shadow-sm">
                    <ul class="list-disc ml-4 text-left font-medium">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.post') }}" method="POST">
                @csrf
                
                <div class="mb-5">
                    <label class="block text-[10.5px] font-extrabold text-[#071D49] tracking-widest uppercase mb-2" for="name">NAMA LENGKAP</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" 
                           class="w-full px-4 py-3 bg-white border border-[#e2e8f0] rounded text-[14px] text-[#071D49] font-medium focus:ring-1 focus:ring-[#ea580c] focus:border-[#ea580c] transition-all outline-none shadow-sm" 
                           placeholder="Masukkan nama lengkap Anda" required>
                </div>

                <div class="mb-5">
                    <label class="block text-[10.5px] font-extrabold text-[#071D49] tracking-widest uppercase mb-2" for="email">EMAIL ADDRESS</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" 
                           class="w-full px-4 py-3 bg-white border border-[#e2e8f0] rounded text-[14px] text-[#071D49] font-medium focus:ring-1 focus:ring-[#ea580c] focus:border-[#ea580c] transition-all outline-none shadow-sm" 
                           placeholder="nama@email.com" required>
                    <p class="text-[10.5px] text-slate-400 mt-2 font-medium">Kami akan mengirimkan email verifikasi lewat email ini.</p>
                </div>

                <div class="mb-5">
                    <label class="block text-[10.5px] font-extrabold text-[#071D49] tracking-widest uppercase mb-2" for="phone">NOMOR HP (WHATSAPP)</label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" 
                           class="w-full px-4 py-3 bg-white border border-[#e2e8f0] rounded text-[14px] text-[#071D49] font-medium focus:ring-1 focus:ring-[#ea580c] focus:border-[#ea580c] transition-all outline-none shadow-sm" 
                           placeholder="08xxxxxxxxxx" required>
                    <p class="text-[10.5px] text-slate-400 mt-2 font-medium">Digunakan untuk verifikasi OTP dan notifikasi via WhatsApp.</p>
                </div>
                
                <div class="mb-5">
                    <label class="block text-[10.5px] font-extrabold text-[#071D49] tracking-widest uppercase mb-2" for="password">PASSWORD</label>
                    <input type="password" id="password" name="password" 
                           class="w-full px-4 py-3 bg-[#f8fafc] border border-[#e2e8f0] rounded text-[14px] text-[#071D49] font-medium focus:ring-1 focus:ring-[#ea580c] focus:border-[#ea580c] focus:bg-white transition-all outline-none shadow-sm" 
                           placeholder="••••••••••••••" required>
                    <p class="text-[10.5px] text-[#64748b] mt-2 leading-[1.6] max-w-[90%]">Min. 8 karakter. Gunakan kombinasi angka, karakter spesial, huruf kapital, & huruf kecil.</p>
                </div>

                <div class="mb-6">
                    <label class="block text-[10.5px] font-extrabold text-[#071D49] tracking-widest uppercase mb-2" for="password_confirmation">KONFIRMASI PASSWORD</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" 
                           class="w-full px-4 py-3 bg-white border border-[#e2e8f0] rounded text-[14px] text-[#071D49] font-medium focus:ring-1 focus:ring-[#ea580c] focus:border-[#ea580c] transition-all outline-none shadow-sm" 
                           placeholder="Masukkan ulang password Anda" required>
                    <p class="text-[10.5px] text-[#64748b] mt-2 leading-[1.6] max-w-[90%]">Min. 8 karakter. Gunakan kombinasi angka, karakter spesial, huruf kapital, & huruf kecil.</p>
                </div>

                <!-- Terms and Privacy Checkbox -->
                <div class="flex items-start gap-3 mt-8 mb-6">
                    <input type="checkbox" id="terms" class="mt-[3px] flex-shrink-0 w-[14px] h-[14px] border-slate-300 rounded text-[#054fda] focus:ring-[#054fda] cursor-pointer" required>
                    <label for="terms" class="text-[11.5px] text-slate-500 leading-[1.6] font-medium">
                        Dengan melanjutkan, saya memahami dan menyetujui atas penggunaan informasi yang saya sampaikan sesuai ketentuan <a href="#" class="text-[#054fda] hover:text-[#0b42a9] font-bold underline transition-colors">Kebijakan Privasi</a>.
                    </label>
                </div>

                <button type="submit" class="w-full py-3.5 bg-[#0b42a9] hover:bg-[#072c72] text-white rounded font-bold text-[14.5px] transition-colors shadow-md mb-6">
                    Registrasi
                </button>
                
                <div class="text-center mb-8">
                    <span class="text-[13px] text-slate-500 font-medium">
                        Sudah punya akun? <a href="{{ route('login') }}" class="text-[#0b42a9] font-extrabold hover:underline">Masuk</a>
                    </span>
                </div>

                <div class="text-center text-[11px] text-slate-400 font-medium pb-2">
                    <span class="hover:text-[#071D49] cursor-pointer transition-colors">EN</span> | <span class="font-extrabold text-[#071D49]">ID</span>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
