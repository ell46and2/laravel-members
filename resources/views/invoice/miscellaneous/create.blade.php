@extends('layouts.base')

@section('content')

<div class="panel">
    <div class="panel__inner">
        <div class="panel__main">
            <h1 class="[ heading--1 ] [ mb-1 ]">Add Miscellaneous to Invoice</h1>
            You can view your submitted invoices below, if you wish to create a new invoice
        </div>
    </div>
</div>

<form method="POST" action="{{ route('invoice.misc.store', $invoice) }}">
    {{ csrf_field() }}

    <div class="panel">
        <div class="panel__inner">
            <div class="panel__header">
                <h2 class="panel__heading">
                    Enter Miscellaneous item
                </h2>
            </div>

            <div class="panel__main">
                <div class="row">
                    <div class="col-md-4">
                        @include('form.partials._input', [
                            'placeholder' => 'Enter Description...',
                            'label' => 'Description',
                            'field' => 'misc_name',
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
                           <datepicker-component name="misc_date" placeholder="Select Date" old="{{ old('misc_date') }}"></datepicker-component> 

                            @if($errors->has('misc_date'))
                               <span class="invalid-feedback">
                                   <strong>{{ $errors->first('misc_date') }}</strong>
                               </span>
                            @endif
                        </dd>
 
                    </div>

                    <div class="col-md-4">
                        @include('form.partials._input', [
                            'placeholder' => 'Enter Amount...',
                            'label' => 'Amount',
                            'field' => 'value',
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
        
   
    <h2>Misc</h2>
    <form method="POST" action="{{ route('invoice.misc.store', $invoice) }}">
        {{ csrf_field() }}

            @include('form.partials._input', [
                'label' => 'Description',
                'field' => 'misc_name',
                'type' => 'text',
                'attributes' => 'required',
                'errors' => $errors,
            ])

            
            <div class="form-group row">
                <div class="form-control{{ $errors->has('misc_date') ? ' is-invalid' : '' }}">
                    <datepicker-component name="misc_date" placeholder="Select Date" old="{{ old('misc_date') }}"></datepicker-component>
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
@endsection --}}
