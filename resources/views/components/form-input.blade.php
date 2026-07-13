<!-- Admin Form Input Component -->
@props([
    'name',
    'label' => null,
    'type' => 'text',
    'required' => false,
    'placeholder' => null,
    'value' => null,
    'old' => null,
    'rows' => null,
    'help' => null,
    'error' => null,
])

<div>
    @if(isset($label))
        <label class="block text-sm font-bold text-gray-700 mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    @if($type === 'textarea')
        <textarea aria-label="{{ $placeholder }}" name="{{ $name }}"
                  @if($rows) rows="{{ $rows }}" @endif
                  placeholder="{{ $placeholder }}"
                  @if($required) required @endif
                  class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors resize-none">{{ old($name, $old ?? $value ?? '') }}</textarea>
    @elseif($type === 'select')
        <select aria-label="{{ $Name }}" name="{{ $name }}"
                @if($required) required @endif
                class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 cursor-pointer transition-colors">
            {{ $slot }}
        </select>
    @else
        <input aria-label="{{ $placeholder }}" type="{{ $type }}"
               name="{{ $name }}"
               value="{{ old($name, $old ?? $value ?? '') }}"
               placeholder="{{ $placeholder }}"
               @if($required) required @endif
               class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors">
    @endif

    @if(isset($help))
        <p class="text-xs text-gray-500 mt-1">{{ $help }}</p>
    @endif

    @if($errors->has($name))
        <p class="text-red-500 text-sm mt-1">{{ $errors->first($name) }}</p>
    @elseif(isset($error))
        <p class="text-red-500 text-sm mt-1">{{ $error }}</p>
    @endif
</div>
