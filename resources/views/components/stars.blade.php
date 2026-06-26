@php
    /** @var float $rating  0–5 rating value */
    $rating = $rating ?? 0;
    $rounded = (int) round($rating);
@endphp
<div class="stars">
    @for($i = 1; $i <= 5; $i++)
        <span style="color:{{ $i <= $rounded ? '#ffb900' : '#e5e7eb' }};font-size:13px">&#9733;</span>
    @endfor
</div>
