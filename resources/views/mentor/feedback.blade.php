@extends('layouts.mentor')

@section('title', __('app.feedback_rating'))
@section('header_title', __('app.feedback_rating'))

@section('content')
    <x-flash-messages />

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-6 gap-4 mb-8">
        <div class="col-span-2 bg-gradient-to-br from-amber-400 to-amber-500 rounded-xl p-6 text-white">
            <p class="text-amber-100 text-sm mb-1">{{ __('app.avg_rating') }}</p>
            <p class="text-3xl sm:text-4xl font-bold flex items-center gap-2">
                {{ number_format($ratingStats['avg'], 1) }}
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
            </p>
        </div>
        @foreach([5, 4, 3, 2, 1] as $star)
            <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center">
                <div class="flex items-center justify-center gap-1 mb-1">
                    <span class="font-bold text-gray-800">{{ $star }}</span>
                    <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
                <p class="text-xl font-bold text-gray-800">{{ $ratingStats[$star . '_stars'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- Reviews List --}}
    <x-card-panel :title="__('app.reviews')">
        @forelse($ratings as $rating)
            <div class="p-6 border-b border-gray-100 last:border-0">
                <div class="flex items-start justify-between mb-2">
                    <div class="flex items-center gap-2">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 {{ $i <= $rating->rating ? 'text-amber-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                    <span class="text-xs text-gray-400">{{ $rating->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <p class="text-gray-800 mb-2">{{ $rating->review ?? __('app.no_review') }}</p>
                <div class="flex items-center gap-2">
                    @if($rating->user?->profile_photo)
                        <img decoding="async" loading="lazy" alt="" src="{{ $rating->user->profile_photo }}" class="w-6 h-6 rounded-full object-cover">
                    @endif
                    <span class="text-sm text-gray-600">{{ $rating->user?->name ?? __('app.anonymous') }}</span>
                    <span class="text-gray-300">•</span>
                    <span class="text-sm text-gray-500">{{ $rating->course?->title }}</span>
                </div>
            </div>
        @empty
            <x-empty-state :message="__('app.no_feedback')" icon="rating" />
        @endforelse
    </x-card-panel>

    @if($ratings->hasPages())
    <div class="mt-4">
        {{ $ratings->links() }}
    </div>
    @endif
@endsection
