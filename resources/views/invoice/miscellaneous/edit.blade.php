@extends('layouts.app')

@section('content')
<div class="container">
        
   
    <h2>Edit Misc</h2>
    <form method="POST" action="{{ route('invoice.misc.update', [$invoice, $invoiceLine]) }}">
        {{ csrf_field() }}
        @method('put')

            @include('form.partials._input', [
                'label' => 'Description',
                'field' => 'misc_name',
                'type' => 'text',
                'attributes' => 'required',
                'errors' => $errors,
                'value' => $invoiceLine->misc_name
            ])

            
            <div class="form-group row">
                <div class="form-control{{ $errors->has('misc_date') ? ' is-invalid' : '' }}">
                    <datepicker-component 
                        name="misc_date" 
                        placeholder="Select Date" 
                        old="{{ old('misc_date', $invoiceLine->misc_date->format('d/m/Y')) }}"
                    ></datepicker-component>
                </div>
                @if ($errors->has('misc_date'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('misc_date') }}</strong>
                    </span>
                @endif
            </div>

            @include('form.partials._input', [
                'label' => 'Amount',
                'field' => 'value',
                'type' => 'number',
                'attributes' => 'required',
                'errors' => $errors,
                'value' => $invoiceLine->value
            ])

             <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                        Update
                    </button>
                </div>
            </div>

    </form>
    
    
</div>
@endsection
