<!-- Admin Page Header Component -->
<div class="bg-white rounded-2xl p-4 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-gray-100">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">{{ $title }}</h1>
        <p class="text-sm text-gray-500 mt-1">{{ $description ?? '' }}</p>
    </div>
    @if(isset($count))
        <span class="mt-2 sm:mt-0 bg-gray-100 text-gray-700 text-xs font-bold px-3 py-1.5 rounded-full">
            {{ $count }} {{ __('app.total') }}
        </span>
    @elseif(isset($actionRoute))
        <div class="mt-4 sm:mt-0 self-center sm:self-auto">
            <a href="{{ $actionRoute }}"
               class="bg-[#cc0000] hover:bg-red-700 text-white font-bold py-2.5 px-5 rounded-full text-sm transition-colors shadow-lg shadow-red-200 flex items-center gap-2">
                @if(isset($actionIcon))
                    {{ $actionIcon }}
                @else
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                @endif
                {{ $actionLabel ?? __('app.add') }}
            </a>
        </div>
    @elseif(isset($actionSlot))
        {{ $actionSlot }}
    @endif
</div>
