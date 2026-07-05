<!-- Data Table Component -->
@props([
    'paginator' => null,
])

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            {{ $slot }}
        </table>
    </div>

    @if($paginator && $paginator->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $paginator->links() }}
        </div>
    @endif
</div>
