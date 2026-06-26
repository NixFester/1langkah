@php
    /** @var string $value  big stat value (top-right text) */
    /** @var string $label  muted label */
    /** @var string $change positive delta string (e.g. "+2 baru") */
    /** @var string $icon   optional icon name (renders inside a primary-bg square) */
    /** @var string $iconHtml  optional raw HTML icon (used by dashboard to pass pre-rendered icons) */
    $value    = $value ?? '';
    $label    = $label ?? '';
    $change   = $change ?? '';
    $icon     = $icon ?? null;
    $iconHtml = $iconHtml ?? null;

    if (! $iconHtml && $icon) {
        $iconHtml = view('components.icon', ['name' => $icon])->render();
    }
@endphp
<div class="stat-card">
    <div class="flex items-center justify-between">
        <div class="stat-value">{{ $value }}</div>
        @if($iconHtml)
            <div style="width:40px;height:40px;border-radius:10px;background:var(--primary-bg);display:flex;align-items:center;justify-content:center;color:var(--primary)">{!! $iconHtml !!}</div>
        @endif
    </div>
    <div class="stat-label">{{ $label }}</div>
    @if($change)
        <div class="stat-change">{{ $change }}</div>
    @endif
</div>
