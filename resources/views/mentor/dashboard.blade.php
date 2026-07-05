@extends('layouts.mentor')

@section('title', 'Dashboard Mentor')
@section('header_title', 'Dashboard Mentor')

@section('content')
    <x-flash-messages />

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-stat-card label="Kursus Saya" :value="$stats['total_courses']" icon="book" color="blue" />
        <x-stat-card label="Total Siswa" :value="$stats['total_students']" icon="users" color="green" />
        <x-stat-card label="Total Enrollments" :value="$stats['total_enrollments']" icon="shield" color="purple" />
        <x-stat-card label="Rating Rata-rata" :value="number_format($stats['avg_rating'], 1)" suffix=" ⭐" color="amber" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- My Courses --}}
        <x-card-panel title="Kursus Saya" :actionRoute="route('mentor.my-courses')">
            @if($myCourses->isEmpty())
                <x-empty-state message="Belum ada kursus" icon="book" />
            @else
                <div class="space-y-4">
                    @foreach($myCourses as $course)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-800">{{ $course->title }}</p>
                                <p class="text-sm text-gray-500">{{ $course->category }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-blue-600">{{ $course->enrollments_count ?? 0 }}</p>
                                <p class="text-xs text-gray-400">siswa</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-card-panel>

        {{-- Recent Students --}}
        <x-card-panel title="Siswa Terbaru" :actionRoute="route('mentor.students')">
            @if($recentStudents->isEmpty())
                <x-empty-state message="Belum ada siswa" icon="users" />
            @else
                <div class="space-y-4">
                    @foreach($recentStudents as $enrollment)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                @if($enrollment->user?->profile_photo)
                                    <img src="{{ $enrollment->user->profile_photo }}" alt="{{ $enrollment->user->name }}" class="w-10 h-10 rounded-full object-cover">
                                @else
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <span class="text-blue-600 font-bold">{{ substr($enrollment->user->name ?? 'U', 0, 1) }}</span>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-medium text-gray-800">{{ $enrollment->user->name ?? 'Unknown' }}</p>
                                    <p class="text-sm text-gray-500">{{ $enrollment->enrollable?->title ?? 'Unknown Course' }}</p>
                                </div>
                            </div>
                            <p class="text-xs text-gray-400">{{ $enrollment->created_at->diffForHumans() }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-card-panel>
    </div>

    {{-- Recent Ratings --}}
    <x-card-panel title="Rating Terbaru" :actionRoute="route('mentor.feedback')" class="mt-6">
        @if($recentRatings->isEmpty())
            <x-empty-state message="Belum ada rating" icon="rating" />
        @else
            <div class="space-y-4">
                @foreach($recentRatings as $rating)
                    <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-2">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= $rating->rating ? 'text-amber-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-800">{{ $rating->review ?? 'Tanpa review' }}</p>
                            <p class="text-xs text-gray-400 mt-1">
                                {{ $rating->user?->name ?? 'Anonymous' }} - {{ $rating->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </x-card-panel>
@endsection
