@php
    /**
     * Review Card Component
     *
     * @param \App\Models\CourseRating|\App\Models\Review $review
     */
    $review = $review ?? null;
@endphp

@if($review)
<div class="flex gap-4 p-4 bg-gray-50 rounded-xl">
    <!-- Avatar -->
    <div class="flex-shrink-0">
        @if($review->user && $review->user->profile_photo)
            <img src="{{ $review->user->profile_photo }}" alt="{{ $review->user->name }}"
                 class="w-10 h-10 rounded-full object-cover">
        @else
            <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center font-bold text-sm">
                {{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}
            </div>
        @endif
    </div>

    <!-- Content -->
    <div class="flex-1 min-w-0">
        <div class="flex items-center justify-between mb-1">
            <div class="flex items-center gap-2">
                <span class="font-semibold text-gray-900">{{ $review->user->name ?? 'Anonymous' }}</span>
                <div class="flex text-yellow-400">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-3.5 h-3.5 {{ $i <= $review->rating ? '' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                </div>
            </div>
            <span class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
        </div>
        @if($review->review_text)
            <p class="text-sm text-gray-600">{{ $review->review_text }}</p>
        @endif
    </div>
</div>
@endif
