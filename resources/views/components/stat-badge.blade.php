<!-- Stat Badge Component -->
@props([
    'status' => null,
    'type' => null,
])

@php
    $config = match(true) {
        // Payment/verification status
        $status === 'pending' => ['bg-yellow-50', 'text-yellow-700', 'Menunggu'],
        $status === 'approved' => ['bg-green-100', 'text-green-700', 'Disetujui'],
        $status === 'rejected' => ['bg-red-100', 'text-red-700', 'Ditolak'],
        $status === 'active' => ['bg-green-100', 'text-green-700', 'Aktif'],
        $status === 'inactive' => ['bg-gray-100', 'text-gray-700', 'Nonaktif'],

        // Level badges
        $status === 'Beginner' => ['bg-green-50', 'text-green-700', 'Beginner'],
        $status === 'Intermediate' => ['bg-blue-50', 'text-blue-700', 'Intermediate'],
        $status === 'Advanced' => ['bg-purple-50', 'text-purple-700', 'Advanced'],

        // Event type badges
        $type === 'online' => ['bg-blue-50', 'text-blue-700', 'Online'],
        $type === 'offline' => ['bg-purple-50', 'text-purple-700', 'Offline'],
        $type === 'hybrid' => ['bg-green-50', 'text-green-700', 'Hybrid'],

        // Event status badges
        $status === 'upcoming' => ['bg-yellow-50', 'text-yellow-700', 'Upcoming'],
        $status === 'ongoing' => ['bg-blue-50', 'text-blue-700', 'Ongoing'],
        $status === 'completed' => ['bg-green-50', 'text-green-700', 'Selesai'],
        $status === 'cancelled' => ['bg-red-50', 'text-red-700', 'Dibatalkan'],
        $status === 'draft' => ['bg-gray-50', 'text-gray-700', 'Draft'],

        // Promo status
        $status === 'expired' => ['bg-gray-100', 'text-gray-700', 'Kadaluarsa'],
        $status === 'maxed' => ['bg-red-50', 'text-red-700', 'Habis Pakai'],

        // Quiz types
        $type === 'pre_test' => ['bg-blue-50', 'text-blue-700', 'Pre-Test'],
        $type === 'post_test' => ['bg-purple-50', 'text-purple-700', 'Post-Test'],
        $type === 'chapter_quiz' => ['bg-orange-50', 'text-orange-700', 'Chapter Quiz'],

        // Enrollment status
        $status === 'enrolled' => ['bg-blue-50', 'text-blue-700', 'Terdaftar'],

        // Default
        default => ['bg-gray-50', 'text-gray-700', $status ?? ucfirst($type ?? '')],
    };
@endphp

<span class="{{ $config[0] }} {{ $config[1] }} text-xs font-medium px-2.5 py-1 rounded-full capitalize">
    {{ $config[2] }}
</span>
