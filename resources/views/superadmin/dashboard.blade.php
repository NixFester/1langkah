@extends('layouts.superadmin')

@section('title', 'Dashboard Superadmin')
@section('header_title', 'Dashboard Superadmin')

@section('content')
    <x-flash-messages />

    {{-- User Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        <x-stat-card label="Total User" :value="$userStats['total']" color="purple" />
        <x-stat-card label="Admin" :value="$userStats['admins']" color="red" />
        <x-stat-card label="Keuangan" :value="$userStats['keuangans']" color="amber" />
        <x-stat-card label="Marketing" :value="$userStats['marketings']" color="pink" />
        <x-stat-card label="Mentor" :value="$userStats['mentors']" color="blue" />
    </div>

    {{-- System Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <x-gradient-stat label="Kursus" :value="$systemStats['courses']" from="from-purple-500" to="to-purple-600" />
        <x-gradient-stat label="Enrollments" :value="$systemStats['enrollments']" from="from-blue-500" to="to-blue-600" />
        <x-gradient-stat label="Revenue Bulan Ini" :value="'Rp ' . number_format($systemStats['revenue_this_month'], 0, ',', '.')" from="from-green-500" to="to-green-600" />
        <x-gradient-stat label="Pending Payment" :value="$systemStats['pending_payments']" from="from-amber-500" to="to-amber-600" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        {{-- Role Distribution --}}
        <x-card-panel title="Distribusi Role User">
            <div class="space-y-3">
                @foreach($roleDistribution as $role => $count)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full" style="background-color: {{ match($role) { 'superadmin' => '#7c3aed', 'admin' => '#ef4444', 'keuangan' => '#f59e0b', 'marketing' => '#ec4899', 'mentor' => '#3b82f6', default => '#10b981' } }}"></div>
                            <span class="text-sm text-gray-600 capitalize">{{ ucfirst($role) }}</span>
                        </div>
                        <span class="font-bold text-gray-800">{{ $count }}</span>
                    </div>
                @endforeach
            </div>
        </x-card-panel>

        {{-- Recent Users --}}
        <x-card-panel title="User Terbaru" :actionRoute="route('superadmin.users')" actionLabel="Lihat Semua">
            <div class="space-y-3">
                @foreach($recentUsers as $user)
                    <div class="flex items-center justify-between p-3 -mx-2 rounded-xl hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <x-user-avatar :user="$user" size="sm" />
                            <div>
                                <p class="text-sm font-medium text-gray-800">{{ $user->name }}</p>
                                <p class="text-xs text-gray-400">{{ $user->email }}</p>
                            </div>
                        </div>
                        <x-role-badge :role="$user->role" />
                    </div>
                @endforeach
            </div>
        </x-card-panel>

        {{-- Recent Activity --}}
        <x-card-panel title="Aktivitas Terbaru" :actionRoute="route('superadmin.audit-logs')" actionLabel="Lihat Semua">
            <div class="space-y-3">
                @forelse($recentActivity->take(5) as $log)
                    <div class="flex items-start gap-3 p-3 -mx-2 rounded-xl hover:bg-gray-50 transition-colors">
                        <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fa {{ $log->action_icon ?? 'fa-circle' }} text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-800">{{ $log->description ?? $log->action_label }}</p>
                            <p class="text-xs text-gray-400">{{ $log->user?->name ?? 'System' }} - {{ $log->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <x-empty-state message="Belum ada aktivitas" icon="chart" />
                @endforelse
            </div>
        </x-card-panel>
    </div>

    {{-- Quick Actions --}}
    <x-card-panel title="Aksi Cepat">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('superadmin.users') }}" class="p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors text-center">
                <svg class="w-8 h-8 text-purple-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <p class="text-sm font-medium text-purple-800">Kelola User</p>
            </a>
            <a href="{{ route('superadmin.audit-logs') }}" class="p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors text-center">
                <svg class="w-8 h-8 text-blue-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <p class="text-sm font-medium text-blue-800">Audit Log</p>
            </a>
            <a href="{{ route('superadmin.system-stats') }}" class="p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors text-center">
                <svg class="w-8 h-8 text-green-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <p class="text-sm font-medium text-green-800">Statistik</p>
            </a>
            <a href="{{ route('dashboard') }}" target="_blank" class="p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors text-center">
                <svg class="w-8 h-8 text-gray-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                <p class="text-sm font-medium text-gray-800">Buka App</p>
            </a>
        </div>
    </x-card-panel>
@endsection
