@extends('layouts.mentor')

@section('title', __('app.my_courses'))
@section('header_title', __('app.my_courses'))

@section('content')
    <x-flash-messages />

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($courses as $course)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-medium">{{ $course->category }}</span>
                        <span class="text-xs text-gray-400">{{ $course->chapters?->count() ?? 0 }} {{ __('app.chapter_lowercase') }}</span>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">{{ $course->title }}</h3>
                    <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ $course->short_description ?? $course->description }}</p>
                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <div class="text-center">
                            <p class="text-xl font-bold text-blue-600">{{ $course->enrollments_count ?? 0 }}</p>
                            <p class="text-xs text-gray-400">{{ __('app.student') }}</p>
                        </div>
                        <a href="{{ route('mentor.course-detail', $course) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                            {{ __('app.detail') }}
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12 text-gray-400">
                <x-empty-state
                    :message="__('app.no_course')"
                    icon="book"
                />
                <p class="text-sm mt-2">{{ __('app.contact_admin_add_course') }}</p>
            </div>
        @endforelse
    </div>

    @if($courses->hasPages())
    <div class="mt-6">
        {{ $courses->links() }}
    </div>
    @endif
@endsection
