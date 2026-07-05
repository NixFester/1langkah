@php
    /**
     * Star Rating Input Component
     *
     * @param int    $name        Input name
     * @param int    $value       Current rating value
     * @param string $size         Size: sm|md|lg (default: md)
     */
    $name = $name ?? 'rating';
    $value = $value ?? 0;
    $size = $size ?? 'md';

    $sizes = [
        'sm' => 'text-xl',
        'md' => 'text-2xl',
        'lg' => 'text-3xl',
    ];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

<div class="flex items-center gap-1 rating-input" data-name="{{ $name }}">
    @for($i = 1; $i <= 5; $i++)
        <button type="button"
                onclick="setRating({{ $i }})"
                class="star-btn {{ $sizeClass }} text-gray-300 hover:text-yellow-400 transition-colors"
                data-rating="{{ $i }}">
            ★
        </button>
    @endfor
    <input type="hidden" name="{{ $name }}" id="ratingValue" value="{{ $value }}">
</div>

@once
@push('scripts')
<script>
    let selectedRating = 0;

    function setRating(rating) {
        selectedRating = rating;
        document.querySelectorAll('.star-btn').forEach((btn, index) => {
            btn.classList.toggle('text-yellow-400', index < rating);
            btn.classList.toggle('text-gray-300', index >= rating);
        });
        const input = document.getElementById('ratingValue');
        if (input) input.value = rating;
    }

    // Initialize if there's an existing rating
    document.addEventListener('DOMContentLoaded', function() {
        const existingRating = parseInt(document.getElementById('ratingValue')?.value || 0);
        if (existingRating > 0) {
            setRating(existingRating);
        }
    });
</script>
@endpush
@endonce
