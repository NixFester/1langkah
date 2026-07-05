@php
    /**
     * Empty State Component
     *
     * @param string $title      Empty state title
     * @param string $message    Description message
     * @param string $icon       Icon SVG path or null for default
     * @param string $actionUrl   Optional action URL
     * @param string $actionLabel Optional action label
     */
    $title = $title ?? 'Tidak Ada Data';
    $message = $message ?? 'Data tidak ditemukan.';
    $icon = $icon ?? null;
    $actionUrl = $actionUrl ?? null;
    $actionLabel = $actionLabel ?? 'Tambah Baru';
@endphp

<div class="bg-white border border-gray-100 rounded-2xl p-12 shadow-sm text-center">
    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
        @if($icon)
            {!! $icon !!}
        @else
            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        @endif
    </div>
    <h3 class="font-bold text-gray-900 text-xl mb-2">{{ $title }}</h3>
    <p class="text-gray-500 max-w-md mx-auto mb-6">{{ $message }}</p>
    @if($actionUrl)
        <a href="{{ $actionUrl }}"
           class="inline-flex items-center justify-center px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-full text-sm font-semibold transition-colors">
            {{ $actionLabel }}
        </a>
    @endif
</div>
