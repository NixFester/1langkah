@php
    /**
     * Search & Filter Bar Component
     *
     * @param string $placeholder   Placeholder text
     * @param string $searchModel   Alpine.js model for search (default: 'searchQuery')
     * @param string $sortModel     Alpine.js model for sort (default: 'sortBy')
     * @param array  $sortOptions   Sort options as ['value' => 'Label']
     * @param array  $filters       Array of filter groups
     * @param bool   $showFilters  Show filter panel by default
     */
    $placeholder = $placeholder ?? __('app.search') . '...';
    $searchModel = $searchModel ?? 'searchQuery';
    $sortModel = $sortModel ?? 'sortBy';
    $sortOptions = $sortOptions ?? [
        'newest' => __('app.newest'),
        'rating' => __('app.highest_rating'),
        'price_low' => __('app.price_low_high'),
        'price_high' => __('app.price_high_low'),
    ];
    $filters = $filters ?? [];
    $showFilters = $showFilters ?? false;
@endphp

<div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
    <div class="flex flex-col md:flex-row gap-4">
        <!-- Search Input -->
        <div class="flex-1 relative">
            <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text"
                   x-model="{{ $searchModel }}"
                   placeholder="{{ $placeholder }}"
                   class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
        </div>

        <!-- Sort Dropdown -->
        <div class="relative">
            <select x-model="{{ $sortModel }}"
                    class="appearance-none bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 pr-10 text-sm text-gray-700 focus:ring-2 focus:ring-red-500 focus:border-red-500 cursor-pointer">
                @foreach($sortOptions as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
            <svg class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>

        <!-- Filter Toggle (if filters provided) -->
        @if(!empty($filters))
            <button @click="showFilter = !showFilter"
                    :class="showFilter ? 'bg-red-600 text-white border-red-600' : 'bg-white text-gray-700 border-gray-200'"
                    class="px-4 py-3 border rounded-xl text-sm font-medium flex items-center gap-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                {{ __('app.filter') }}
            </button>
        @endif
    </div>

    <!-- Filter Panel -->
    @if(!empty($filters))
        <div x-show="showFilter" x-collapse class="mt-4 pt-4 border-t border-gray-100">
            <div class="flex flex-col gap-4">
                @foreach($filters as $filter)
                    <div>
                        @if(!empty($filter['label']))
                            <label class="block text-xs font-medium text-gray-500 mb-2">{{ $filter['label'] }}</label>
                        @endif
                        <div class="flex flex-wrap gap-2">
                            @foreach($filter['options'] as $option)
                                <button @click="{{ $filter['model'] }} = '{{ $option['value'] }}'"
                                        :class="{{ $filter['model'] }} === '{{ $option['value'] }}' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                                        class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors">
                                    {{ $option['label'] }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
