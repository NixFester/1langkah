@php
    /**
     * Video Item Component
     *
     * @param array  $video       Video data
     * @param array  $chapter     Parent chapter data
     * @param bool   $isEnrolled  Whether user is enrolled
     */
    $video = $video ?? [];
    $chapter = $chapter ?? [];
    $isEnrolled = $isEnrolled ?? false;

    $isCompleted = $video['is_completed'] ?? false;
@endphp

<div class="flex items-center gap-4 p-4 {{ !$isEnrolled ? 'opacity-60' : '' }} hover:bg-gray-50 transition-colors
    {{ $isCompleted ? 'video-completed' : '' }}"
     data-video-id="{{ $video['id'] }}"
     data-chapter-id="{{ $chapter['id'] ?? '' }}"
     data-course-id="{{ $course['id'] ?? '' }}"
     data-video-url="{{ $video['video_url'] ?? '' }}">

    @if($isEnrolled)
        <!-- Enrolled: clickable video -->
        <div class="flex items-center gap-4 flex-1 group video-item {{ $isCompleted ? 'cursor-default' : 'cursor-pointer' }}">
            <!-- Thumbnail -->
            <div class="w-24 h-14 rounded-lg flex items-center justify-center flex-shrink-0 overflow-hidden relative {{ $isCompleted ? 'bg-emerald-100' : 'bg-gray-200' }}">
                @if(!empty($video['thumbnail_url']))
                    <img decoding="async" loading="lazy" src="{{ $video['thumbnail_url'] }}" alt="{{ $video['title'] }}" class="w-full h-full object-cover">
                @endif
                <div class="absolute inset-0 flex items-center justify-center">
                    @if($isCompleted)
                        <div class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    @else
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    @endif
                </div>
            </div>

            <!-- Info -->
            <div class="flex-1 min-w-0">
                <p class="font-medium text-gray-900 group-hover:text-red-600 transition-colors truncate
                    {{ $isCompleted ? 'line-through text-gray-500' : '' }}">
                    {{ $video['title'] }}
                </p>
                <p class="text-xs text-gray-500">
                    @if($isCompleted)
                        <span class="text-emerald-600">{{ __('app.completed_watching') }}</span>
                    @else
                        {{ $video['duration'] ?? '' }}
                    @endif
                </p>
            </div>

            <!-- Status Icon -->
            @if($isCompleted)
                <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            @else
                <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                </svg>
            @endif
        </div>
    @else
        <!-- Not enrolled: locked video -->
        <div class="flex items-center gap-4 flex-1">
            <!-- Thumbnail with lock -->
            <div class="w-24 h-14 rounded-lg bg-gray-200 flex items-center justify-center flex-shrink-0 overflow-hidden relative">
                @if(!empty($video['thumbnail_url']))
                    <img decoding="async" loading="lazy" src="{{ $video['thumbnail_url'] }}" alt="{{ $video['title'] }}" class="w-full h-full object-cover blur-sm">
                @endif
                <div class="absolute inset-0 flex items-center justify-center">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
            </div>

            <!-- Info -->
            <div class="flex-1 min-w-0">
                <p class="font-medium text-gray-900 truncate">{{ $video['title'] }}</p>
                <p class="text-xs text-gray-500">{{ $video['duration'] ?? '' }}</p>
            </div>

            <!-- Lock Badge -->
            <span class="text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded-full flex-shrink-0">{{ __('app.locked') }}</span>
        </div>
    @endif
</div>
