<!-- Gradient Stat Card Component -->
@props([
    'label' => '',
    'value' => '0',
    'from' => 'from-blue-500',
    'to' => 'to-blue-600',
    'prefix' => '',
    'suffix' => '',
])

<div class="bg-gradient-to-br {{ $from }} {{ $to }} rounded-xl p-6 text-white shadow-lg">
    <p class="text-sm mb-1 opacity-80">{{ $label }}</p>
    <p class="text-3xl font-bold mb-1">{!! $prefix !!}{{ $value }}{!! $suffix !!}</p>
    @if(isset($subtitle))
        <p class="text-sm opacity-80">{{ $subtitle }}</p>
    @endif
</div>
