<!-- User Avatar Component -->
@props([
    'user' => null,
    'name' => '',
    'photo' => null,
    'size' => 'md', // sm, md, lg, xl
    'class' => '',
    'bgColor' => 'purple', // purple, blue, green, red, amber, gray
])

@php
$photo = $photo ?? ($user?->profile_photo ?? null);
$name = $name ?? ($user?->name ?? 'U');
$initials = strtoupper(substr($name, 0, 2));

$sizeClasses = match($size) {
    'sm' => 'w-8 h-8 text-xs',
    'md' => 'w-10 h-10 text-sm',
    'lg' => 'w-12 h-12 text-base',
    'xl' => 'w-16 h-16 text-xl',
    '2xl' => 'w-20 h-20 text-2xl',
    default => 'w-10 h-10 text-sm',
};

$bgClasses = match($bgColor) {
    'blue' => 'bg-blue-100 text-blue-600',
    'green' => 'bg-green-100 text-green-600',
    'red' => 'bg-red-100 text-red-600',
    'amber' => 'bg-amber-100 text-amber-600',
    'gray' => 'bg-gray-100 text-gray-600',
    'purple' => 'bg-purple-100 text-purple-600',
    default => 'bg-purple-100 text-purple-600',
};
@endphp

@if($photo)
    <img src="{{ $photo }}"
         alt="{{ $name }}"
         class="rounded-full object-cover shadow-sm {{ $sizeClasses }} {{ $class }}">
@else
    <div class="rounded-full flex items-center justify-center font-bold {{ $sizeClasses }} {{ $bgClasses }} {{ $class }}">
        {{ $initials }}
    </div>
@endif
