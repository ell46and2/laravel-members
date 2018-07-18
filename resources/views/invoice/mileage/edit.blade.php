@extends('layouts.app')

@section('content')
<div class="container">
        
   
    <h2>Edit Mileage</h2>
    <form method="POST" action="{{ route('invoice.mileage.update', [$invoice, $mileage]) }}">
        {{ csrf_field() }}
        @method('put')

            @include('form.partials._input', [
                'label' => 'Description',
                'field' => 'description',
                'type' => 'text',
                'attributes' => 'required',
                'errors' => $errors,
                'value' => $mileage->description
            ])

            
            <div class="form-group row">
                <div class="form-control{{ $errors->has('mileage_date') ? ' is-invalid' : '' }}">
                    <datepicker-component name="mileage_date" placeholder="Select Date" old="{{ old('mileage_date', $mileage->mileage_date->format('d/m/Y')) }}"></datepicker-component>
                </div>
                @if ($errors->has('mileage_date'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('mileage_date') }}</strong>
                    </span>
                @endif
            </div>

            @include('form.partials._input', [
                'label' => 'Miles',
                'field' => 'miles',
                'type' => 'number',
                'attributes' => 'required',
                'errors' => $errors,
                'value' => $mileage->miles
            ])

             <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                        Update Mileage
                    </button>
                </div>
            </div>
                 
    </form>
    
    
</div>
@endsection
