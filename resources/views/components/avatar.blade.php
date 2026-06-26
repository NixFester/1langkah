@php
    /** @var string $initials  avatar initials (e.g. 'AK') */
    /** @var string $size      '' | 'avatar-sm' | 'avatar-lg' | 'avatar-xl' */
    /** @var string $style     optional inline style (e.g. background color) */
    /** @var string $class     optional extra classes */
    $size = $size ?? '';
    $style = $style ?? '';
    $class = $class ?? '';
@endphp
<div class="avatar {{ $size }} {{ $class }}" style="{{ $style }}">{{ $initials }}</div>
