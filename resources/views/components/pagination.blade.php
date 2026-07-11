@php
    $paginator = $paginator ?? null;
    // If $elements is not passed (e.g. called directly), generate it.
    if (!isset($elements) && $paginator instanceof \Illuminate\Pagination\LengthAwarePaginator) {
        $window = \Illuminate\Pagination\UrlWindow::make($paginator);
        $elements = array_filter([
            $window['first'],
            is_array($window['slider']) ? '...' : null,
            $window['slider'],
            is_array($window['last']) ? '...' : null,
            $window['last'],
        ]);
        // Flatten
        $flatElements = [];
        foreach ($elements as $element) {
            if (is_string($element)) {
                $flatElements[] = $element;
            } else {
                $flatElements[] = $element;
            }
        }
        $elements = $flatElements;
    }
@endphp

@if ($paginator && $paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex flex-col sm:flex-row items-center justify-between gap-4 w-full">
        <div class="flex justify-between w-full sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="px-4 py-2 text-sm font-bold text-gray-400 bg-gray-50 border border-gray-200 cursor-not-allowed rounded-xl w-1/2 text-center mr-2">
                    &laquo; {{ __('app.prev') }}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="px-4 py-2 text-sm font-bold text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl transition-colors w-1/2 text-center mr-2">
                    &laquo; {{ __('app.prev') }}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="px-4 py-2 text-sm font-bold text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl transition-colors w-1/2 text-center ml-2">
                    {{ __('app.next') }} &raquo;
                </a>
            @else
                <span class="px-4 py-2 text-sm font-bold text-gray-400 bg-gray-50 border border-gray-200 cursor-not-allowed rounded-xl w-1/2 text-center ml-2">
                    {{ __('app.next') }} &raquo;
                </span>
            @endif
        </div>

        <div class="hidden sm:flex flex-1 items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">
                    Showing <span class="font-bold text-gray-900">{{ $paginator->firstItem() }}</span> to <span class="font-bold text-gray-900">{{ $paginator->lastItem() }}</span> of <span class="font-bold text-gray-900">{{ $paginator->total() }}</span> results
                </p>
            </div>
            
            <div class="flex items-center gap-1">
                @if ($paginator->onFirstPage())
                    <span class="w-10 h-10 flex items-center justify-center rounded-xl text-gray-400 bg-gray-50 cursor-not-allowed">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" class="w-10 h-10 flex items-center justify-center rounded-xl text-gray-600 bg-gray-50 hover:bg-gray-200 hover:text-gray-900 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                @endif

                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="w-10 h-10 flex items-center justify-center text-gray-400 font-bold">...</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-red-600 text-white font-bold shadow-md shadow-red-200">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="w-10 h-10 flex items-center justify-center rounded-xl text-gray-600 bg-gray-50 hover:bg-gray-200 hover:text-gray-900 font-bold transition-colors">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="w-10 h-10 flex items-center justify-center rounded-xl text-gray-600 bg-gray-50 hover:bg-gray-200 hover:text-gray-900 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                @else
                    <span class="w-10 h-10 flex items-center justify-center rounded-xl text-gray-400 bg-gray-50 cursor-not-allowed">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </span>
                @endif
            </div>
        </div>
    </nav>
@endif
