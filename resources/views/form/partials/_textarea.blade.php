<dt>
    @if(isset($label))
    <label class="text--color-blue">{{ $label }}</label>
    @endif
</dt>
<dd>
    <textarea 
      class="form-control" 
      id="{{ $field }}" 
      name="{{ $field }}"
      placeholder="{{ $placeholder ?? '' }}"
      @if(isset($disabled) && $disabled)
        disabled 
      @endif
      rows="8" 
      cols="80"  
      placeholder="Text Details"
      >{{ old($field, $value) }}</textarea>

      @if ($errors->has($field))
        <span class="invalid-feedback">
            <strong>{{ $errors->first($field) }}</strong>
        </span>
      @endif
</dd>