@extends('layouts.mentor')

@section('title', __('app.course_detail'))
@section('header_title', $course->title)

@section('content')
    <x-flash-messages />
    <x-back-button route="{{ route('mentor.my-courses') }}" />

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
        <x-stat-card :label="__('app.total_students')" :value="$totalStudents" icon="users" color="blue" />
        <x-stat-card :label="__('app.completed')" :value="$completedStudents" icon="checkCircle" color="green" />
        <x-stat-card :label="__('app.avg_progress')" :value="$avgProgress . '%'" icon="barChart" color="purple" />
        <x-stat-card :label="__('app.rating')" :value="$avgRating" icon="star" color="amber" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Students --}}
        <x-card-panel :title="__('app.enrolled_students') . ' (' . $totalStudents . ')'">
            @forelse($enrolledStudents as $student)
                <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                    <div class="flex items-center gap-3">
                        @if($student['user']?->profile_photo)
                            <img decoding="async" loading="lazy" alt="" src="{{ $student['user']->profile_photo }}" class="w-10 h-10 rounded-full object-cover">
                        @else
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold">
                                {{ substr($student['user']->name ?? 'U', 0, 1) }}
                            </div>
                        @endif
                        <div>
                            <p class="font-medium text-gray-800">{{ $student['user']->name ?? 'Unknown' }}</p>
                            <p class="text-xs text-gray-400">{{ $student['last_activity']?->diffForHumans() ?? __('app.not_active_yet') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-blue-600">{{ $student['progress'] }}%</p>
                        <div class="w-24 h-2 bg-gray-200 rounded-full mt-1">
                            <div class="h-full bg-blue-500 rounded-full" style="width: {{ $student['progress'] }}%"></div>
                        </div>
                    </div>
                </div>
            @empty
                <x-empty-state :message="__('app.no_students')" icon="users" />
            @endforelse
        </x-card-panel>

        {{-- Ratings --}}
        <x-card-panel :title="__('app.rating_review')">
            @forelse($ratings as $rating)
                <div class="py-3 border-b border-gray-100 last:border-0">
                    <div class="flex items-center gap-2 mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 {{ $i <= $rating->rating ? 'text-amber-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                    <p class="text-sm text-gray-700">{{ $rating->review ?? __('app.no_review') }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ $rating->user?->name ?? __('app.anonymous') }}</p>
                </div>
            @empty
                <x-empty-state :message="__('app.no_rating')" icon="rating" />
            @endforelse
        </x-card-panel>
    </div>
@endsection
