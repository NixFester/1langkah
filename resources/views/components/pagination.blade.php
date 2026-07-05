@php
    /**
     * Pagination Component
     *
     * @param \Illuminate\Pagination\LengthAwarePaginator|\Illuminate\Contracts\Pagination\Paginator $paginator
     * @param array $params Additional URL params
     */
    $paginator = $paginator ?? null;
@endphp

@if($paginator && $paginator->hasPages())
    <div class="flex justify-center gap-2">
        {{-- Previous Page --}}
        @if($paginator->onFirstPage())
            <span class="px-3 py-1.5 rounded-full text-sm text-gray-400 bg-gray-100 cursor-not-allowed">
                &laquo; Prev
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
               class="px-3 py-1.5 rounded-full text-sm text-gray-600 bg-gray-100 hover:bg-gray-200 transition-colors">
                &laquo; Prev
            </a>
        @endif

        {{-- Page Numbers --}}
        @foreach($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
            @if($page == $paginator->currentPage())
                <span class="px-3 py-1.5 rounded-full text-sm text-white bg-red-600">{{ $page }}</span>
            @else
                <a href="{{ $url }}"
                   class="px-3 py-1.5 rounded-full text-sm text-gray-600 bg-gray-100 hover:bg-gray-200 transition-colors">
                    {{ $page }}
                </a>
            @endif
        @endforeach

        {{-- Next Page --}}
        @if($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
               class="px-3 py-1.5 rounded-full text-sm text-gray-600 bg-gray-100 hover:bg-gray-200 transition-colors">
                Next &raquo;
            </a>
        @else
            <span class="px-3 py-1.5 rounded-full text-sm text-gray-400 bg-gray-100 cursor-not-allowed">
                Next &raquo;
            </span>
        @endif
    </div>
@endif
