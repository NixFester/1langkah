@php
    /** @var string $value  big stat value (top-right text) */
    /** @var string $label  muted label */
    /** @var string $change positive delta string (e.g. "+2 baru") */
    /** @var string $icon   optional icon name (renders inside a primary-bg square) */
    /** @var string $iconHtml  optional raw HTML icon (used by dashboard to pass pre-rendered icons) */
    /** @var string $color  color theme for icon (red, blue, green, purple, yellow, orange) */
    $value    = $value ?? '';
    $label    = $label ?? '';
    $change   = $change ?? '';
    $icon     = $icon ?? null;
    $iconHtml = $iconHtml ?? null;
    $color    = $color ?? 'red';

    if (! $iconHtml && $icon) {
        $iconHtml = view('components.icon', ['name' => $icon])->render();
    }
    
    $colorClasses = [
        'red' => 'bg-red-50 text-[#cc0000]',
        'blue' => 'bg-blue-50 text-blue-600',
        'green' => 'bg-green-50 text-green-600',
        'purple' => 'bg-purple-50 text-purple-600',
        'yellow' => 'bg-yellow-50 text-yellow-600',
        'orange' => 'bg-orange-50 text-orange-600',
    ];
    $iconTheme = $colorClasses[$color] ?? $colorClasses['red'];
@endphp
<div class="bg-white rounded-2xl p-4 sm:p-5 border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300">
    <div class="flex items-center justify-between mb-3">
        <div class="text-2xl sm:text-3xl font-extrabold text-gray-900">{{ $value }}</div>
        @if($iconHtml)
            <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ $iconTheme }}">
                {!! $iconHtml !!}
            </div>
        @endif
    </div>
    <div class="text-[12.5px] sm:text-[13px] font-medium text-gray-500 tracking-tight">{{ $label }}</div>
    @if($change)
        <div class="text-[11px] font-bold text-green-600 mt-2">{{ $change }}</div>
    @endif
</div>
