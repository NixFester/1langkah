<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - 1Langkah</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        .admin-sidebar { width: 250px; background: #111827; color: #fff; min-height: 100vh; position: fixed; left: 0; top: 0; padding: 20px 0; }
        .admin-sidebar a { color: #d1d5db; text-decoration: none; padding: 12px 24px; display: block; font-size: 14px; }
        .admin-sidebar a:hover, .admin-sidebar a.active { background: #1f2937; color: #fff; border-left: 3px solid #d10000; }
        .admin-content { margin-left: 250px; padding: 32px; }
        .admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; border-bottom: 1px solid #e5e7eb; padding-bottom: 16px; }
        .admin-card { background: #fff; border-radius: 12px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 24px; }
    </style>
</head>
<body>
    <div class="admin-sidebar">
        <div style="padding: 0 24px 20px; font-size: 20px; font-weight: bold; color: #fff;">1Langkah Admin</div>
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
        <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users') ? 'active' : '' }}">Manage Users</a>
        <a href="{{ route('admin.courses') }}" class="{{ request()->routeIs('admin.courses') ? 'active' : '' }}">Manage Courses</a>
        <a href="{{ route('admin.bootcamps') }}" class="{{ request()->routeIs('admin.bootcamps') ? 'active' : '' }}">Manage Bootcamps</a>
        <a href="{{ route('admin.events') }}" class="{{ request()->routeIs('admin.events') ? 'active' : '' }}">Manage Events</a>
        <a href="{{ route('dashboard') }}" style="margin-top: 40px; color: #9ca3af;">&larr; Back to App</a><form method="POST" action="{{ route('logout') }}" style="margin-top: 10px;">
            @csrf
            <button type="submit" style="background: none; border: none; color: #9ca3af; padding: 12px 24px; cursor: pointer; font-size: 14px; text-align: left; width: 100%;">Logout</button>
        </form>
    </div>

    <div class="admin-content">
        <div class="admin-header">
            <h2 style="font-size: 24px; font-weight: 700;">@yield('title', 'Dashboard')</h2>
            <div>Logged in as: <strong>{{ auth()->user()->name }}</strong></div>
        </div>

        @yield('content')
    </div>
</body>
</html>