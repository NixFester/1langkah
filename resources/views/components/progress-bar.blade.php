@php
    /** @var int    $pct   0–100 */
    /** @var string $color '' | 'green' | 'purple' | css color (custom via inline style) */
    $pct   = $pct ?? 0;
    $color = $color ?? '';
    $fillClass = in_array($color, ['green', 'purple']) ? $color : '';
    $fillStyle = ($color !== '' && ! in_array($color, ['green', 'purple'])) ? "background:{$color}" : '';
@endphp
<div class="progress-bar">
    <div class="progress-fill {{ $fillClass }}" style="width:{{ $pct }}%;{{ $fillStyle }}"></div>
</div>
