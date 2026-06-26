@php
    /** @var string $title      section title */
    /** @var string $linkText   if present, render "title →" link */
    /** @var string $linkHref   Laravel URL the link points to */
    $title    = $title ?? '';
    $linkText = $linkText ?? '';
    $linkHref = $linkHref ?? '';
@endphp
<div class="section-header">
    <div class="section-title">{{ $title }}</div>
    @if($linkText)
        <a class="section-link" href="{{ $linkHref }}">{{ $linkText }} &rarr;</a>
    @endif
</div>
