<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('app.1langkah_platform_desc'))</title>
    <meta name="description" content="{{ __('app.1langkah_meta_desc') }}">
    <link rel="preconnect" href="https://images.unsplash.com" crossorigin>
    <link rel="preconnect" href="https://i.pravatar.cc" crossorigin>
    <link rel="preconnect" href="https://ui-avatars.com" crossorigin>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" href="data:,">
    
    <!-- Preload LCP Image to fix Lighthouse LCP Request Discovery -->
    <link rel="preload" as="image" href="{{ asset('assets/icons/main_dashboard_image.svg') }}" fetchpriority="high">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-[#0a0a0a] text-[#fdfdfc] font-sans antialiased selection:bg-[#dc2626] selection:text-white">
    @yield('body')

    @stack('scripts')
</body>
</html>
