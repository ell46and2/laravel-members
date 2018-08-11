@extends('layouts.base')

@section('content')

<div class="panel">
    <div class="panel__inner">
        <div class="panel__main">
            <h1 class="[ heading--1 ] [ mb-1 ]">Add Mileage to Invoice</h1>
            You can view your submitted invoices below, if you wish to create a new invoice
        </div>
    </div>
</div>

<form method="POST" action="{{ route('invoice.mileage.store', $invoice) }}">
    {{ csrf_field() }}

    <div class="panel">
        <div class="panel__inner">
            <div class="panel__header">
                <h2 class="panel__heading">
                    Enter mileage item
                </h2>
            </div>

            <div class="panel__main">
                <div class="row">
                    <div class="col-md-4">
                        @include('form.partials._input', [
                            'placeholder' => 'Enter Description...',
                            'label' => 'Description',
                            'field' => 'description',
                            'type' => 'text',
                            'attributes' => 'required',
                            'errors' => $errors
                        ])
                    </div>

                    <div class="col-md-4">
                        <dt>
                            <label class="text--color-blue" for="date">Date</label>
                        </dt>
                        <dd>
                           <datepicker-component name="mileage_date" placeholder="Select Date" old="{{ old('mileage_date') }}"></datepicker-component> 
                        </dd>
                        

                        @if ($errors->has('mileage_date'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('mileage_date') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="col-md-4">
                        @include('form.partials._input', [
                            'placeholder' => 'Enter Mileage...',
                            'label' => 'Miles',
                            'field' => 'miles',
                            'type' => 'number',
                            'attributes' => 'required',
                            'errors' => $errors
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button class="button button--success button--block" type="submit">Add to invoice</button>
</form>

@endsection
{{-- 
<div class="container">
        
   
    <h2>Mileage</h2>
    <form method="POST" action="{{ route('invoice.mileage.store', $invoice) }}">
        {{ csrf_field() }}

            @include('form.partials._input', [
                'label' => 'Description',
                'field' => 'description',
                'type' => 'text',
                'attributes' => 'required',
                'errors' => $errors
            ])

            
            <div class="form-group row">
                <div class="form-control{{ $errors->has('mileage_date') ? ' is-invalid' : '' }}">
                    <datepicker-component name="mileage_date" placeholder="Select Date" old="{{ old('mileage_date') }}"></datepicker-component>
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
                'errors' => $errors
            ])

             <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                        Add to Invoice
                    </button>
                </div>
            </div>

    </form>
    
    
</div>
@endsection
 --}}