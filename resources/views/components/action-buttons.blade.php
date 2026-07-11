<!-- Admin Action Buttons Component -->
@props([
    'editRoute' => null,
    'editLabel' => __('app.edit'),
    'deleteRoute' => null,
    'deleteLabel' => __('app.delete'),
    'deleteConfirm' => null,
    'manageRoute' => null,
    'manageLabel' => __('app.manage'),
    'customActions' => null,
])

<div class="flex items-center justify-end gap-2">
    {{-- Custom Actions Slot --}}
    @if(isset($customActions))
        {{ $customActions }}
    @endif

    {{-- Manage Button --}}
    @if(isset($manageRoute))
        <a href="{{ $manageRoute }}"
           class="inline-flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
            {{ $manageLabel }}
        </a>
    @endif

    {{-- Edit Button --}}
    @if(isset($editRoute))
        <a href="{{ $editRoute }}"
           class="inline-flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
            {{ $editLabel }}
        </a>
    @endif

    {{-- Delete Button --}}
    @if(isset($deleteRoute))
        <form method="POST" action="{{ $deleteRoute }}" class="m-0"
              onsubmit="return confirm('{{ $deleteConfirm ?? __('app.delete_confirm_default') }}')">
            @csrf @method('DELETE')
            <button type="submit"
                    class="inline-flex items-center justify-center bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                {{ $deleteLabel }}
            </button>
        </form>
    @endif
</div>
