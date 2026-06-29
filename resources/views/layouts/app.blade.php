<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '1Langkah Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('styles')
    <style>
        .sidebar, .main-content, .topbar { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        
        body.sidebar-collapsed .sidebar { width: 88px; }
        body.sidebar-collapsed .main-content { margin-left: 88px; }
        body.sidebar-collapsed .topbar { left: 88px; }
        body.sidebar-collapsed .sidebar-text { display: none; }
        body.sidebar-collapsed .sidebar-header { justify-content: center; padding: 16px 0; }
        body.sidebar-collapsed .sidebar-logo-text { display: none; }
        body.sidebar-collapsed .nav-item { justify-content: center; }
        body.sidebar-collapsed .nav-section-title { text-align: center; font-size: 0; }
        body.sidebar-collapsed .nav-section-title::after { content: '•••'; font-size: 14px; letter-spacing: 2px; }
        body.sidebar-collapsed .sidebar-user { justify-content: center; }
        body.sidebar-collapsed .sidebar-user-info { display: none; }
        body.sidebar-collapsed .collapse-btn svg { transform: rotate(180deg); }
        
        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); display: flex !important; width: 260px !important; }
            body.sidebar-mobile-open .sidebar { transform: translateX(0); }
            .main-content { margin-left: 0 !important; }
            .topbar { left: 0 !important; }
            .collapse-btn { display: none !important; }
        }
        
        body.sidebar-collapsed .sidebar-logo-link { width: 24px !important; overflow: hidden; }
        body.sidebar-collapsed .sidebar-header { padding: 16px 20px; justify-content: space-between; }
    </style>
</head>
<body x-data="{ sidebarCollapsed: false, sidebarMobileOpen: false }" :class="{ 'sidebar-collapsed': sidebarCollapsed, 'sidebar-mobile-open': sidebarMobileOpen }">
    <!-- Mobile overlay -->
    <div x-show="sidebarMobileOpen" @click="sidebarMobileOpen = false" class="fixed inset-0 bg-gray-900/50 z-[95] lg:hidden" style="display: none;" x-transition.opacity></div>

    <x-sidebar :active-page="$activePage ?? 'dashboard'" />

    <div class="main-content">
        <x-topbar />
        <div class="page-content fade-in">
            @yield('content')
        </div>
    </div>

    @stack('scripts')
</body>
</html>
