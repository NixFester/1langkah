<!-- Activity List Component -->
@props([
    'activities' => [],
    'emptyMessage' => __('app.no_activities'),
    'iconColor' => 'gray',
])

@php
$colorClasses = match($iconColor) {
    'purple' => 'bg-purple-100 text-purple-600',
    'blue' => 'bg-blue-100 text-blue-600',
    'green' => 'bg-green-100 text-green-600',
    'amber' => 'bg-amber-100 text-amber-600',
    'red' => 'bg-red-100 text-red-600',
    default => 'bg-gray-100 text-gray-600',
};
@endphp

<div class="space-y-4">
    @forelse($activities as $activity)
        <div class="flex items-start gap-3 p-3 -mx-2 rounded-xl hover:bg-gray-50 transition-colors">
            <div class="w-2 h-2 rounded-full mt-2 flex-shrink-0" style="background-color: {{ $activity['color'] ?? '#6b7280' }}"></div>
            <div class="flex-1 min-w-0">
                <p class="text-sm text-gray-700 truncate">{{ $activity['text'] ?? $activity['description'] ?? '' }}</p>
                <p class="text-xs text-gray-400">{{ $activity['time'] ?? ($activity['created_at'] ?? '') }}</p>
            </div>
        </div>
    @empty
        <div class="text-center py-8 text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-sm">{{ $emptyMessage }}</p>
        </div>
    @endforelse
</div>
