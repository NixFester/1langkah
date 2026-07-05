<!-- Admin Icon with Badge Component -->
@props([
    'icon',
    'bgColor' => 'bg-gray-100',
    'textColor' => 'text-gray-600',
])

<div class="w-12 h-12 {{ $bgColor }} rounded-xl flex items-center justify-center flex-shrink-0 {{ $textColor }}">
    <x-icon name="{{ $icon }}" class="w-6 h-6" />
</div>
