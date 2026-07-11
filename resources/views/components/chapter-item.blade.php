@php
    /**
     * Chapter Item Component
     *
     * @param array  $chapter       Chapter data
     * @param int    $index        Chapter number
     * @param bool   $isEnrolled   Whether user is enrolled
     * @param string $openModel    Alpine.js model for open state
     */
    $chapter = $chapter ?? [];
    $index = $index ?? 0;
    $isEnrolled = $isEnrolled ?? false;

    $isCompleted = $chapter['is_completed'] ?? false;
    $videos = $chapter['videos'] ?? [];
    $totalVideos = count($videos);
    $completedVideos = $chapter['completed_videos'] ?? 0;
@endphp

<div class="border border-gray-100 rounded-xl overflow-hidden"
     x-data="{ open: openChapter === {{ $index }} }">

    <!-- Chapter Header -->
    <div @click="open = !open; openChapter = open ? {{ $index }} : null"
         class="flex items-center justify-between p-4 bg-gray-50 cursor-pointer hover:bg-gray-100 transition-colors">

        <div class="flex items-center gap-3">
            <!-- Number/Check Badge -->
            <span class="w-8 h-8 rounded-full {{ $isCompleted ? 'bg-emerald-500 text-white' : 'bg-red-100 text-red-600' }} flex items-center justify-center text-sm font-bold">
                @if($isCompleted)
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                @else
                    {{ $index + 1 }}
                @endif
            </span>

            <div>
                <span class="font-medium text-gray-900">{{ $chapter['title'] }}</span>
                <span class="ml-2 text-xs text-gray-500 video-counter" data-total="{{ $totalVideos }}">
                    {{ $completedVideos }}/{{ $totalVideos }} {{ __('app.videos') }}
                </span>
            </div>
        </div>

        <div class="flex items-center gap-4 text-sm text-gray-500">
            @if($isCompleted)
                <span class="text-emerald-600 font-medium flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>{{ __('app.completed') }}</span>
            @endif
            <span>{{ $chapter['duration'] ?? '0h' }}</span>
            <svg :class="open ? 'rotate-180' : ''" class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
    </div>

    <!-- Videos List -->
    <div x-show="open" x-collapse class="border-t border-gray-100">
        <!-- Chapter Description -->
        @if(!empty($chapter['description']))
            <div class="p-4 bg-gray-50/50 border-b border-gray-100">
                <p class="text-sm text-gray-600 leading-relaxed">{{ $chapter['description'] }}</p>
            </div>
        @endif

        <!-- Videos -->
        @if(!empty($videos))
            @foreach($videos as $video)
                <x-video-item :video="$video" :chapter="$chapter" :is-enrolled="$isEnrolled" />
            @endforeach
        @else
            <div class="p-4 text-center text-gray-500 text-sm">
                {{ __('app.no_video_for_chapter') }}
            </div>
        @endif
    </div>
</div>
