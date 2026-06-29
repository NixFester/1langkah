@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="grid-4">
    <div class="admin-card">
        <div style="font-size: 14px; color: #6b7280;">Total Users</div>
        <div style="font-size: 32px; font-weight: 700; margin-top: 8px;">{{ $stats['users'] }}</div>
    </div>
    <div class="admin-card">
        <div style="font-size: 14px; color: #6b7280;">Total Courses</div>
        <div style="font-size: 32px; font-weight: 700; margin-top: 8px;">{{ $stats['courses'] }}</div>
    </div>
    <div class="admin-card">
        <div style="font-size: 14px; color: #6b7280;">Total Bootcamps</div>
        <div style="font-size: 32px; font-weight: 700; margin-top: 8px;">{{ $stats['bootcamps'] }}</div>
    </div>
    <div class="admin-card">
        <div style="font-size: 14px; color: #6b7280;">Revenue (Mock)</div>
        <div style="font-size: 32px; font-weight: 700; margin-top: 8px;">{{ $stats['revenue'] }}</div>
    </div>
</div>

<div class="grid-2">
    <div class="admin-card">
        <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 16px;">Recent Users</h3>
        <ul style="list-style: none; padding: 0;">
            @foreach($recentUsers as $user)
                <li style="padding: 8px 0; border-bottom: 1px solid #f3f4f6; display: flex; justify-content: space-between;">
                    <span>{{ $user->name }}</span>
                    <span style="color: #6b7280; font-size: 12px;">{{ $user->email }}</span>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="admin-card">
        <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 16px;">Recent Courses</h3>
        <ul style="list-style: none; padding: 0;">
            @foreach($recentCourses as $course)
                <li style="padding: 8px 0; border-bottom: 1px solid #f3f4f6; display: flex; justify-content: space-between;">
                    <span>{{ $course->title }}</span>
                    <span style="color: #6b7280; font-size: 12px;">{{ $course->mentor_name }}</span>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection