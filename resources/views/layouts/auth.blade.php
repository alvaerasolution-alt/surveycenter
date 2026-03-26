<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $seoTitle ?? 'SurveyCenter' }}</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('assets/logosc.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/logosc.png') }}">
    <link rel="shortcut icon" href="{{ asset('assets/logosc.png') }}" type="image/png">

    {{-- SEO Meta --}}
    <meta name="description" content="{{ $seoDesc ?? 'SurveyCenter - Jasa Survey Pasar & Sebar Kuesioner Terpercaya' }}">

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    {{-- Inter Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>

    {{-- Stacked Styles --}}
    @stack('styles')
</head>

<body class="bg-white">
    @yield('content')
</body>

</html>
