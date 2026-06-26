@php
    /** @var string $text   button label */
    /** @var string $type   primary|outline|ghost|gold|white */
    /** @var string $size   '' | 'btn-sm' | 'btn-lg' | 'btn-full' | combos like 'btn-lg btn-full' */
    /** @var string $href   if provided, render <a> instead of <button> */
    /** @var string $icon   icon name to prepend */
    /** @var string $class  extra CSS classes */
    /** @var array  $attrs  extra HTML attributes as key=>value */
    $type  = $type ?? 'primary';
    $size  = $size ?? '';
    $icon  = $icon ?? null;
    $class = $class ?? '';
    $href  = $href ?? null;
    $attrs = $attrs ?? [];

    $attrStr = '';
    foreach ($attrs as $k => $v) {
        if (is_array($v)) {
            $v = implode(' ', $v);
        }
        $attrStr .= ' ' . e($k) . '="' . e($v) . '"';
    }

    $iconHtml = $icon ? '<span style="width:16px;height:16px;display:flex">' . view('components.icon', ['name' => $icon])->render() . '</span>' : '';
    $cls = trim("btn btn-{$type} {$size} {$class}");
@endphp
@if($href)
    <a href="{{ $href }}" class="{{ $cls }}" {{ $attrStr }}>{!! $iconHtml !!}{{ $slot }}</a>
@else
    <button type="{{ $type === 'submit' ? 'submit' : 'button' }}" class="{{ $cls }}" {{ $attrStr }}>{!! $iconHtml !!}{{ $slot }}</button>
@endif
