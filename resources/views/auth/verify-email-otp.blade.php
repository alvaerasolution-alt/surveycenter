@extends('layouts.auth')

@push('styles')
<style>
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    .otp-input {
        width: 48px; height: 56px;
        text-align: center; font-size: 22px; font-weight: 800;
        border: 2px solid #e2e8f0; border-radius: 8px;
        background: #f8fafc;
        transition: all 0.2s;
        outline: none;
    }
    .otp-input:focus {
        border-color: #ea580c;
        background: white;
        box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.1);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-[#f2f6f9] text-[#071D49] relative">
    
    {{-- Header --}}
    <header class="absolute top-0 left-0 right-0 w-full flex justify-between items-center px-8 lg:px-[8%] py-8 z-50">
        <a href="{{ url('/') }}" class="flex items-center gap-2.5 text-[22px] font-bold tracking-tight text-[#071D49]">
            <div class="flex items-center gap-[3px]">
                <div class="w-[3px] h-[15px] rounded-full bg-[#f97316]"></div>
                <div class="w-[4px] h-[22px] rounded-full bg-[#071D49]"></div>
                <div class="w-[3px] h-[15px] rounded-full bg-[#f97316]"></div>
            </div>
            <span>SurveyCenter</span>
        </a>
        <div class="flex items-center gap-4 text-[13.5px] font-medium text-slate-500">
            <span class="hidden sm:inline text-[#64748b]">Sudah verifikasi?</span>
            <a href="{{ route('login') }}" class="px-5 py-2.5 rounded border border-slate-200 bg-white text-[#ea580c] font-bold shadow-sm hover:bg-slate-50 transition-colors">Log In</a>
        </div>
    </header>

    {{-- Main Container --}}
    <div class="flex w-full min-h-screen relative z-10">
        
        {{-- Left half --}}
        <div class="hidden lg:flex w-[45%] h-full pt-[130px] px-[8%] flex-col">
            <h2 class="text-[20px] font-black mb-8 text-[#071D49] tracking-tight">What's new</h2>
            <div class="flex flex-col gap-6 overflow-y-auto pb-20 scrollbar-hide pr-4">
                @forelse($articles as $article)
                <a href="{{ route('blog.show', $article->slug) }}" class="flex gap-4 items-start group">
                    <img src="{{ $article->image ? asset('storage/' . $article->image) : 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=150&q=80' }}" 
                         class="w-[90px] h-[60px] rounded-md object-cover flex-shrink-0 transition-transform duration-300 group-hover:scale-105 border border-slate-200"
                         alt="{{ $article->title }}">
                    <div class="flex flex-col w-full px-1">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-[#0ea5e9] text-[11px] font-bold">{{ $article->category ?? 'SurveyCenter Update' }}</span>
                            <span class="text-slate-400 text-[10px] font-semibold tracking-wide uppercase">{{ $article->created_at->format('d M y') }}</span>
                        </div>
                        <h3 class="text-[13px] font-bold text-[#071D49] leading-snug group-hover:text-[#0ea5e9] transition-colors">{{ Str::limit($article->title, 70) }}</h3>
                    </div>
                </a>
                @empty
                <p class="text-slate-400 text-sm">Belum ada artikel.</p>
                @endforelse
            </div>
        </div>

    {{-- Curved Background --}}
    <div class="absolute right-0 top-0 bottom-0 w-[58%] bg-white z-10 pointer-events-none hidden lg:block" 
         style="border-top-left-radius: 25% 50%; border-bottom-left-radius: 25% 50%; box-shadow: -15px 0 45px rgba(0,0,0,0.01);"></div>

    <div class="w-full lg:w-[55%] lg:ml-auto min-h-screen lg:min-h-[calc(100vh-64px)] flex flex-col justify-center items-center relative z-20 px-8 py-10 bg-white lg:bg-transparent">
        <div class="w-full max-w-[390px] my-auto">
            <h1 class="text-[34px] font-extrabold text-[#071D49] tracking-tight mb-[18px]">Verifikasi Email</h1>
            
            <p class="text-[13px] text-slate-500 leading-relaxed max-w-[340px] mb-2 mt-1">
                Masukkan 6 digit kode OTP yang telah dikirim ke email
            </p>
            <p class="text-[14px] font-bold text-[#071D49] mb-8">{{ $email }}</p>

            @if (session('status'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded text-[12.5px] mb-6 shadow-sm font-medium">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-[12.5px] mb-6 shadow-sm">
                    <ul class="list-disc ml-4 text-left font-medium">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('verification.verify') }}" method="POST" id="otpForm">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
                <input type="hidden" name="otp" id="otpHidden">

                <div class="flex gap-3 justify-center mb-8">
                    @for ($i = 0; $i < 6; $i++)
                        <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]"
                               class="otp-input" data-index="{{ $i }}"
                               autocomplete="off">
                    @endfor
                </div>

                <button type="submit" class="w-full py-3.5 bg-[#ea580c] hover:bg-[#c2410c] text-white rounded-md font-bold text-[14.5px] transition-colors shadow-sm mb-6 mt-2">
                    Verifikasi Email
                </button>
            </form>
                
            <div class="text-center mt-2 space-y-3">
                <form action="{{ route('verification.resend') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
                    <button type="submit" class="text-[13px] font-semibold text-[#ea580c] hover:text-[#c2410c] transition-colors">
                        Kirim Ulang Kode OTP
                    </button>
                </form>
                <div>
                    <a href="{{ route('login') }}" class="inline-block text-[13px] font-semibold text-slate-500 hover:text-[#ea580c] transition-colors">
                        ← Kembali ke halaman login
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.otp-input');
    const hiddenInput = document.getElementById('otpHidden');
    const form = document.getElementById('otpForm');

    function updateHidden() {
        let otp = '';
        inputs.forEach(input => otp += input.value);
        hiddenInput.value = otp;
    }

    inputs.forEach((input, index) => {
        input.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
            updateHidden();
            if (hiddenInput.value.length === 6) {
                form.submit();
            }
        });

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && !this.value && index > 0) {
                inputs[index - 1].focus();
            }
        });

        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const paste = (e.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '');
            for (let i = 0; i < Math.min(paste.length, 6); i++) {
                inputs[i].value = paste[i];
            }
            updateHidden();
            if (paste.length >= 6) {
                inputs[5].focus();
                form.submit();
            } else {
                inputs[Math.min(paste.length, 5)].focus();
            }
        });
    });

    inputs[0].focus();
});
</script>
@endsection
