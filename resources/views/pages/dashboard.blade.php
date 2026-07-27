@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="w-full px-0 sm:px-2 pb-8 space-y-4 sm:space-y-6">

    <!-- HERO SECTION -->
    <div class="bg-black rounded-2xl sm:rounded-3xl p-6 sm:p-10 flex flex-col sm:flex-row items-center justify-between relative overflow-hidden shadow-lg sm:shadow-xl bg-cover bg-center bg-no-repeat" style="background-image: url('{{ asset('images/dashboard-hero.png') }}');">
        <!-- Overlay for text readability -->
        <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/50 to-transparent"></div>

        <div class="relative z-10 text-white w-full sm:w-2/3 space-y-3 sm:space-y-4">
            <div class="text-white/90 font-medium flex items-center gap-2 text-sm sm:text-base">
                {{ __('app.welcome_back') }}
            </div>
            <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight">{{ auth()->user()->name ?? 'User' }}</h1>
            <p class="text-white/80 text-[13px] sm:text-base leading-relaxed max-w-lg">{{ auth()->user()->bio ?? __('app.no_bio') }}</p>

            <div class="pt-3 sm:pt-4 flex flex-col sm:flex-row gap-3">
                <a href="{{ route('kursus-saya') }}" class="bg-white text-[#cc0000] hover:bg-gray-50 px-6 py-3 sm:py-2.5 rounded-xl sm:rounded-full font-bold text-sm transition-colors flex items-center justify-center sm:justify-start gap-2 shadow-sm inline-flex w-full sm:w-auto">
                    {{ __('app.continue_learning') }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
                <a href="{{ route('kursus') }}" class="bg-white/10 hover:bg-white/20 text-white border border-white/20 px-6 py-3 sm:py-2.5 rounded-xl sm:rounded-full font-bold text-sm transition-colors flex items-center justify-center sm:justify-start gap-2 inline-flex w-full sm:w-auto">
                    {{ __('app.browse_courses') }}
                </a>
            </div>
        </div>

        <div class="relative z-10 mt-6 sm:mt-0 hidden sm:block">
            <div class="w-32 h-32 md:w-40 md:h-40 rounded-full border-4 border-white/20 p-1 relative">
                @if(auth()->user()->profile_photo)
                <img alt="" src="{{ auth()->user()->profile_photo }}" alt="Profile" class="w-full h-full rounded-full object-cover bg-red-900" fetchpriority="high">
                @else
                <div class="w-full h-full rounded-full bg-red-800 flex items-center justify-center text-white text-3xl font-bold">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                </div>
                @endif
                <div class="absolute bottom-2 right-2 w-5 h-5 bg-green-500 border-2 border-[#cc0000] rounded-full"></div>
            </div>
        </div>
    </div>

    <!-- 4 STAT CARDS -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
        <x-stat-card :value="$userStats['courses_enrolled'] ?? 0" :label="__('app.active_courses')" icon="award" color="red" />
        <x-stat-card :value="$userStats['bootcamps_enrolled'] ?? 0" :label="__('app.active_bootcamps')" icon="target" color="blue" />
        <x-stat-card :value="$userStats['courses_completed'] ?? 0" :label="__('app.completed_courses')" icon="checkCircle" color="green" />
        <x-stat-card :value="$userStats['bootcamps_completed'] ?? 0" :label="__('app.completed_bootcamps')" icon="shieldCheck" color="purple" />
    </div>

    <!-- GRID LAYOUT SECTION -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- ROW 1 -->
        <!-- Gamification (XP & Leaderboard) - HIDDEN -->
        @if(false)
        <div class="flex flex-col gap-6 lg:col-span-1 order-1 lg:order-none">
            
            <!-- XP & Level Widget -->
            <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-2xl p-5 text-white shadow-lg relative overflow-hidden">
                <!-- Decorative circles -->
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
                
                <div class="flex items-center justify-between mb-4 relative z-10">
                    <div>
                        <p class="text-white/80 text-xs font-medium">{{ __('app.current_level') }}</p>
                        <h2 class="text-3xl font-extrabold">{{ auth()->user()->level ?? 1 }}</h2>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                </div>

                <!-- XP Progress -->
                <div class="mb-3 relative z-10">
                    <div class="flex justify-between text-xs text-white/80 mb-1">
                        <span>{{ number_format(auth()->user()->xp ?? 0) }} XP</span>
                        <span>{{ number_format($xpToNextLevel ?? 100) }} {{ __('app.xp_more') }}</span>
                    </div>
                    <div class="h-2 bg-white/20 rounded-full overflow-hidden">
                        <div class="h-full bg-yellow-400 rounded-full transition-all duration-500" style="width: {{ $xpProgressPercent ?? 0 }}%"></div>
                    </div>
                </div>

                <!-- XP Actions -->
                <div class="flex gap-2 relative z-10 mt-4">
                    <a href="{{ route('achievement') }}" class="flex-1 bg-white/20 hover:bg-white/30 text-white text-[11px] font-bold py-2 px-2 rounded-lg text-center transition-colors">
                        {{ __('app.rank') }}
                    </a>
                    <a href="{{ route('api.xp.details') }}" class="flex-1 bg-white/20 hover:bg-white/30 text-white text-[11px] font-bold py-2 px-2 rounded-lg text-center transition-colors">
                        {{ __('app.history') }}
                    </a>
                </div>
            </div>

            <!-- Leaderboard -->
            @if(!empty($leaderboard) && count($leaderboard) > 0)
            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden shadow-sm flex-1 flex flex-col">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        {{ __('app.leaderboard') }}
                    </h2>
                </div>
                <div class="divide-y divide-gray-50 overflow-y-auto flex-1 min-h-0">
                    @foreach($leaderboard as $index => $user)
                    <div class="px-4 py-3 flex items-center gap-3 hover:bg-gray-50 transition-colors">
                        <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0
                            @if($index === 0) bg-yellow-100 text-yellow-700
                            @elseif($index === 1) bg-gray-200 text-gray-600
                            @elseif($index === 2) bg-orange-100 text-orange-700
                            @else bg-gray-100 text-gray-500 @endif">
                            {{ $index + 1 }}
                        </div>
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center overflow-hidden flex-shrink-0">
                            @if($user['avatar'])
                                <img decoding="async" loading="lazy" src="{{ $user['avatar'] }}" alt="{{ $user['name'] }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-blue-600 font-bold text-xs">{{ substr($user['name'] ?? 'U', 0, 1) }}</span>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $user['name'] }}</p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="text-sm font-bold text-gray-900">{{ number_format($user['xp']) }}</p>
                            <p class="text-[10px] text-gray-500">XP</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                <a href="{{ route('achievement') }}" class="block text-center text-xs font-bold text-blue-600 py-3 bg-gray-50 hover:bg-gray-100 transition-colors mt-auto border-t border-gray-100">
                    {!! __('app.global_rank') !!}
                </a>
            </div>
            @endif
        </div>
        @endif

        <!-- Lanjutkan Belajar -->
        <div class="lg:col-span-2 order-1 lg:order-1">
            <x-card-panel :title="__('app.continue_learning')" :actionRoute="route('kursus-saya')" :actionLabel="__('app.see_all')" class="h-full">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @forelse(array_slice($activeCourses ?? [], 0, 3) as $course)
                    <a href="{{ route('detail-kursus', ['id' => $course['id']]) }}" class="block border border-gray-100 rounded-xl overflow-hidden hover:shadow-lg hover:border-gray-200 transition-all bg-gray-50/50 group">
                        <div class="h-28 bg-gray-200 relative overflow-hidden">
                            @if(!empty($course['thumbnail']))
                            <img decoding="async" loading="lazy" src="{{ $course['thumbnail'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="">
                            @else
                            <div class="w-full h-full group-hover:scale-105 transition-transform duration-500" style="background: linear-gradient(135deg, {{ $course['color'] ?? '#dc2626' }}, {{ $course['color'] ?? '#dc2626' }}cc);"></div>
                            @endif
                        </div>
                        <div class="p-4 bg-white">
                            <h2 class="text-sm font-bold text-gray-900 truncate group-hover:text-red-600 transition-colors">{{ $course['title'] }}</h2>
                            <p class="text-[11px] text-gray-500 mb-3 truncate">{{ $course['mentor'] ?? 'Mentor' }}</p>
                            <div class="flex items-center gap-3">
                                <div class="h-1.5 flex-1 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-red-600 rounded-full" style="width: {{ $course['progress'] ?? 0 }}%"></div>
                                </div>
                                <span class="text-[10px] font-bold text-gray-700">{{ $course['progress'] ?? 0 }}%</span>
                            </div>
                        </div>
                    </a>
                    @empty
                    <div>
                        <x-empty-state :message="__('app.no_started_courses')" icon="inbox" :actionRoute="route('kursus')" :actionLabel="__('app.browse_courses')" />
                    </div>
                    @endforelse
                </div>
            </x-card-panel>
        </div>

        <!-- Bootcamp Saya -->
        <div class="lg:col-span-2 order-3 lg:order-3">
            <x-card-panel :title="__('app.my_bootcamps')" :actionRoute="route('bootcamps-saya')" :actionLabel="__('app.view')" class="h-full">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse(array_slice($myBootcamps ?? [], 0, 3) as $bootcamp)
                    <x-list-item
                        :href="route($bootcamp['type'] === 'online' ? 'detail-online-bootcamp' : 'detail-offline-bootcamp', ['id' => $bootcamp['id']])"
                        :thumbnail="$bootcamp['thumbnail'] ?? null"
                        :title="$bootcamp['title']"
                        :subtitle="$bootcamp['mentor'] ?? 'Mentor'"
                        :progress="$bootcamp['progress'] ?? 0"
                        progressColor="blue"
                        :meta="($bootcamp['progress'] ?? 0) . '% (' . ($bootcamp['attended'] ?? 0) . '/' . ($bootcamp['sessions'] ?? 0) . ')'"
                        :badge="['text' => $bootcamp['type'] === 'online' ? 'Online' : 'Offline', 'class' => $bootcamp['type'] === 'online' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700']"
                    />
                    @empty
                    <x-empty-state :message="__('app.no_bootcamps')" icon="users" :actionRoute="route('online-bootcamp')" :actionLabel="__('app.browse')" />
                    @endforelse
                </div>
            </x-card-panel>
        </div>

        <!-- Events Mendatang -->
        <div class="lg:col-span-1 order-2 lg:order-2">
            <x-card-panel :title="__('app.upcoming_events')" :actionRoute="route('event')" :actionLabel="__('app.view')" class="h-full">
                <div class="space-y-4">
                    @forelse($upcomingEvents ?? [] as $event)
                    <a href="{{ route('detail-event', ['id' => $event['id']]) }}" class="flex items-start gap-3 p-2 -mx-2 rounded-xl hover:bg-gray-50 transition-colors">
                        <div class="w-12 h-12 rounded-xl flex flex-col items-center justify-center text-white flex-shrink-0 overflow-hidden" style="background-color: {{ $event['color'] ?? '#cc0000' }}">
                            @if(!empty($event['banner_url']))
                                <img decoding="async" loading="lazy" src="{{ str_starts_with($event['banner_url'], 'http') ? $event['banner_url'] : asset($event['banner_url']) }}" alt="{{ $event['title'] }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-xs font-bold text-center leading-tight">{!! str_replace(' ', '<br>', $event['date']) !!}</span>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h2 class="text-sm font-bold text-gray-900 truncate">{{ $event['title'] }}</h2>
                            <p class="text-[11px] text-gray-500">{{ $event['day'] }}, {{ $event['time'] }}</p>
                            <span class="inline-block mt-1 text-[10px] font-bold px-2 py-0.5 rounded-full bg-purple-100 text-purple-700">
                                {{ ucfirst($event['type'] ?? 'webinar') }}
                            </span>
                        </div>
                    </a>
                    @empty
                    <x-empty-state :message="__('app.no_events')" icon="calendar" :actionRoute="route('event')" :actionLabel="__('app.browse')" />
                    @endforelse
                </div>
            </x-card-panel>
        </div>

        <!-- ROW 2 -->
        <!-- Prestasi & Badge -->
        <div class="lg:col-span-1 order-4 lg:order-4">
            <x-card-panel :title="__('app.achievements')" :actionRoute="route('achievement')" :actionLabel="__('app.all')" class="h-full">
                <div class="space-y-3">
                    @if(!empty($userAchievements) && $userAchievements->count() > 0)
                        @foreach($userAchievements->take(3) as $achievement)
                        <div class="flex items-center gap-3 p-2 -mx-2 rounded-xl hover:bg-gray-50 transition-colors">
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center shadow flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $achievement->achievement->name ?? 'Achievement' }}</p>
                                <p class="text-[10px] text-gray-500">{{ $achievement->earned_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <x-empty-state :message="__('app.collect_badges')" icon="trophy" />
                    @endif
                </div>
            </x-card-panel>
        </div>

        <!-- Rekomendasi Kursus -->
        <div class="lg:col-span-2 order-5 lg:order-5">
            <x-card-panel :title="__('app.course_recommendations')" :actionRoute="route('kursus')" :actionLabel="__('app.explore')" class="h-full">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @forelse(array_slice($recommendedCourses ?? [], 0, 4) as $course)
                    <a href="{{ route('detail-kursus', ['id' => $course['id']]) }}" class="flex gap-3 items-center hover:bg-gray-50 p-2 -mx-2 rounded-xl transition-colors">
                        <div class="w-16 h-16 rounded-lg bg-gray-100 flex-shrink-0 overflow-hidden relative">
                            @if(!empty($course['thumbnail']))
                            <img decoding="async" loading="lazy" src="{{ $course['thumbnail'] }}" class="w-full h-full object-cover" alt="">
                            @else
                            <div class="w-full h-full flex items-center justify-center" style="background: linear-gradient(135deg, {{ $course['color'] ?? '#dc2626' }}, {{ $course['color'] ?? '#dc2626' }}cc);">
                                <x-icon name="book" class="w-5 h-5 text-white" />
                            </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h2 class="text-sm font-bold text-gray-900 truncate">{{ $course['title'] }}</h2>
                            <p class="text-[11px] text-gray-500 truncate">{{ $course['mentor'] ?? 'Mentor' }}</p>
                            <div class="flex items-center gap-2 mt-1">
                                <div class="flex items-center text-yellow-500">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    <span class="text-[10px] font-medium text-gray-600 ml-0.5">{{ number_format($course['rating'], 1) }}</span>
                                </div>
                                <span class="text-[10px] text-gray-500">•</span>
                                <span class="text-[10px] text-gray-500">{{ $course['enrolledCount'] ?? 0 }} pt</span>
                            </div>
                        </div>
                    </a>
                    @empty
                    <div class="sm:col-span-2">
                        <x-empty-state :message="__('app.no_recommendations')" icon="sparkles" />
                    </div>
                    @endforelse
                </div>
            </x-card-panel>
        </div>

        <!-- Aktivitas Terbaru -->
        <div class="lg:col-span-1 order-6 lg:order-6">
            <x-card-panel :title="__('app.activity')" class="h-full">
                <div class="space-y-4">
                    @forelse(array_slice($recentActivities ?? [], 0, 4) as $activity)
                    <div class="flex items-center gap-3 p-2 -mx-2 rounded-xl hover:bg-gray-50 transition-colors">
                        <div class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background-color: {{ $activity['color'] ?? '#3b82f6' }}"></div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[12px] text-gray-700 leading-tight">{{ $activity['text'] }}</p>
                            <p class="text-[10px] text-gray-500 mt-1">{{ $activity['time'] }}</p>
                        </div>
                    </div>
                    @empty
                    <x-empty-state :message="__('app.no_activity')" icon="clock" />
                    @endforelse
                </div>
            </x-card-panel>
        </div>
    </div>

</div>
@endsection
