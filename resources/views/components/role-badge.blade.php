<!-- Role Badge Component -->
@props([
    'role' => null,
    'color' => null,
])

@php
$config = match($role ?? '') {
    'superadmin' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'label' => 'Superadmin'],
    'admin' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Admin'],
    'keuangan' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'label' => 'Keuangan'],
    'marketing' => ['bg' => 'bg-pink-100', 'text' => 'text-pink-700', 'label' => 'Marketing'],
    'mentor' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'Mentor'],
    'student' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Student'],
    default => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'label' => ucfirst($role ?? '')],
};
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize {{ $config['bg'] }} {{ $config['text'] }}">
    {{ $config['label'] }}
</span>
