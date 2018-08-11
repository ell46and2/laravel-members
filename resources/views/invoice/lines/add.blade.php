@extends('layouts.base')

@section('content')

<div class="panel">
    <div class="panel__inner">
        <div class="panel__main">
            <h1 class="[ heading--1 ] [ mb-1 ]">Add activities to invoice</h1>
            Add Activities and Racing Excellence to your invoice
        </div>
        <div class="panel__alert panel__alert--has-icon">
            <div>
                @svg( 'info-circle', 'icon')
                Can't see an activity? Contact the Jockey Coaching Programme admin.
            </div>
            {{-- <div>
                <a class="button button--white" href="">Contact admin</a>
            </div> --}}
        </div>
    </div>
</div>

<form method="POST" action="{{ route('invoice.add-lines', $invoice) }}">
   	{{ csrf_field() }}
	<div class="panel">
	    <div class="panel__inner">
	        <div class="panel__header">
	            <h2 class="panel__heading">
	                List of activities in the past 60 days
	            </h2>
	            <div class="panel__header-meta">
	                <button class="[ button button--primary ] [ mr-1 ] [ js-select-all ]" type="button">Select all</button>
	                <button class="button button--primary [ js-unselect-all ]" type="button">Deselect all</button>
	            </div>
	        </div>

	        <div class="panel__main">
	            <table class="table">
	                <thead>
	                    <tr>
	                        <th></th>
	                        <th>Activity</th>
	                        <th>Jockey</th>
	                        <th>Date</th>
	                        <th></th>
	                        <th></th>
	                    </tr>
	                </thead>
	                <tbody>
	                    @foreach($invoiceables as $index => $invoiceable)
	                        <tr>
	                            <td class="table__checkbox-column">
	                                <div class="[ custom-control custom-checkbox ] [ table__checkbox ]">
	                                    <input 
	                                    	type="checkbox" 
	                                    	class="custom-control-input [ js-invoiceable-checkbox ]" 
	                                    	name="{{ $invoiceable->invoiceableGroup }}[]"
	                                    	value="{{ $invoiceable->id }}"
	                                    	id="{{ $index }}"
	                                    >
	                                    <label class="custom-control-label" for="{{ $index }}"></label>
	                                </div>
	                            </td>
	                            <td>
	                                <a class="table__link" href="">{{ $invoiceable->formattedType }}</a>
	                            </td>
	                            <td> 
	                                @if(isset($invoiceable->activity_type_id) && $invoiceable->jockeys()->count() === 1)
	                                	{{ $invoiceable->jockeys->first()->full_name }}
	                                @else
	                                	<div class="d-flex align-items-center">
	                                        <div class="table__icon">
	                                            @svg( 'group', 'icon')
	                                        </div>
	                                        <div>
                                                Group
                                            </div>
	                                    </div>
	                                @endif
	                            </td>
	                            <td>{{ $invoiceable->formattedStart }}</td>
	                            <td class="table__icon-column">
	                                <a class="table__icon-button" href="">
	                                    @svg( 'edit', 'icon')
	                                </a>
	                            </td>
	                            <td class="text-right">
	                                <a class="button button--primary" href="">View</a>
	                            </td>
	                        </tr>
	                    @endforeach
	                </tbody>
	            </table>
	        </div>
	    </div>
	</div>

	<button class="button button--success button--block" type="submit">Add selected activities to invoice</button>
</form>

@endsection

{{-- <div class="container">

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
</div> --}}
