<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - SurveyCenter</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .transition-smooth {
            transition: all 0.3s ease-in-out;
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen bg-gray-100">

    <div class="bg-white rounded-xl shadow-2xl overflow-hidden w-[900px] max-w-full flex">
        <!-- Left Side: Sign In -->
        <div class="w-1/2 p-10 flex flex-col justify-center">
            <h2 class="text-3xl font-extrabold text-gray-900 text-center">Sign in</h2>

            <p class="text-gray-500 text-sm text-center mt-4">Dikhususkan Untuk Admin</p>

            <!-- Form -->
            @if ($errors->any())
                <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 mt-4">
                    <ul class="list-disc ml-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST" class="mt-4 space-y-4">
                @csrf
                <input type="email" name="email" placeholder="Email"
                    class="block w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-orange-500 focus:border-orange-500"
                    required>
                <input type="password" name="password" placeholder="Password"
                    class="block w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-orange-500 focus:border-orange-500"
                    required>

                <p class="text-right text-sm text-gray-500 hover:text-orange-600 cursor-pointer">Lupa kata sandi anda?
                </p>

                <button type="submit"
                    class="w-full bg-orange-500 text-white py-3 rounded-full font-semibold shadow hover:bg-orange-600 transition-smooth">
                    SIGN IN
                </button>
            </form>
        </div>

        <!-- Right Side: Informasi Tambahan -->
        <div
            class="w-1/2 bg-gradient-to-br from-orange-400 to-orange-600 flex flex-col items-center justify-center p-10 text-white text-center">
            <h2 class="text-3xl font-extrabold mb-4 leading-snug">
                Selamat Datang di <span class="block">SurveyCenter.co.id</span>
            </h2>
            <p class="text-base leading-relaxed max-w-xs mb-6">
                Bergabunglah dengan kami untuk mengelola survei, menganalisis data,
                dan meningkatkan keputusan bisnis Anda secara mudah dan efisien.
            </p>
        </div>


    </div>

</body>

</html>
