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

<div class="bg-gray-50 p-1.5 rounded-full flex w-full overflow-x-auto hide-scrollbar shadow-inner border border-gray-100/50 flex-nowrap">
    @foreach($tabs as $tab)
        <button @click="{{ $model }} = '{{ $tab['id'] }}'"
                :class="{{ $model }} === '{{ $tab['id'] }}'
                    ? 'bg-white text-red-600 shadow-sm border border-gray-100'
                    : 'text-gray-500 hover:text-gray-700'"
                class="flex-1 min-w-max px-4 sm:px-6 py-2.5 sm:py-3 rounded-full text-[13px] sm:text-sm font-semibold whitespace-nowrap transition-all">
            {{ $tab['label'] }}
        </button>
    @endforeach
</div>
<style>
.hide-scrollbar::-webkit-scrollbar { display: none; }
.hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
