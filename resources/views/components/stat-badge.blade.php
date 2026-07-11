<!-- Stat Badge Component -->
@props([
    'status' => null,
    'type' => null,
])

@php
    $config = match(true) {
        // Payment/verification status
        $status === 'pending' => ['bg-yellow-50', 'text-yellow-700', __('app.pending')],
        $status === 'approved' => ['bg-green-100', 'text-green-700', __('app.approved')],
        $status === 'rejected' => ['bg-red-100', 'text-red-700', __('app.rejected')],
        $status === 'active' => ['bg-green-100', 'text-green-700', __('app.active')],
        $status === 'inactive' => ['bg-gray-100', 'text-gray-700', __('app.inactive')],

        // Level badges
        $status === __('app.beginner') => ['bg-green-50', 'text-green-700', __('app.beginner')],
        $status === __('app.intermediate') => ['bg-blue-50', 'text-blue-700', __('app.intermediate')],
        $status === __('app.advanced') => ['bg-purple-50', 'text-purple-700', __('app.advanced')],

        // Event type badges
        $type === 'online' => ['bg-blue-50', 'text-blue-700', __('app.online')],
        $type === 'offline' => ['bg-purple-50', 'text-purple-700', __('app.offline')],
        $type === 'hybrid' => ['bg-green-50', 'text-green-700', __('app.hybrid')],

        // Event status badges
        $status === 'upcoming' => ['bg-yellow-50', 'text-yellow-700', __('app.upcoming')],
        $status === 'ongoing' => ['bg-blue-50', 'text-blue-700', __('app.ongoing')],
        $status === 'completed' => ['bg-green-50', 'text-green-700', __('app.completed')],
        $status === 'cancelled' => ['bg-red-50', 'text-red-700', __('app.cancelled')],
        $status === 'draft' => ['bg-gray-50', 'text-gray-700', __('app.draft')],

        // Promo status
        $status === 'expired' => ['bg-gray-100', 'text-gray-700', __('app.expired')],
        $status === 'maxed' => ['bg-red-50', 'text-red-700', __('app.maxed_out')],

        // Quiz types
        $type === 'pre_test' => ['bg-blue-50', 'text-blue-700', __('app.pre_test')],
        $type === 'post_test' => ['bg-purple-50', 'text-purple-700', __('app.post_test')],
        $type === 'chapter_quiz' => ['bg-orange-50', 'text-orange-700', __('app.chapter_quiz')],

        // Enrollment status
        $status === 'enrolled' => ['bg-blue-50', 'text-blue-700', __('app.registered')],

        // Default
        default => ['bg-gray-50', 'text-gray-700', $status ?? ucfirst($type ?? '')],
    };
@endphp

<span class="{{ $config[0] }} {{ $config[1] }} text-xs font-medium px-2.5 py-1 rounded-full capitalize">
    {{ $config[2] }}
</span>
