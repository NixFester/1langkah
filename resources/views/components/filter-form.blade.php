<!-- Filter Form Component -->
@props([
    'method' => 'GET',
    'action' => null,
    'showExport' => false,
    'exportRoute' => null,
    'exportLabel' => 'Export',
])

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
    <form method="{{ $method }}" action="{{ $action }}" class="flex flex-wrap gap-4 items-end">
        {{ $slot }}

        <div class="flex items-center gap-2">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium text-sm transition-colors">
                Filter
            </button>
            @if(request()->hasAny(['status', 'date_from', 'date_to']))
                <a href="{{ url()->current() }}" class="px-4 py-2 text-gray-600 hover:text-gray-800 text-sm">
                    Reset
                </a>
            @endif
        </div>

        @if($showExport && isset($exportRoute))
            <div class="ml-auto">
                <a href="{{ $exportRoute }}"
                   class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium text-sm inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    {{ $exportLabel }}
                </a>
            </div>
        @endif
    </form>
</div>
