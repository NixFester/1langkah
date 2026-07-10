@php
    $activePage = 'bootcamps-saya';
@endphp

@extends('layouts.app')

@section('title', __('app.my_bootcamps') . ' — 1Langkah')

@section('content')
<div class="w-full px-2 pb-8 space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 -mt-2">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ __('app.my_bootcamps') }}</h1>
            <p class="text-sm text-gray-500">{{ count($myBootcamps) }} {{ __('app.active_bootcamps_count') }}</p>
        </div>
        <a href="{{ route('online-bootcamp') }}" class="bg-red-600 hover:bg-red-700 text-white rounded-full px-5 py-3 text-sm font-bold transition-colors flex items-center gap-2">
            <span>{{ __('app.find_bootcamp') }}</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            @forelse($myBootcamps as $bootcamp)
                <a href="{{ route($bootcamp['type'] === 'offline' ? 'detail-offline-bootcamp' : 'detail-online-bootcamp', ['id' => $bootcamp['id']]) }}" class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow block">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <div class="w-full sm:w-28 h-28 rounded-3xl bg-gray-100 overflow-hidden flex-shrink-0">
                            @if(!empty($bootcamp['thumbnail']))
                                <img src="{{ $bootcamp['thumbnail'] }}" alt="{{ $bootcamp['title'] }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-red-200"></div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h2 class="text-lg font-bold text-gray-900">{{ $bootcamp['title'] }}</h2>
                                    <p class="text-sm text-gray-500 mt-1">{{ $bootcamp['mentor'] }}</p>
                                </div>
                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[11px] font-semibold {{ $bootcamp['type'] === 'offline' ? 'bg-orange-50 text-orange-700' : 'bg-purple-50 text-purple-700' }}">
                                    {{ $bootcamp['type'] === 'offline' ? 'Offline' : 'Online' }}
                                </span>
                            </div>
                            <div class="mt-4 grid grid-cols-2 gap-3 text-sm text-gray-500">
                                <div class="bg-gray-50 rounded-2xl p-4">
                                    <div class="font-semibold text-gray-900">{{ $bootcamp['sessions'] ?? '-' }}</div>
                                    <div>{{ __('app.sessions') }}</div>
                                </div>
                                <div class="bg-gray-50 rounded-2xl p-4">
                                    <div class="font-semibold text-gray-900">{{ $bootcamp['progress'] ?? 0 }}%</div>
                                    <div>{{ __('app.progress') }}</div>
                                </div>
                                <div class="bg-gray-50 rounded-2xl p-4">
                                    <div class="font-semibold text-gray-900">{{ number_format($bootcamp['rating'] ?? 0, 1) }}</div>
                                    <div>{{ __('app.rating') }}</div>
                                </div>
                                <div class="bg-gray-50 rounded-2xl p-4">
                                    <div class="font-semibold text-gray-900">{{ $bootcamp['enrolled_at'] ? date('d M Y', strtotime($bootcamp['enrolled_at'])) : '-' }}</div>
                                    <div>{{ __('app.enrolled_at') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <x-empty-state
                    :message="__('app.no_enrolled_bootcamp')"
                    icon="users"
                    :actionRoute="route('online-bootcamp')"
                    :actionLabel="__('app.find_bootcamp')"
                />
            @endforelse
        </div>

        <div class="space-y-6">
            <x-card-panel :title="__('app.bootcamp_stats')">
                <div class="grid grid-cols-2 gap-4 text-sm text-gray-700">
                    <div class="rounded-3xl bg-red-50 p-4">
                        <div class="text-3xl font-bold text-red-600">{{ $userStats['bootcamps_enrolled'] ?? 0 }}</div>
                        <div class="mt-1">{{ __('app.enrolled_bootcamps') }}</div>
                    </div>
                    <div class="rounded-3xl bg-green-50 p-4">
                        <div class="text-3xl font-bold text-green-600">{{ $userStats['bootcamps_completed'] ?? 0 }}</div>
                        <div class="mt-1">{{ __('app.completed_bootcamps') }}</div>
                    </div>
                    <div class="rounded-3xl bg-purple-50 p-4">
                        <div class="text-3xl font-bold text-purple-600">{{ $userStats['xp'] ?? 0 }}</div>
                        <div class="mt-1">{{ __('app.total_xp') }}</div>
                    </div>
                    <div class="rounded-3xl bg-yellow-50 p-4">
                        <div class="text-3xl font-bold text-yellow-700">{{ $userStats['certificates'] ?? 0 }}</div>
                        <div class="mt-1">{{ __('app.certificates') }}</div>
                    </div>
                </div>
            </x-card-panel>
            <x-card-panel :title="__('app.bootcamp_guide')">
                <ul class="space-y-3 text-sm text-gray-600">
                    <li class="flex gap-3"><span class="text-red-600">•</span> {{ __('app.guide_attendance') }}</li>
                    <li class="flex gap-3"><span class="text-red-600">•</span> {{ __('app.guide_material') }}</li>
                    <li class="flex gap-3"><span class="text-red-600">•</span> {{ __('app.guide_mentor') }}</li>
                </ul>
            </x-card-panel>
        </div>
    </div>
</div>
@endsection
