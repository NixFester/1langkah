<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('app.1langkah_platform_desc'))</title>
    <meta name="description" content="{{ __('app.1langkah_meta_desc') }}">
    <link rel="dns-prefetch" href="https://images.unsplash.com">
    <link rel="dns-prefetch" href="https://i.pravatar.cc">
    <link rel="dns-prefetch" href="https://ui-avatars.com">
    <meta name="view-transition" content="same-origin">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <meta name="view-transition" content="same-origin">
    <link rel="icon" href="data:,">
    
    <!-- Preload LCP Image to fix Lighthouse LCP Request Discovery -->
    <link rel="preload" as="image" href="{{ asset('assets/icons/main_dashboard_image.svg') }}" fetchpriority="high">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"></noscript>
    @vite(['resources/css/app.css'])
    <script src="https://instant.page/5.2.0" type="module" integrity="sha384-jnZyxPjiipYXnSU0ygqeac2q7CVYMbh84q0uHVRRxK3jCUbO6202Gf6rZfvFkuRx"></script>
    @stack('styles')
</head>
<body class="bg-[#0a0a0a] text-[#fdfdfc] font-sans antialiased selection:bg-[#dc2626] selection:text-white">
    @yield('body')

    @stack('scripts')
</body>
</html>
