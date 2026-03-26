<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Midtrans - Log In</title>
    <!-- Tailwind CSS (Gunakan @vite('resources/css/app.css') jika sudah mengonfigurasi Tailwind di proyek Laravel Anda) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Kustom scrollbar untuk left panel */
        .scrollbar-thin::-webkit-scrollbar {
            width: 6px;
        }
        .scrollbar-thin::-webkit-scrollbar-track {
            background: transparent;
        }
        .scrollbar-thin::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 20px;
        }
    </style>
</head>
<body class="bg-[#f2f7fa] text-[#071D49] h-screen flex flex-col lg:flex-row overflow-hidden relative">

    <!-- Header (absolute in desktop, relative in mobile) -->
    <header class="absolute top-0 left-0 right-0 flex justify-between items-center p-6 lg:px-12 lg:py-8 z-10 pointer-events-none">
        <div class="flex items-center gap-2 text-[26px] font-medium tracking-tight pointer-events-auto">
            <div class="flex items-center gap-1">
                <div class="w-1 h-4 rounded-full bg-blue-500"></div>
                <div class="w-1 h-[26px] rounded-full bg-[#071D49]"></div>
                <div class="w-1 h-4 rounded-full bg-blue-500"></div>
            </div>
            <span>midtrans</span>
        </div>
        <div class="flex items-center gap-4 text-sm pointer-events-auto">
            <span class="text-[#6B7A99] hidden sm:inline">Belum ada akun?</span>
            <a href="#" class="border border-slate-200 bg-white px-6 py-2.5 rounded-md text-blue-600 font-semibold hover:bg-slate-50 transition-colors">Sign Up</a>
        </div>
    </header>

    <!-- Left Panel -->
    <div class="hidden lg:flex w-[46%] pt-[140px] pb-12 px-20 flex-col overflow-y-auto h-full scrollbar-thin">
        <h2 class="text-xl font-extrabold mb-7">What's new</h2>
        <div class="flex flex-col gap-6">
            <!-- News Items -->
            <a href="#" class="flex gap-5 items-start group">
                <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=150&q=80" alt="News Image 1" class="w-[100px] h-[65px] rounded-md object-cover flex-shrink-0 transition-transform duration-300 group-hover:scale-103">
                <div class="flex flex-col justify-start gap-1.5 w-full">
                    <div class="flex justify-between text-xs items-center w-full mb-0.5">
                        <span class="text-sky-600 font-semibold">Midtrans Update</span>
                        <span class="text-slate-400 font-medium">20 Nov 21</span>
                    </div>
                    <h3 class="text-[14.5px] font-bold leading-snug text-[#071D49] group-hover:text-sky-600 transition-colors">Website Toko Online untuk Bisnis, Apa Pentingnya?</h3>
                </div>
            </a>
            
            <a href="#" class="flex gap-5 items-start group">
                <img src="https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?auto=format&fit=crop&w=150&q=80" alt="News Image 2" class="w-[100px] h-[65px] rounded-md object-cover flex-shrink-0 transition-transform duration-300 group-hover:scale-103">
                <div class="flex flex-col justify-start gap-1.5 w-full">
                    <div class="flex justify-between text-xs items-center w-full mb-0.5">
                        <span class="text-sky-600 font-semibold">Midtrans Update</span>
                        <span class="text-slate-400 font-medium">25 Nov 21</span>
                    </div>
                    <h3 class="text-[14.5px] font-bold leading-snug text-[#071D49] group-hover:text-sky-600 transition-colors">Apa Itu Disbursement Dalam Transaksi Bisnis? Berikut ini Penjelasan...</h3>
                </div>
            </a>
            
            <a href="#" class="flex gap-5 items-start group">
                <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?auto=format&fit=crop&w=150&q=80" alt="News Image 3" class="w-[100px] h-[65px] rounded-md object-cover flex-shrink-0 transition-transform duration-300 group-hover:scale-103">
                <div class="flex flex-col justify-start gap-1.5 w-full">
                    <div class="flex justify-between text-xs items-center w-full mb-0.5">
                        <span class="text-sky-600 font-semibold">Midtrans Update</span>
                        <span class="text-slate-400 font-medium">26 Nov 21</span>
                    </div>
                    <h3 class="text-[14.5px] font-bold leading-snug text-[#071D49] group-hover:text-sky-600 transition-colors">Manajemen Keuangan Adalah Kunci Kesuksesan Bisnis, Ini 5 Alasannya</h3>
                </div>
            </a>
            
            <a href="#" class="flex gap-5 items-start group">
                <img src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?auto=format&fit=crop&w=150&q=80" alt="News Image 4" class="w-[100px] h-[65px] rounded-md object-cover flex-shrink-0 transition-transform duration-300 group-hover:scale-103">
                <div class="flex flex-col justify-start gap-1.5 w-full">
                    <div class="flex justify-between text-xs items-center w-full mb-0.5">
                        <span class="text-sky-600 font-semibold">Midtrans Update</span>
                        <span class="text-slate-400 font-medium">27 Nov 21</span>
                    </div>
                    <h3 class="text-[14.5px] font-bold leading-snug text-[#071D49] group-hover:text-sky-600 transition-colors">Brand Adalah Jiwa Sebuah Bisnis. Ini Cara Membangunnya</h3>
                </div>
            </a>
        </div>
    </div>

    <!-- Right Panel -->
    <div class="absolute inset-y-0 right-0 w-full lg:w-[60%] flex justify-end pointer-events-none">
        <!-- The actual white panel with massive border wrap -->
        <div class="w-full h-full bg-white lg:-ml-10 lg:rounded-l-[280px] shadow-[-15px_0_40px_rgba(0,0,0,0.02)] flex flex-col pt-[100px] lg:pt-[130px] items-center overflow-y-auto pointer-events-auto scrollbar-thin">
            <div class="w-full max-w-[460px] px-8 lg:px-10 pb-12">
                <h1 class="text-[34px] font-extrabold mb-8 tracking-tight text-[#071D49]">Log In</h1>
                
                <div class="flex bg-white rounded-md p-1 mb-6 shadow-[0_0_0_1px_#E2E8F0] w-fit">
                    <button class="px-6 py-2.5 rounded text-sm font-semibold bg-[#EBF8FF] text-[#0369a1] shadow-sm transition-all" id="tab-merchant">Merchant</button>
                    <button class="px-6 py-2.5 rounded text-sm font-semibold text-slate-500 hover:text-slate-700 transition-all bg-transparent" id="tab-partner">Partner</button>
                </div>
                
                <p class="text-[13.5px] text-slate-500 leading-relaxed mb-8">
                    Masuk ke dashboard Midtrans sebagai merchant, untuk melihat transaksi, download laporan, hingga mencairkan saldo Anda.
                </p>

                <form action="#" method="POST">
                    <div class="mb-6">
                        <label class="block text-[11px] font-extrabold text-[#071D49] mb-2.5 tracking-[0.8px] uppercase" for="email">Email Address</label>
                        <input type="email" id="email" name="email" class="w-full px-4 py-3.5 bg-slate-100 border border-transparent rounded-md text-[15px] focus:outline-none focus:border-blue-500 focus:bg-white focus:ring-[3px] focus:ring-blue-500/10 transition-all placeholder-slate-400 font-medium text-[#071D49]" placeholder="nuridwan1303@gmail.com" required>
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-[11px] font-extrabold text-[#071D49] mb-2.5 tracking-[0.8px] uppercase" for="password">Password</label>
                        <input type="password" id="password" name="password" class="w-full px-4 py-3.5 bg-slate-100 border border-transparent rounded-md text-[15px] focus:outline-none focus:border-blue-500 focus:bg-white focus:ring-[3px] focus:ring-blue-500/10 transition-all placeholder-slate-400 font-medium text-[#071D49]" placeholder="••••••••••••••" required>
                    </div>

                    <button type="submit" class="w-full py-3.5 bg-[#0b50bc] hover:bg-[#083c93] text-white border-none rounded-md text-[15px] font-semibold cursor-pointer transition-colors mt-2 mb-4">Log me in</button>
                    
                    <div class="text-[11px] text-slate-500 leading-relaxed mb-9">
                        Kami melakukan pembaruan <b class="text-[#071D49] font-bold">Pemberitahuan Privasi Midtrans</b> efektif 26 Maret 2025.<br><br>
                        Dengan melanjutkan, Anda memahami dan menyetujui penggunaan Kami atas informasi yang Anda sampaikan sesuai dengan ketentuan Pemberitahuan Privasi.
                    </div>

                    <div class="text-center flex flex-col gap-4 text-[13.5px] mb-10">
                        <span class="text-slate-500">Lupa kata sandi? <a href="#" class="text-blue-600 font-semibold hover:underline">Klik disini</a></span>
                        <a href="#" class="text-blue-600 font-semibold hover:underline">Kirim ulang link verifikasi</a>
                    </div>
                    
                    <div class="text-center text-[13px] text-slate-500 mb-10">
                        <a href="#" class="font-medium text-slate-500 hover:text-[#071D49]">EN</a> | <span class="font-bold text-[#071D49]">ID</span>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const tabMerchant = document.getElementById('tab-merchant');
        const tabPartner = document.getElementById('tab-partner');

        const activeClasses = 'px-6 py-2.5 rounded text-sm font-semibold bg-[#EBF8FF] text-[#0369a1] shadow-sm transition-all'.split(' ');
        const inactiveClasses = 'px-6 py-2.5 rounded text-sm font-semibold text-slate-500 hover:text-slate-700 transition-all bg-transparent'.split(' ');

        tabMerchant.addEventListener('click', () => {
            tabMerchant.className = activeClasses.join(' ');
            tabPartner.className = inactiveClasses.join(' ');
        });

        tabPartner.addEventListener('click', () => {
            tabPartner.className = activeClasses.join(' ');
            tabMerchant.className = inactiveClasses.join(' ');
        });
    </script>
</body>
</html>
