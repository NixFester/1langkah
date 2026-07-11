@props(['count' => 0, 'label' => __('app.item'), 'model' => null])
@php
    /**
     * Results Count Component
     *
     * @param int    $count    Number of results
     * @param string $label    Label (default: 'kursus')
     * @param string $model    Alpine.js model for dynamic count
     */
@endphp

<div {{ $attributes->merge(['class' => 'text-sm text-gray-500 mb-6']) }}>
    {{ __('app.showing') }}
    <span class="font-semibold text-gray-900" {!! $model ? "x-text=\"{$model}.length\"" : "" !!}>
        {{ $model ? '' : $count }}
    </span>
    {{ $label }}
</div>
