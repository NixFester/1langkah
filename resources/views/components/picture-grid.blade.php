<!-- Admin Picture Grid Component -->
@props([
    'pictures' => [],
    'deleteRoute' => null,
])

@if(count($pictures) === 0)
    <div class="p-6 md:p-8 text-center">
        <p class="text-gray-500 text-sm">{{ __('app.no_pictures') }}</p>
    </div>
@else
    <div class="p-6 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
        @foreach($pictures as $picture)
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden relative group">
                <div class="aspect-video bg-gray-100">
                    <img decoding="async" loading="lazy" alt="" src="{{ $picture->url ?? $picture->image_url }}"
                         alt="{{ $picture->description ?? 'Image' }}"
                         class="w-full h-full object-cover"
                         onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 100 60%27%3E%3Crect fill=%27%23f3f4f6%27 width=%27100%27 height=%2760%27/%3E%3Ctext x=%2750%27 y=%2735%27 text-anchor=%27middle%27 fill=%27%239ca3af%27 font-family=%27sans-serif%27 font-size=%2712%27%3E{{ __('app.picture_not_found') }}%3C/text%3E%3C/svg%3E'">
                </div>
                <div class="p-2">
                    <span class="inline-block px-2 py-1 text-xs font-bold rounded-full
                        {{ ($picture->type ?? '') === 'thumbnail' ? 'bg-yellow-100 text-yellow-700' : 'bg-blue-100 text-blue-700' }}">
                        {{ ucfirst($picture->type ?? 'gallery') }}
                    </span>
                    @if($picture->description ?? false)
                        <p class="text-xs text-gray-500 mt-1 truncate">{{ $picture->description }}</p>
                    @endif
                </div>

                @if(isset($deleteRoute) || isset($picture->id))
                    <form method="POST"
                          action="{{ $deleteRoute ?? route('admin.pictures.destroy', $picture) }}"
                          onsubmit="return confirm('{{ __('app.delete_image_confirm') }}')"
                          class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        @csrf @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white p-1.5 rounded-full shadow-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </form>
                @endif
            </div>
        @endforeach
    </div>
@endif
