@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        
        @if(!$coach->hasOpenInvoice())
            <p>No current open invoice, so show create button</p>
            <form method="POST" action="{{ route('invoice.store', $coach) }}">
                {{ csrf_field() }}
                <button class="btn btn-primary" type="submit">Create Invoice</button>
            </form>  
            <br><br><br>       
        @endif

        

        @foreach($invoices as $invoice)
            @if($invoice->isOpen())
                <p>Current Invoice</p>
                <a href="{{ route('invoice.show', $invoice) }}" class="btn btn-primary">View</a>
            @else
                <p>{{ $invoice->label }}</p>
            @endif
            
        @endforeach
    </div>
</div>
@endsection
