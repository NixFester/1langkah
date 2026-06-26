<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '1Langkah Dashboard')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>
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
