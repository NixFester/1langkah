@php
    /** @var string $text  badge label */
    /** @var string $type  primary|success|purple|blue|gold|dark|live */
    $type = $type ?? 'primary';
@endphp
<span class="badge badge-{{ $type }}">{{ $text }}</span>
