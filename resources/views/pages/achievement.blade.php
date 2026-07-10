@extends('layouts.app', ['activePage' => 'achievement'])

@section('title', __('app.achievements') . ' - 1Langkah')

@section('header_title', __('app.achievements_badges'))

@section('content')
<div class="w-full px-2 pb-8 space-y-6">

    <!-- Header Stats -->
    <div class="bg-gradient-to-r from-red-600 to-red-700 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold mb-1">{{ __('app.achievements_badges') }}</h1>
                <p class="text-white/80 text-sm">{{ __('app.achievements_subtitle') }}</p>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold">{{ $totalEarned }}</div>
                <div class="text-sm text-white/80">{{ __('app.from') }} {{ $totalAvailable }} {{ __('app.achievements') }}</div>
            </div>
        </div>
        <!-- Progress Bar -->
        <div class="mt-4 w-full bg-white/20 rounded-full h-2">
            <div class="bg-white rounded-full h-2 transition-all duration-500"
                 style="width: {{ $totalAvailable > 0 ? ($totalEarned / $totalAvailable) * 100 : 0 }}%"></div>
        </div>
    </div>

    <!-- Earned Achievements -->
    @if($userAchievements->count() > 0)
    <div>
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
            {{ __('app.unlocked_achievements') }}
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($userAchievements as $userAchievement)
            <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-start gap-4">
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center shadow-lg flex-shrink-0">
                        {!! $userAchievement->achievement->icon ?? '<svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>' !!}
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-gray-900 mb-1">{{ $userAchievement->achievement->name }}</h3>
                        <p class="text-sm text-gray-500 mb-2">{{ $userAchievement->achievement->description }}</p>
                        <div class="flex items-center gap-2 text-xs text-gray-400">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            {{ __('app.obtained') }} {{ $userAchievement->earned_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Available Achievements by Category -->
    @foreach($allAchievements as $category => $achievements)
    <div>
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2 capitalize">
            @switch($category)
                @case('learning')
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    @break
                @case('engagement')
                    <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    @break
                @case('achievement')
                    <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                    @break
                @case('streak')
                    <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path></svg>
                    @break
                @default
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
            @endswitch
            {{ ucfirst(str_replace('_', ' ', $category)) }}
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($achievements as $achievement)
                @php
                    $isEarned = $userAchievements->contains('achievement_id', $achievement->id);
                @endphp
                <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm {{ $isEarned ? 'opacity-100' : 'opacity-60' }}">
                    <div class="flex items-start gap-4">
                        <div class="w-14 h-14 rounded-xl flex items-center justify-center shadow-lg flex-shrink-0
                            @if($isEarned)
                                bg-gradient-to-br from-yellow-400 to-orange-500
                            @else
                                bg-gray-200
                            @endif">
                            {!! $achievement->icon ?? '<svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>' !!}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-gray-900 mb-1 flex items-center gap-2">
                                {{ $achievement->name }}
                                @if($isEarned)
                                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                @endif
                            </h3>
                            <p class="text-sm text-gray-500 mb-2">{{ $achievement->description }}</p>
                            @if(!$isEarned && $achievement->trigger_conditions)
                            <div class="text-xs text-gray-400">
                                <span class="font-medium">{{ __('app.requirement') }}:</span>
                                @if(is_array($achievement->trigger_conditions))
                                    @foreach($achievement->trigger_conditions as $condition => $value)
                                        @if(is_array($value))
                                            {{ ucfirst($condition) }}:
                                            @foreach($value as $req => $val)
                                                {{ ucfirst(str_replace('_', ' ', $req)) }}: {{ $val }}@if(!$loop->last), @endif
                                            @endforeach
                                        @else
                                            {{ ucfirst(str_replace('_', ' ', $condition)) }}: {{ $value }}
                                        @endif
                                        @if(!$loop->last) • @endif
                                    @endforeach
                                @else
                                    {{ $achievement->trigger_conditions }}
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endforeach

</div>
@endsection
