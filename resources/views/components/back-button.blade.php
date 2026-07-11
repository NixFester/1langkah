<!-- Back Button Component -->
@props([
    'route' => null,
    'label' => __('app.back'),
    'theme' => 'gray',
])

@php
$themeClasses = match($theme) {
    'amber' => 'text-amber-600 hover:text-amber-700',
    'blue' => 'text-blue-600 hover:text-blue-700',
    'pink' => 'text-pink-600 hover:text-pink-700',
    default => 'text-gray-600 hover:text-gray-800',
};
@endphp

<a href="{{ $route ?? url()->previous() }}"
   class="inline-flex items-center gap-2 {{ $themeClasses }} mb-6 transition-colors">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
    </svg>
    {{ $label }}
</a>
