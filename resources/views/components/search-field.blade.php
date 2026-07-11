<!-- Search Field Component -->
@props([
    'name' => 'search',
    'value' => '',
    'placeholder' => __('app.search_placeholder_short'),
    'model' => null, // Alpine.js x-model binding
])

@php
$modelAttr = $model ? 'x-model="' . e($model) . '"' : '';
@endphp

<div class="relative flex-1">
    <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
    </svg>
    <input
        type="text"
        name="{{ $name }}"
        value="{{ $value }}"
        placeholder="{{ $placeholder }}"
        {{ $modelAttr }}
        class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
    >
</div>
