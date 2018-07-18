<div class="form-group row">
    <label for="{{ $field }}" class="col-md-4 col-form-label text-md-right">{{ $label }}</label>

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
        >

        @if ($errors->has($field))
            <span class="invalid-feedback">
                <strong>{{ $errors->first($field) }}</strong>
            </span>
        @endif
    </div>
</div>