@props([
    'label',
    'name',
    'type'        => 'text',
    'value'       => '',
    'placeholder' => '',
    'required'    => false,
    'options'     => [],   // for type="select": ['val' => 'Label', ...]
    'rows'        => 3,    // for type="textarea"
    'span'        => 1,    // grid column span (1 or 2 or 3)
])
<div style="grid-column: span {{ $span }}">
    <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:4px;">
        {{ $label }}@if($required) <span style="color:#dc2626">*</span>@endif
    </label>
    @if($type === 'select')
        <select aria-label="{{ $Name }}" name="{{ $name }}" {{ $required ? 'required' : '' }}
            style="width:100%;padding:8px 12px;border:1px solid #e5e7eb;border-radius:6px;font-size:13px;background:#fff;">
            <option value="">-- {{ __('app.select') }} {{ $label }} --</option>
            @foreach($options as $val => $label)
                <option value="{{ $val }}" {{ old($name, $value) == $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    @elseif($type === 'textarea')
        <textarea aria-label="{{ $placeholder }}" name="{{ $name }}" rows="{{ $rows }}" placeholder="{{ $placeholder }}"
            style="width:100%;padding:8px 12px;border:1px solid #e5e7eb;border-radius:6px;font-size:13px;resize:vertical;">{{ old($name, $value) }}</textarea>
    @else
        <input aria-label="{{ $placeholder }}" type="{{ $type }}" name="{{ $name }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            style="width:100%;padding:8px 12px;border:1px solid #e5e7eb;border-radius:6px;font-size:13px;">
    @endif
</div>