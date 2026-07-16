@extends('layouts.superadmin')

@section('title', __('app.superadmin_dashboard'))
@section('header_title', __('app.superadmin_dashboard'))

@section('content')
    <x-flash-messages />

    <div class="w-full px-2 pb-8 space-y-6">
        <!-- HERO SECTION -->
        <div class="bg-[#cc0000] rounded-2xl p-6 md:p-8 flex flex-col md:flex-row items-center justify-between relative overflow-hidden shadow-xl">
            <!-- Glow effect -->
            <div class="absolute -right-20 -top-20 w-[400px] h-[400px] bg-red-600 rounded-full blur-[80px] pointer-events-none opacity-50"></div>
            
            <div class="relative z-10 text-white w-full md:w-2/3 space-y-4">
                <div class="text-white font-medium flex items-center gap-2">
                    {{ __('app.welcome_back') }}
                </div>
                <h1 class="text-3xl sm:text-4xl font-bold">{{ auth()->user()->name ?? 'Superadmin' }}</h1>
                <p class="text-white text-sm sm:text-base">{{ __('app.superadmin_dashboard') ?? 'Superadmin Panel' }}</p>
                
                <div class="flex flex-wrap items-center gap-3 pt-2">
                    <div class="bg-red-800/50 backdrop-blur-sm border border-red-500/30 rounded-full px-4 py-2 text-sm font-medium flex items-center gap-2">
                        <x-icon name="users" class="w-4 h-4 text-blue-200" />
                        {{ $userStats['total'] ?? 0 }} {{ __('app.total_users') }}
                    </div>
                    <div class="bg-red-800/50 backdrop-blur-sm border border-red-500/30 rounded-full px-4 py-2 text-sm font-medium flex items-center gap-2">
                        <x-icon name="book" class="w-4 h-4 text-orange-200" />
                        {{ $systemStats['courses'] ?? 0 }} {{ __('app.courses') }}
                    </div>
                    <div class="bg-red-800/50 backdrop-blur-sm border border-red-500/30 rounded-full px-4 py-2 text-sm font-medium flex items-center gap-2">
                        <x-icon name="award" class="w-4 h-4 text-yellow-200" />
                        {{ $systemStats['enrollments'] ?? 0 }} {{ __('app.enrollments') }}
                    </div>
                </div>
            </div>
            
            <div class="relative z-10 mt-6 md:mt-0 hidden sm:block">
                <div class="w-32 h-32 md:w-40 md:h-40 rounded-full border-4 border-white/20 p-1 relative">
                    @if(auth()->user()->profile_photo)
                    <img decoding="async" loading="lazy" alt="" src="{{ auth()->user()->profile_photo }}" alt="Admin Profile" class="w-full h-full rounded-full object-cover bg-red-900" fetchpriority="high">
                    @else
                    <div class="w-full h-full rounded-full bg-[#7f1d1d] flex items-center justify-center text-white text-3xl md:text-5xl font-bold">
                        {{ strtoupper(substr(auth()->user()->name ?? 'S', 0, 2)) }}
                    </div>
                    @endif
                    <div class="absolute bottom-2 right-2 w-5 h-5 bg-green-500 border-2 border-[#cc0000] rounded-full"></div>
                </div>
            </div>
        </div>

        <!-- 4 STAT CARDS -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
            <x-stat-card :value="$userStats['total'] ?? 0" :label="__('app.total_users')" icon="users" color="blue" />
            <x-stat-card :value="$systemStats['courses'] ?? 0" :label="__('app.course')" icon="book" color="red" />
            <x-stat-card :value="$systemStats['enrollments'] ?? 0" :label="__('app.enrollments')" icon="award" color="green" />
            <x-stat-card :value="'Rp ' . number_format($systemStats['revenue_this_month'] ?? 0, 0, ',', '.')" :label="__('app.revenue_this_month')" icon="creditCard" color="amber" />
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Role Distribution --}}
            <x-card-panel :title="__('app.role_distribution')">
                <div class="space-y-3">
                    @foreach($roleDistribution as $role => $count)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full" style="background-color: {{ match($role) { 'superadmin' => '#D10000', 'admin' => '#ef4444', 'keuangan' => '#f59e0b', 'marketing' => '#ec4899', 'mentor' => '#3b82f6', default => '#10b981' } }}"></div>
                                <span class="text-sm text-gray-600 capitalize">{{ ucfirst($role) }}</span>
                            </div>
                            <span class="font-bold text-gray-800">{{ $count }}</span>
                        </div>
                    @endforeach
                </div>
            </x-card-panel>

            {{-- Recent Users --}}
            <x-card-panel :title="__('app.recent_users')" :actionRoute="route('superadmin.users')" :actionLabel="__('app.view_all')">
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
            <x-card-panel :title="__('app.recent_activity')" :actionRoute="route('superadmin.audit-logs')" :actionLabel="__('app.view_all')">
                <div class="space-y-3">
                    @forelse($recentActivity->take(5) as $log)
                        <div class="flex items-start gap-3 p-3 -mx-2 rounded-xl hover:bg-gray-50 transition-colors">
                            <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="fa {{ $log->action_icon ?? 'fa-circle' }} text-xs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-800">{{ $log->description ?? $log->action_label }}</p>
                                <p class="text-xs text-gray-400">{{ $log->user?->name ?? __('app.system') }} - {{ $log->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <x-empty-state :message="__('app.no_activity')" icon="chart" />
                    @endforelse
                </div>
            </x-card-panel>
        </div>

        {{-- Quick Actions --}}
        <x-card-panel :title="__('app.quick_actions')">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('superadmin.users') }}" class="p-4 bg-red-50 rounded-lg hover:bg-red-100 transition-colors text-center">
                    <svg class="w-8 h-8 text-red-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <p class="text-sm font-medium text-red-800">{{ __('app.manage_users') }}</p>
                </a>
                <a href="{{ route('superadmin.audit-logs') }}" class="p-4 bg-red-50 rounded-lg hover:bg-red-100 transition-colors text-center">
                    <svg class="w-8 h-8 text-red-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <p class="text-sm font-medium text-red-800">{{ __('app.audit_log') }}</p>
                </a>
                <a href="{{ route('superadmin.system-stats') }}" class="p-4 bg-red-50 rounded-lg hover:bg-red-100 transition-colors text-center">
                    <svg class="w-8 h-8 text-red-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <p class="text-sm font-medium text-red-800">{{ __('app.statistics') }}</p>
                </a>
                <a href="{{ route('dashboard') }}" target="_blank" class="p-4 bg-red-50 rounded-lg hover:bg-red-100 transition-colors text-center">
                    <svg class="w-8 h-8 text-red-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    <p class="text-sm font-medium text-red-800">{{ __('app.open_app') }}</p>
                </a>
            </div>
        </x-card-panel>
    </div>
@endsection
