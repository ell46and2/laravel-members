@extends('layouts.base')

@section('content')

<div class="panel">
    <div class="panel__inner">
        <div class="panel__main">
            <h1 class="[ heading--1 ] [ mb-1 ]">Invoicing</h1>
            You can view your invoices below
        </div>
        <div class="panel__alert panel__alert--has-icon">
            <div>
            	@svg( 'info-circle', 'icon')
                {{ daysToSubmitInvoice() }}</span> {{ str_plural('day', daysToSubmitInvoice()) }} left to submit invoice for {{ currentInvoicingMonth() }}'s invoicing period
            </div>
        </div>
    </div>
</div>

@if($coach->canCreateInvoice())
	<form method="POST" action="{{ route('invoice.store', $coach) }}">
		{{ csrf_field() }}
		<button class="button button--primary button--block" type="submit">Create Invoice</button>
	</form>
@endif

<div class="panel">
    <div class="panel__inner">
        <div class="panel__header">
            <h2 class="panel__heading">
                Invoice archive
            </h2>
        </div>

        <div class="panel__main">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Invoice number</th>
                        <th>Invoice name</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Amount</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                	@foreach($invoices as $invoice)
                		@if($invoice->isOpen())
                			<tr>
                				<td>
                				<a class="table__link" href="{{ route('invoice.show', $invoice) }}">{{ $invoice->id }}</a></td>
                				<td><a class="table__link" href="{{ route('invoice.show', $invoice) }}">Current</a></td>
                				<td>-</td>
                				<td>Open</td>
                				<td>£{{ $invoice->overallValue }}</td>
                				<td><a href="{{ route('invoice.show', $invoice) }}" class="btn btn-primary">Update</a></td>
                			</tr>
                		@else
                			<tr>
                				<td><a class="table__link" href="{{ route('invoice.show', $invoice) }}">{{ $invoice->id }}</a></td>
                				<td><a class="table__link" href="{{ route('invoice.show', $invoice) }}">{{ $invoice->label }}</a></td>
            					<td>{{ $invoice->submittedDate }}</td>
                				<td>{{ $invoice->formattedStatus }}</td>
                				<td>£{{ $invoice->overallValue }}</td>
                				<td><a href="{{ route('invoice.show', $invoice) }}" class="btn btn-primary">View</a></td>
                			</tr>
                		@endif
                	@endforeach
                </tbody>
            </table>
            
            {{ $invoices->links() }}
    </div>
</div>
@endsection
{{-- 
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
                <th scope="col">Invoice Number</th>
                <th scope="col">Name</th>
                <th scope="col">Submitted Date</th>
                <th scope="col">Status</th>
                <th scope="col">Amount</th>
                <th></th>
            </thead>
        
            <tbody>
		    	@foreach($invoices as $invoice)
		    		@if($invoice->isOpen())
		    			<tr>
		    				<td>{{ $invoice->id }}</td>
		    				<td>Current</td>
		    				<td>-</td>
		    				<td>Open</td>
		    				<td>£{{ $invoice->overallValue }}</td>
		    				<td><a href="{{ route('invoice.show', $invoice) }}" class="btn btn-primary">Update</a></td>
		    			</tr>
		    		@else
		    			<tr>
		    				<td>{{ $invoice->id }}</td>
		    				<td>{{ $invoice->label }}</td>
							<td>{{ $invoice->submittedDate }}</td>
		    				<td>{{ $invoice->formattedStatus }}</td>
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
 --}}