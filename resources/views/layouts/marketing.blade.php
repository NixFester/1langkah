<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="dns-prefetch" href="https://images.unsplash.com">
    <link rel="dns-prefetch" href="https://i.pravatar.cc">
    <link rel="dns-prefetch" href="https://ui-avatars.com">
    <title>@yield('title', __('app.marketing_1langkah'))</title>
    <meta name="description" content="{{ __("app.1langkah_meta_desc") ?? "1Langkah Platform" }}">
    <meta name="view-transition" content="same-origin">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://instant.page/5.2.0" type="module" integrity="sha384-jnZyxPjiipYXnSU0ygqeac2q7CVYMbh84q0uHVRRxK3jCUbO6202Gf6rZfvFkuRx"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('styles')
    <style>
        .sidebar, .main-content, .topbar { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        
        body.sidebar-collapsed .sidebar { width: 80px; }
        body.sidebar-collapsed .main-content { margin-left: 80px; }
        body.sidebar-collapsed .topbar { left: 80px; }
        body.sidebar-collapsed .sidebar-text { display: none !important; }
        body.sidebar-collapsed .sidebar-header { justify-content: center; padding: 24px 0; gap: 6px; }
        body.sidebar-collapsed .sidebar-logo-text { display: none; }
        
        body.sidebar-collapsed .nav-item { 
            justify-content: center !important; 
            width: 44px !important; 
            height: 44px !important; 
            margin: 0 auto 12px !important; 
            padding: 0 !important;
            border-radius: 12px !important;
        }
        
        body.sidebar-collapsed .nav-section-title { display: none; }
        body.sidebar-collapsed .nav-sub-container { display: none !important; }
        body.sidebar-collapsed .sidebar-user { justify-content: center; margin: 0 auto; width: 44px; height: 44px; padding: 0 !important; }
        body.sidebar-collapsed .sidebar-user-info { display: none !important; }
        body.sidebar-collapsed .settings-btn { display: none !important; }
        body.sidebar-collapsed .sidebar { overflow-x: hidden !important; }
        body.sidebar-collapsed .collapse-btn svg { transform: rotate(180deg); }
        
        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); display: flex !important; width: 260px !important; }
            body.sidebar-mobile-open .sidebar { transform: translateX(0); }
            .main-content { margin-left: 0 !important; }
            .topbar { left: 0 !important; }
            .collapse-btn { display: none !important; }
        }
        
        body.sidebar-collapsed .sidebar-logo-link { width: 28px !important; overflow: hidden; display: flex; justify-content: flex-start; margin-left: 2px; }
        body.sidebar-collapsed .sidebar-logo-link svg { flex-shrink: 0; }
    </style>
</head>
<body x-data="{ sidebarCollapsed: false, sidebarMobileOpen: false }" :class="{ 'sidebar-collapsed': sidebarCollapsed, 'sidebar-mobile-open': sidebarMobileOpen }">
    <!-- Mobile overlay -->
    <div x-show="sidebarMobileOpen" @click="sidebarMobileOpen = false" class="fixed inset-0 bg-gray-900/50 z-[95] lg:hidden" style="display: none;" x-transition.opacity></div>

    <x-sidebar :active-page="Route::currentRouteName() ?? 'dashboard'" />

    <div class="main-content">
        <x-topbar />

        {{-- Page Content --}}
        <main class="page-content fade-in p-6">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
