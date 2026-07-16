@extends('layouts.mentor')

@section('title', __('app.mentor_dashboard'))

@section('content')
<div class="w-full px-2 pb-8 space-y-6">

    <!-- PAGE HEADER -->
    <x-page-header
        :title="__('app.mentor_dashboard')"
        :description="__('app.welcome_mentor')"
    />

    <x-flash-messages />

    <!-- HERO SECTION -->
    <div class="bg-[#cc0000] rounded-2xl p-6 md:p-8 flex flex-col md:flex-row items-center justify-between relative overflow-hidden shadow-xl">
        <!-- Glow effect -->
        <div class="absolute -right-20 -top-20 w-[400px] h-[400px] bg-red-600 rounded-full blur-[80px] pointer-events-none opacity-50"></div>

        <div class="relative z-10 text-white w-full sm:w-2/3 space-y-3 sm:space-y-4">
            <h1 class="text-3xl sm:text-4xl font-bold">{{ auth()->user()->name ?? 'Mentor' }}</h1>
            <p class="text-white text-sm sm:text-base leading-relaxed max-w-lg">{{ auth()->user()->mentor?->bio ?? __('app.no_bio_add_in_settings') }}</p>

            <div class="pt-3 sm:pt-4 flex flex-col sm:flex-row gap-3">
                <a href="{{ route('mentor.sessions.index') }}" class="bg-white text-[#cc0000] hover:bg-gray-50 px-6 py-2.5 rounded-full font-bold text-sm transition-colors flex items-center justify-center sm:justify-start gap-2 shadow-sm inline-flex w-full sm:w-auto">
                    {{ __('app.manage_sessions') }}
                    <x-icon name="arrowRight" class="w-4 h-4" />
                </a>
                <a href="{{ route('mentor.profile.edit') }}" class="bg-white/10 hover:bg-white/20 text-white border border-white/20 px-6 py-2.5 rounded-full font-bold text-sm transition-colors flex items-center justify-center sm:justify-start gap-2 inline-flex w-full sm:w-auto">
                    {{ __('app.edit_profile') }}
                </a>
            </div>
        </div>

        <div class="relative z-10 mt-6 md:mt-0 hidden sm:block">
            <div class="w-32 h-32 md:w-40 md:h-40 rounded-full border-4 border-white/20 p-1 relative">
                @if(auth()->user()->profile_photo)
                <img decoding="async" loading="lazy" alt="" src="{{ auth()->user()->profile_photo }}" alt="Profile" class="w-full h-full rounded-full object-cover bg-red-900" fetchpriority="high">
                @else
                <div class="w-full h-full rounded-full bg-[#7f1d1d] flex items-center justify-center text-white text-3xl md:text-5xl font-bold">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                </div>
                @endif
                <div class="absolute bottom-2 right-2 w-5 h-5 bg-green-500 border-2 border-[#cc0000] rounded-full"></div>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
        <x-stat-card :label="__('app.my_courses')" :value="$stats['total_courses']" icon="award" color="blue" />
        <x-stat-card :label="__('app.total_students')" :value="$stats['total_students']" icon="users" color="green" />
        <x-stat-card :label="__('app.total_enrollments')" :value="$stats['total_enrollments']" icon="book" color="purple" />
        <x-stat-card :label="__('app.avg_rating')" :value="number_format($stats['avg_rating'], 1)" icon="starEmpty" suffix=" ⭐" color="amber" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- My Courses --}}
        <x-card-panel :title="__('app.my_courses')" :actionRoute="route('mentor.my-courses')">
            @if($myCourses->isEmpty())
                <x-empty-state :message="__('app.no_course')" icon="book" />
            @else
                <div class="space-y-3">
                    @foreach($myCourses as $course)
                        <div class="flex items-center justify-between p-3 rounded-2xl hover:bg-gray-50 transition-colors border border-transparent hover:border-gray-100 gap-3">
                            <div class="min-w-0">
                                <p class="text-[13px] font-bold text-gray-900 truncate">{{ $course->title }}</p>
                                <p class="text-[11px] text-gray-500 truncate">{{ $course->category }}</p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <p class="text-[12px] font-bold text-blue-600">{{ $course->enrollments_count ?? 0 }}</p>
                                <p class="text-[11px] text-gray-400">{{ __('app.student_lowercase') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-card-panel>

        {{-- Recent Students --}}
        <x-card-panel :title="__('app.recent_students')" :actionRoute="route('mentor.students')">
            @if($recentStudents->isEmpty())
                <x-empty-state :message="__('app.no_students')" icon="users" />
            @else
                <div class="space-y-3">
                    @foreach($recentStudents as $enrollment)
                        <div class="flex items-center justify-between p-3 rounded-2xl hover:bg-gray-50 transition-colors border border-transparent hover:border-gray-100 gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                @if($enrollment->user?->profile_photo)
                                    <img decoding="async" loading="lazy" alt="" src="{{ $enrollment->user->profile_photo }}" alt="{{ $enrollment->user->name }}" class="w-10 h-10 rounded-full object-cover flex-shrink-0">
                                @else
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-blue-600 font-bold">{{ substr($enrollment->user->name ?? 'U', 0, 1) }}</span>
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <p class="text-[13px] font-bold text-gray-900 truncate">{{ $enrollment->user->name ?? 'Unknown' }}</p>
                                    <p class="text-[11px] text-gray-500 truncate">{{ $enrollment->enrollable?->title ?? __('app.unknown_course') }}</p>
                                </div>
                            </div>
                            <p class="text-[11px] text-gray-400 flex-shrink-0">{{ $enrollment->created_at->diffForHumans() }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-card-panel>
    </div>

    {{-- Recent Ratings --}}
    <x-card-panel :title="__('app.recent_rating')" :actionRoute="route('mentor.feedback')">
        @if($recentRatings->isEmpty())
            <x-empty-state :message="__('app.no_rating')" icon="rating" />
        @else
            <div class="space-y-3">
                @foreach($recentRatings as $rating)
                    <div class="flex items-start gap-4 p-4 rounded-2xl hover:bg-gray-50 transition-colors border border-transparent hover:border-gray-100">
                        <div class="flex items-center gap-1">
                            @for($i = 1; $i <= 5; $i++)
                                <x-icon name="star" class="w-4 h-4 {{ $i <= $rating->rating ? 'text-amber-400' : 'text-gray-300' }}" />
                            @endfor
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[13px] text-gray-800">{{ $rating->review ?? __('app.no_review') }}</p>
                            <p class="text-[11px] text-gray-400 mt-1 truncate">
                                {{ $rating->user?->name ?? __('app.anonymous') }} - {{ $rating->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </x-card-panel>

</div>
@endsection
