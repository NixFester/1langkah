<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '1Langkah — AI-Powered Learning Experience Platform')</title>
    <link rel="icon" href="data:,">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-[#0a0a0a] text-[#fdfdfc] font-sans antialiased selection:bg-[#dc2626] selection:text-white">
    @yield('body')

    @stack('scripts')
</body>
</html>
