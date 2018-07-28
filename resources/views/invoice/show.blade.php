@extends('layouts.app')

@section('content')
@php
    $isEditable = $invoice->isEditable();
    $isEditableAndPendingReview = $invoice->isEditableAndPendingReview();
@endphp
<div class="container">

    @if($invoice->isOpen())
       {{ daysToSubmitInvoice() }} {{ str_plural('Day', daysToSubmitInvoice()) }} left to submit invoice for {{ currentInvoicingMonth() }}'s invoicing period
    @endif

    <br><br>
    
    @if($isEditable)
       <a href="{{ route('invoice.add', $invoice) }}" class="btn btn-primary">Add Items</a>
    @endif
   

   <div>
       <div>
            @if($invoice->label)
                <p>{{ $invoice->label }}</p>
            @endif
           <p>Invoice Number {{ $invoice->id }}</p>
           <p>Status {{ $invoice->status }}</p>
           @if($coach->isVatRegistered())
               <p>VAT Number {{ $coach->vat_number }}</p>
           @endif
       </div>
        <br><br>
       <div>
           <p>To {{ config('jcp.invoice.address.line1') }}</p>
           <p>{{ config('jcp.invoice.address.line2') }}</p>
           <p>{{ config('jcp.invoice.address.line3') }}</p>
           <p>{{ config('jcp.invoice.address.county') }}</p>
           <p>{{ config('jcp.invoice.address.postcode') }}</p>
       </div>
       <br><br>
       <div>
           <p>{{ $coach->full_name }}</p>
           <p>{{ $coach->address_1 }}</p>
           @if($coach->address_2)
               <p>{{ $coach->address_2 }}</p>
           @endif
           <p>{{ $coach->county->name }}</p>
           <p>{{ $coach->postcode }}</p>
       </div>
   </div>


   <br><br>
    <h2>Activities by Jockey</h2>
    @foreach($coach->jockeys as $jockey)
       <h4>{{ $jockey->full_name }}</h4>
       <div class="jockey-training-this-month">
           {{--  Colour red if over allocated amount. --}}
            {{ $jockey->trainingTimeThisMonth() }} Hours coaching this month
       </div>
        
        <table class="table">
            <thead>
                <th scope="col">Type</th>
                <th scope="col">Date</th>
                <th scope="col">Location</th>
                <th scope="col">Duration</th>
                <th scope="col">Amount</th>
                @if($isEditable)
                   <th></th> 
                @endif
                
            </thead>
        
            <tbody>
                @foreach($invoice->activityLines as $line)
                    @if($line->activity->isAssignedToUser($jockey))

                        <tr>
                            <td>{{ $line->activity->formattedType }}</td>
                            <td>{{ $line->activity->formattedStartDayMonth }}</td>
                            <td>{{ $line->activity->formattedLocation }}</td>
                            <td>{{ $line->activity->duration }}</td>
                            <td>{{ $line->formattedValuePerJockey }}</td>
                            @if($isEditable)
                                <td>
                                    @if($isEditableAndPendingReview) 
                                        <a href="" class="btn btn-primary">Edit</a>
                                    @endif
                                    <form method="POST" action="{{ route('invoice.remove-line', [$invoice, $line]) }}">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="_method" value="delete" />
                                        <button class="btn btn-danger" type="submit">Remove</button>
                                    </form>
                                </td>
                            @endif
                            
                        </tr>
                    
                    @endif
                @endforeach
            </tbody>
        </table>
         <br>
    @endforeach

    <br><br>
    @if($invoice->racingExcellenceLines->count())
        <h2>Racing Excellence</h2>
        <table class="table">
            <thead>
                <th scope="col">Race Name</th>
                <th scope="col">Date</th>
                <th scope="col">Number of Divisions</th>
                <th scope="col">Amount</th>
                @if($isEditable)
                   <th></th> 
                @endif
            </thead>
        
            <tbody>
            @foreach($invoice->racingExcellenceLines as $line)
               
                <tr>
                    <td>
                        <p>{{ $line->racingExcellence->formattedSeriesName }}</p>
                        <p>{{ $line->racingExcellence->formattedLocation }}</p>
                    </td>
                    <td>{{ $line->racingExcellence->formattedStartDayMonth }}</td>
                    <td>{{ $line->racingExcellence->numDivisions }}</td>
                    <td>{{ $line->formattedValue }}</td>
                    @if($isEditable)
                        <td>
                            <form method="POST" action="{{ route('invoice.remove-line', [$invoice, $line]) }}">
                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="delete" />
                                <button class="btn btn-danger" type="submit">Remove</button>
                            </form>
                        </td>
                    @endif                   
                </tr>

            @endforeach
            </tbody>
        </table>
    @endif

    <br><br>
    @if($isEditable)
       <a href="{{ route('invoice.misc.create', $invoice) }}" class="btn btn-primary">Add Misc</a>        
    @endif
    <h2>Misc</h2>
    <table class="table">
        <thead>
            <th scope="col">Name</th>
            <th scope="col">Date</th>
            <th scope="col">Amount</th>
            @if($isEditable)
               <th></th> 
            @endif
        </thead>
        <tbody>
            @foreach($invoice->miscellaneousLines as $line)
                <tr>
                    <td>{{ $line->misc_name }}</td>
                    <td>{{ $line->formattedMiscDate }}</td>
                    <td>{{ $line->formattedValue }}</td>
                    @if($isEditable)
                        <td>
                            <a href="{{ route('invoice.misc.edit', [$invoice, $line]) }}" class="btn btn-primary">Edit</a>
                            <form 
                                method="POST" 
                                action="{{ route('invoice.misc.delete', [$invoice, $line]) }}"
                                class="[ js-confirmation ]"
                                data-confirm="Are you sure you wish to delete this Miscellaneous item?"
                            >
                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="delete" />
                                <button class="btn btn-danger" type="submit">Delete</button>
                            </form>
                        </td>
                    @endif
                    
                </tr>
            @endforeach
        </tbody>
    </table>
    


    <br><br>
    @if($isEditable)
       <a href="{{ route('invoice.mileage.add', $invoice) }}" class="btn btn-primary">Add Mileage</a>
    @endif
    
    <h2>Mileage</h2>
    <table class="table">
        <thead>
            <th scope="col">Description</th>
            <th scope="col">Date</th>
            <th scope="col">Miles</th>
            @if($isEditable)
               <th></th> 
            @endif
        </thead>
        <tbody>
            @foreach($invoice->invoiceMileage->mileages as $mileage)
                <tr>
                    <td>{{ $mileage->description }}</td>
                    <td>{{ $mileage->formattedDate }}</td>
                    <td>{{ $mileage->miles }}</td>
                    @if($isEditable)
                        <td>
                            <a href="{{ route('invoice.mileage.edit', [$invoice, $mileage]) }}" class="btn btn-primary">Edit</a>
                            <form 
                                method="POST" 
                                action="{{ route('invoice.mileage.delete', [$invoice, $mileage]) }}"
                                class="[ js-confirmation ]"
                                data-confirm="Are you sure you wish to delete this Mileage?"
                            >
                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="delete" />
                                <button class="btn btn-danger" type="submit">Delete</button>
                            </form>
                        </td>
                    @endif                
                </tr>
            @endforeach
        </tbody>
    </table>
    <p>Total Mileage Value: £{{ $invoice->invoiceMileage->value }}</p>
    

    <br><br>
    <h2>Total</h2>
    @php 
        $invoiceTotal = $invoice->overallValue;
    @endphp
    <p>£{{ invoiceNumberFormat($invoiceTotal) }}</p>
    @if($coach->isVatRegistered())
      <p>VAT: £{{ invoiceNumberFormat(($invoiceTotal / 100) * 20) }}</p>  
    @endif
    

    @if($invoice->canBeSubmitted())
        <form method="POST" action="{{ route('invoice.submit-review', [$invoice]) }}">
            {{ csrf_field() }}
            <button class="btn btn-danger" type="submit">Submit For Review</button>
        </form>
    @endif

    @if($invoice->submittableButOutOfWindow())
        <div>
            You can only submit this invoice between the {{ nextInvoicingPeriod() }}
        </div>
    @endif

    @if($isEditableAndPendingReview)
        <form method="POST" action="{{ route('invoice.approve', [$invoice]) }}">
            {{ csrf_field() }}
            <button class="btn btn-danger" type="submit">Approve</button>
        </form>
    @endif

    <a href="{{ route('invoice.index', $coach) }}" class="btn btn-primary">Back to Invoice Archive</a>
    
</div>
@endsection
