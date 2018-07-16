@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

    	@if($coach->canCreateInvoice())
    		<form method="POST" action="{{ route('invoice.store', $coach) }}">
    			{{ csrf_field() }}
    			<button class="btn btn-primary" type="submit">Create Invoice</button>
    		</form>
    	@endif
        
        <table class="table">
            <thead>
                <th scope="col">Id</th>
                <th scope="col">Name</th>
                <th scope="col">Value</th>
                <th></th>
                <th></th>
            </thead>
        
            <tbody>
		    	@foreach($invoices as $invoice)
		    		@if($invoice->isOpen())
		    			<tr>
		    				<td>{{ $invoice->id }}</td>
		    				<td>Current</td>
		    				<td>£{{ $invoice->overallValue }}</td>
		    				<td><a href="{{ route('invoice.show', $invoice) }}" class="btn btn-primary">View</a></td>
		    			</tr>
		    		@else
		    			<tr>
		    				<td>{{ $invoice->id }}</td>
		    				<td>{{ $invoice->label }}</td>
		    				<td>£{{ $invoice->overallValue }}</td>
		    				<td><a href="{{ route('invoice.show', $invoice) }}" class="btn btn-primary">View</a></td>
		    			</tr>
		    		@endif
		    	@endforeach
		    </tbody>
		</table>
    </div>
</div>
@endsection
