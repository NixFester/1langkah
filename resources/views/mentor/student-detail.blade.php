@extends('layouts.mentor')

@section('title', __('app.student_detail'))
@section('header_title', $student->name)

@section('content')
    <x-flash-messages />
    <x-back-button route="{{ route('mentor.students') }}" />

    {{-- Student Info --}}
    <x-card-panel class="mb-6">
        <div class="flex items-center gap-4">
            @if($student->profile_photo)
                <img decoding="async" loading="lazy" alt="" src="{{ $student->profile_photo }}" class="w-16 h-16 rounded-full object-cover">
            @else
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold text-xl">
                    {{ substr($student->name, 0, 1) }}
                </div>
            @endif
            <div>
                <h2 class="text-xl font-bold text-gray-800">{{ $student->name }}</h2>
                <p class="text-gray-500">{{ $student->email }}</p>
            </div>
        </div>
    </x-card-panel>

    {{-- Progress Data --}}
    <div class="space-y-6">
        @forelse($progressData as $data)
            <x-card-panel :title="$data['course']->title">
                <div class="flex items-center justify-between mb-4">
                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">
                        {{ $data['progress_percent'] }}% {{ __('app.completed') }}
                    </span>
                </div>
                <div class="w-full h-3 bg-gray-200 rounded-full mb-4">
                    <div class="h-full bg-blue-500 rounded-full" style="width: {{ $data['progress_percent'] }}%"></div>
                </div>
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div>
                        <p class="text-2xl font-bold text-gray-800">{{ $data['completed_chapters'] }}</p>
                        <p class="text-xs text-gray-500">{{ __('app.completed_chapters') }}</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-800">{{ $data['total_chapters'] }}</p>
                        <p class="text-xs text-gray-500">{{ __('app.total_chapters') }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">{{ $data['last_activity']?->format('d/m/Y') ?? '-' }}</p>
                        <p class="text-xs text-gray-500">{{ __('app.last_active') }}</p>
                    </div>
                </div>
            </x-card-panel>
        @empty
            <x-card-panel>
                <x-empty-state :message="__('app.no_course_progress')" icon="book" />
            </x-card-panel>
        @endforelse
    </div>
@endsection
