@php
    /**
     * Tab Navigation Component
     *
     * @param array  $tabs      Array of ['id' => 'tabId', 'label' => 'Tab Label']
     * @param string $model     Alpine.js model (default: 'activeTab')
     * @param string $active    Default active tab
     */
    $tabs = $tabs ?? [];
    $model = $model ?? 'activeTab';
    $active = $active ?? '';
@endphp

<div class="bg-gray-50 p-1.5 rounded-full flex w-full overflow-x-auto scrollbar-hide shadow-inner border border-gray-100/50">
    @foreach($tabs as $tab)
        <button @click="{{ $model }} = '{{ $tab['id'] }}'"
                :class="{{ $model }} === '{{ $tab['id'] }}'
                    ? 'bg-white text-red-600 shadow-sm border border-gray-100'
                    : 'text-gray-500'"
                class="flex-1 px-6 py-3 rounded-full text-sm font-semibold whitespace-nowrap transition-all">
            {{ $tab['label'] }}
        </button>
    @endforeach
</div>
