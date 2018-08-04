{{-- <div class="form-group row">
    @if(isset($label))
        <label for="{{ $field }}" class="col-md-4 col-form-label text-md-right">{{ $label }}</label>
    @endif
   
    <div class="col-md-6">
        <input 
            id="{{ $field }}" 
            type="{{ $type }}" 
            class="form-control{{ $errors->has($field) ? ' is-invalid' : '' }}" 
            name="{{ $field }}" 
            value="{{ $type !== 'password' ? old($field) ?? $value ?? '' : '' }}" {{ 
            $attributes ?? '' }}
            @if($type === 'number')
            step="any"
            @endif
            @if(isset($disabled) && $disabled)
                disabled 
            @endif
        >

        @if ($errors->has($field))
            <span class="invalid-feedback">
                <strong>{{ $errors->first($field) }}</strong>
            </span>
        @endif
    </div>
</div> --}}


<dt>
    @if(isset($label))
        <label for="{{ $field }}" class="text--color-blue">{{ $label }}</label>
    @endif
</dt>
<dd>
    <input 
        class="form-control mt-1{{ (isset($disabled) && $disabled) ? ' form-control--display-as-text' : '' }}" 
        id="{{ $field }}" 
        type="{{ $type }}" 
        name="{{ $field }}" 
        value="{{ $type !== 'password' ? old($field) ?? $value ?? '' : '' }}" {{ 
            $attributes ?? '' }}
        @if(isset($placeholder))
            placeholder="{{ $placeholder }}" 
        @endif
        @if($type === 'number')
        step="any"
        @endif
        @if(isset($disabled) && $disabled)
            disabled 
        @endif
    >

    @if ($errors->has($field))
        <span class="invalid-feedback">
            <strong>{{ $errors->first($field) }}</strong>
        </span>
    @endif
</dd>