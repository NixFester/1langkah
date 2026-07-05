@php
    /**
     * Category Pills Component
     *
     * @param array  $categories  List of category names
     * @param string $model      Alpine.js model for active category (default: 'activeCat')
     * @param string $active     Currently active category (default: 'All')
     */
    $categories = $categories ?? [];
    $model = $model ?? 'activeCat';
    $active = $active ?? 'All';
@endphp

<div class="flex items-center gap-3 overflow-x-auto pb-2">
    {{-- All Button --}}
    <button @click="{{ $model }} = 'All'"
            :class="{{ $model }} === 'All'
                ? 'text-white shadow-sm'
                : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'"
            :style="{{ $model }} === 'All'
                ? 'background-color: #dc2626; padding: 8px 20px; border-radius: 9999px; font-size: 14px; font-weight: 500;'
                : 'padding: 8px 20px; border-radius: 9999px; font-size: 14px; font-weight: 500;'"
            class="py-2 transition-colors whitespace-nowrap cursor-pointer">
        All
    </button>

    {{-- Category Buttons --}}
    @foreach($categories as $cat)
        <button @click="{{ $model }} = '{{ $cat }}'"
                :class="{{ $model }} === '{{ $cat }}'
                    ? 'text-white shadow-sm'
                    : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'"
                :style="{{ $model }} === '{{ $cat }}'
                    ? 'background-color: #dc2626; padding: 8px 20px; border-radius: 9999px; font-size: 14px; font-weight: 500;'
                    : 'padding: 8px 20px; border-radius: 9999px; font-size: 14px; font-weight: 500;'"
                class="py-2 transition-colors whitespace-nowrap cursor-pointer">
            {{ $cat }}
        </button>
    @endforeach
</div>
