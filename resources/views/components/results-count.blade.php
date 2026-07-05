@php
    /**
     * Results Count Component
     *
     * @param int    $count    Number of results
     * @param string $label    Label (default: 'kursus')
     * @param string $model    Alpine.js model for dynamic count
     */
    $count = $count ?? 0;
    $label = $label ?? 'item';
    $model = $model ?? null;
@endphp

<div class="text-sm text-gray-500">
    Menampilkan
    <span class="font-semibold text-gray-900" {{ $model ? "x-text=\"{$model}.length\"" : '' }}>
        {{ $model ? '' : $count }}
    </span>
    {{ $label }}
</div>
