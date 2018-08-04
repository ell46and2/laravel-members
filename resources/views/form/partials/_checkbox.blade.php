@if(isset($label))
<label class="text--color-blue">{{ $label }}</label>
@endif
<div class="custom-control custom-checkbox mt-1">
    <input 
        @if(isset($disabled) && $disabled)
            disabled
        @endif
        type="checkbox" 
        class="custom-control-input" 
        name="{{ $field }}" 
        value="1" 
        {{ old($field, $value) ? 'checked' : '' }}
        id="{{ $field }}"
    >
    <label class="custom-control-label text--color-blue-dark" for="{{ $field }}">{{ $name }}</label>
</div>
