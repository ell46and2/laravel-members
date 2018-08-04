<dt>
	@include('form.partials._checkbox', [
		'label' => $checkLabel,
    	'field' => $checkField,
    	'value' => $checkValue,
    	'name' => $checkName,
    	'disabled' => $hideInput
  	])
</dt>
<dd>
    <textarea 
      class="form-control" 
      id="{{ $textareaField }}" 
      name="{{ $textareaField }}"
      placeholder="{{ $textareaPlaceholder ?? '' }}"
      @if(isset($disabled) && $disabled)
        disabled 
      @endif
      rows="8" 
      cols="80"
      >{{ old($textareaField, $textareaValue) }}</textarea>

      @if ($errors->has($textareaField))
        <span class="invalid-feedback">
            <strong>{{ $errors->first($textareaField) }}</strong>
        </span>
      @endif
</dd>