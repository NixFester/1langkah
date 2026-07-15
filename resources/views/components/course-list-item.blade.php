<!-- Course List Item Component -->
@props([
    'course' => [],
    'href' => null,
    'showProgress' => false,
    'showPrice' => false,
    'showRating' => false,
])

@php
$href = $href ?? ($course['id'] ? route('detail-kursus', $course['id']) : null);
$title = $course['title'] ?? '';
$subtitle = ($course['mentor'] ?? '') . ($course['mentor_company'] ? ' · ' . $course['mentor_company'] : '');
$thumbnail = $course['thumbnail'] ?? null;
$color = $course['color'] ?? '#dc2626';
$progress = $course['progress'] ?? 0;
$price = $course['formatted_price'] ?? $course['price'] ?? '';
$rating = $course['rating'] ?? $course['rating'] ?? 0;
$enrolledCount = $course['enrolledCount'] ?? $course['enrolled_count'] ?? $course['enrolled'] ?? 0;
$category = $course['category'] ?? '';
$level = $course['level'] ?? '';
@endphp

@if($href)
    <a href="{{ $href }}" class="flex gap-3 items-center hover:bg-gray-50 p-2 -mx-2 rounded-xl transition-colors">
@else
    <div class="flex gap-3 items-center">
@endif

    {{-- Thumbnail --}}
    <div class="w-16 h-12 rounded-lg bg-gray-100 flex-shrink-0 overflow-hidden">
        @if($thumbnail)
            <img decoding="async" loading="lazy" src="{{ $thumbnail }}" class="w-full h-full object-cover" alt="">
        @else
            <div class="w-full h-full flex items-center justify-center" style="background: linear-gradient(135deg, {{ $color }}, {{ $color }}cc);">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
        @endif
    </div>

    <div class="flex-1 min-w-0">
        {{-- Category Badge --}}
        @if($category)
            <span class="inline-block px-2 py-0.5 rounded-md text-[11px] font-bold text-red-600 bg-red-50 mb-1">{{ $category }}</span>
        @endif

        <h2 class="text-sm font-bold text-gray-900 truncate">{{ $title }}</h2>
        @if($subtitle)
            <p class="text-[11px] text-gray-500 truncate">{{ $subtitle }}</p>
        @endif

        {{-- Rating --}}
        @if($showRating && $rating > 0)
            <div class="flex items-center gap-1.5 mt-1">
                <div class="flex text-yellow-400">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-3 h-3 {{ $i <= floor($rating) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.922-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                </div>
                <span class="text-[10px] font-medium text-gray-600">{{ number_format($rating, 1) }}</span>
                <span class="text-[10px] text-gray-400">•</span>
                <span class="text-[10px] text-gray-500">{{ number_format($enrolledCount) }} {{ __('app.participants') }}</span>
            </div>
        @endif
    </div>

    {{-- Price or Progress --}}
    <div class="flex-shrink-0 text-right">
        @if($showProgress)
            <div class="text-right">
                <div class="h-1.5 w-24 bg-gray-100 rounded-full overflow-hidden mb-1">
                    <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $progress }}%"></div>
                </div>
                <p class="text-[10px] text-gray-500 font-medium">{{ $progress }}% {{ __('app.completed') }}</p>
            </div>
        @elseif($showPrice)
            <span class="text-sm font-bold {{ in_array($price, ['Gratis', 'Free', __('app.free')]) ? 'text-emerald-600' : 'text-gray-900' }}">
                {{ $price }}
            </span>
        @endif
    </div>

@if($href)
    </a>
@else
    </div>
@endif
