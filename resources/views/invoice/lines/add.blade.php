@extends('layouts.app')

@section('content')
<div class="container">

		@if($invoiceables->count())
			<button class="btn btn-primary [ js-select-all ]">Select All</button>
			<button class="btn btn-primary [ js-unselect-all ]">Unselect All</button>
		@endif
    
    	<form method="POST" action="{{ route('invoice.add-lines', $invoice) }}">
    		{{ csrf_field() }}
			
			@foreach($invoiceables as $invoiceable)
				<input 
					class="js-invoiceable-checkbox"
					type="checkbox" 
					name="{{ $invoiceable->invoiceableGroup }}[]" 
					value="{{ $invoiceable->id }}"
				>
	        	<p>{{ $invoiceable->formattedType }}</p>
	        	<p>{{ $invoiceable->formattedStart }}</p>
	        	<br><br>
	        @endforeach

	        <button class="btn btn-primary" type="submit">Add</button>
    	</form>
</div>


@endsection

@section('script')

<script>




	
</script>

@endsection