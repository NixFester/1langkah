@php
    /** @var string $text   button label */
    /** @var string $type   primary|outline|ghost|gold|white */
    /** @var string $size   '' | 'sm' | 'lg' | 'full' | combos like 'lg full' */
    /** @var string $href   if provided, render <a> instead of <button> */
    /** @var string $icon   icon name to prepend */
    /** @var string $class  extra CSS classes */
    /** @var bool   $pill   rounded-full style (default true for primary) */
    /** @var array  $attrs  extra HTML attributes as key=>value */
    $type  = $type ?? 'primary';
    $size  = $size ?? '';
    $icon  = $icon ?? null;
    $class = $class ?? '';
    $href  = $href ?? null;
    $attrs = $attrs ?? [];
    $pill  = $pill ?? ($type === 'primary');

    $attrStr = '';
    foreach ($attrs as $k => $v) {
        if (is_array($v)) {
            $v = implode(' ', $v);
        }
        $attrStr .= ' ' . e($k) . '="' . e($v) . '"';
    }

    $iconHtml = $icon ? '<span style="width:16px;height:16px;display:flex">' . view('components.icon', ['name' => $icon])->render() . '</span>' : '';
    $pillClass = $pill ? 'rounded-full' : 'rounded-xl';
    $cls = trim("btn btn-{$type} {$size} {$pillClass} {$class}");
@endphp
@if($href)
    <a href="{{ $href }}" class="{{ $cls }}" {{ $attrStr }}>{!! $iconHtml !!}{{ $slot }}</a>
@else
    <button type="{{ $type === 'submit' ? 'submit' : 'button' }}" class="{{ $cls }}" {{ $attrStr }}>{!! $iconHtml !!}{{ $slot }}</button>
@endif
