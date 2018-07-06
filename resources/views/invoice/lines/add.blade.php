@extends('layouts.app')

@section('content')
<div class="container">
    
    	<form method="POST" action="{{ route('invoice.add-lines', $invoice) }}">
    		{{ csrf_field() }}
			
			@foreach($invoiceables as $invoiceable)
				<input type="checkbox" name="{{ $invoiceable->invoiceableGroup }}[]" value="{{ $invoiceable->id }}">
	        	<p>{{ $invoiceable->formattedType }}</p>
	        	<p>{{ $invoiceable->formattedStart }}</p>
	        	<br><br>
	        @endforeach

	        <button class="btn btn-primary" type="submit">Add</button>
    	</form>
        
        
      
   
</div>
@endsection