@extends('layouts.base')

@section('content')
@php
    $isEditable = $invoice->isEditable();
    $isEditableAndPendingReview = $invoice->isEditableAndPendingReview();
@endphp

<div class="panel">
    <div class="panel__inner">
        <div class="panel__main">
            <h1 class="[ heading--1 ] [ mb-1 ]">Create Invoice</h1>
            Add you activities and mileage to create your invoice.
        </div>
        @if($invoice->isOpen())
            <div class="panel__alert panel__alert--has-icon">
                <div>
                    @svg( 'info-circle', 'icon')
                    {{ daysToSubmitInvoice() }}</span> {{ str_plural('day', daysToSubmitInvoice()) }} left to submit invoice for {{ currentInvoicingMonth() }}'s invoicing period
                </div>
            </div>
        @endif
    </div>
</div>

@if($isEditable)
    <div class="row">
        <div class="col-sm-4">
            <a class="button button--primary button--block" href="{{ route('invoice.add', $invoice) }}">Add Activities &amp; Racing Excellence</a>
        </div>
        <div class="col-sm-4">
            <a class="button button--primary button--block" href="{{ route('invoice.mileage.add', $invoice) }}">Add Mileage</a>
        </div>
        <div class="col-sm-4">
            <a class="button button--primary button--block" href="{{ route('invoice.misc.create', $invoice) }}">Add Miscellaneous</a>
        </div>
    </div>
@endif

<div class="panel">
    <div class="panel__inner">
        <div class="panel__main panel__main--3-columns">
            <div>
                <dl class="definition-list">
                    @if($invoice->label)
                        <dt>Invoice date</dt>
                        <dd>{{ $invoice->label }}</dd>
                    @endif

                    <dt>Invoice number</dt>
                    <dd>{{ $invoice->id }}</dd>

                    <dt>Status</dt>
                    <dd>{{ $invoice->status }}</dd>
                    
                    @if($coach->isVatRegistered())
                        <dt>VAT number</dt>
                        <dd>{{ $coach->vat_number }}</dd>
                    @endif
                </dl>
            </div>
            <div>
                <dl class="definition-list">
                    <dt>To</dt>
                    <dd>
                        {{ config('jcp.invoice.address.name') }}<br>
                        {{ config('jcp.invoice.address.line1') }}<br>
                        {{ config('jcp.invoice.address.line2') }}<br>
                        {{ config('jcp.invoice.address.line3') }}<br>
                        {{ config('jcp.invoice.address.county') }}<br>
                        {{ config('jcp.invoice.address.postcode') }}
                    </dd>
                </dl>
            </div>
            <div>
                <dl class="definition-list">
                    <dt>To</dt>
                    <dd>
                        {{ $coach->full_name }}<br>
                        {!! $coach->fullAddress !!}
                    </dd>
                </dl>
            </div>
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel__inner">
        <div class="panel__header">
            <h2 class="panel__heading">
                Jockey 1:1 &amp; Group Activities
            </h2>
        </div>

        <div class="panel__main">
            <table class="table table--grouped">
                <thead>
                    <tr>
                        <th>Activity</th>
                        <th>Date</th>
                        <th>Duration</th>
                        <th>Amount</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                @foreach($invoiceJockeys as $jockey)
                    @php 
                        // $trainingTimeThisMonth = $jockey->trainingTimeThisMonth();
                        $trainingTime = $jockey->trainingTimeForInvoiceMonth($invoice);
                        $allowedTime = $jockey->trainingTimeAllowanceForInvoiceMonth($invoice);
                    @endphp
                    <tbody>
                        <tr>
                            <th colspan="6">
                                <div class="table__group-head">
                                    <div class="[ table__group-head-avatar ] [ avatar ]">
                                        <div class="avatar__image" style="background-image:url('{{ $jockey->getAvatar() }}');"></div>
                                    </div>
                                    <div class="table__group-head-text">
                                        <div>
                                            {{ $jockey->full_name }}
                                        </div>
                                        <div class="table__group-header-total-time{{ $trainingTime > $allowedTime ? ' text-danger' : '' }}">
                                            {{--  Colour red if over allocated amount. --}}
                                            {{ $trainingTime }} hours coaching this month
                                        </div>
                                    </div>
                                </div>
                            </th>
                        </tr>
                        
                        @php
                            $jockeyActivityCount = 0;
                        @endphp

                        @foreach($invoice->activityLines as $line)
                            
                            @if($line->activity->isAssignedToUser($jockey))
                                <tr>
                                    <td class="table__group-item-decor"></td>
                                    <td>
                                        <a class="table__link" href="">{{ $line->activity->formattedType }}</a>
                                    </td>
                                    <td>{{ $line->activity->formattedStartDayMonth }}</td>
                                    <td>{{ $line->activity->duration }}</td>
                                    <td>{{ $line->formattedValuePerJockey }}</td>
                                    
                                    @if($isEditableAndPendingReview) 
                                        <td class="table__icon-column">
                                            <a class="table__icon-button" href="{{ route('invoice-line.edit', [$invoice, $line]) }}">
                                                @svg( 'edit', 'icon')
                                            </a>
                                        </td>
                                    @endif
                                    @if($isEditable)
                                        <td class="text-right">
                                            <form method="POST" action="{{ route('invoice.remove-line', [$invoice, $line]) }}">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="_method" value="delete" />
                                                <button class="button button--primary" type="submit">Remove</button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                                @php
                                    $jockeyActivityCount++;
                                @endphp
                            @endif
                        @endforeach
                        
                        @if(!$jockeyActivityCount)
                            <tr>
                                <td class="text-center" colspan="5">
                                    No items
                                </td>
                            </tr>
                        @endif
                        
                    </tbody>
                @endforeach
            </table>
        </div>
        @if($isEditable)
            <a class="panel__call-to-action" href="{{ route('invoice.add', $invoice) }}">Add activities to invoice</a>
        @endif
    </div>
</div>

@if($invoice->racingExcellenceLines->count())
    <div class="panel">
        <div class="panel__inner">
            <div class="panel__header">
                <h2 class="panel__heading">
                    Racing Excellence
                </h2>
            </div>

            <div class="panel__main">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Race name</th>
                            <th>Date</th>
                            <th>Number of division</th>
                            <th>Amount</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->racingExcellenceLines as $line)
                            <tr>
                                <td>
                                    <div>
                                        <a class="table__link" href="">{{ $line->racingExcellence->formattedSeriesName }}</a>
                                    </div>
                                    <div>
                                        {{ $line->racingExcellence->formattedLocation }}
                                    </div>
                                </td>
                                <td>{{ $line->racingExcellence->formattedStartDayMonth }}</td>
                                <td>{{ $line->racingExcellence->numDivisions }}</td>
                                <td>{{ $line->formattedValue }}</td>
                                @if($isEditable)
                                    <td class="text-right">
                                        <form method="POST" action="{{ route('invoice.remove-line', [$invoice, $line]) }}">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="_method" value="delete" />
                                            <button class="button button--primary" type="submit">Remove</button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($isEditable)
                <a class="panel__call-to-action" href="{{ route('invoice.add', $invoice) }}">Add Racing Excellence to invoice</a>
            @endif
        </div>
    </div>
@endif

<div class="panel">
    <div class="panel__inner">
        <div class="panel__header">
            <h2 class="panel__heading">
                Mileage
            </h2>
        </div>

        <div class="panel__main">
            <table class="table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Miles</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @if(!$invoice->invoiceMileage->mileages->count())
                        <tr>
                            <td class="text-center" colspan="6">
                                No items
                            </td>
                        </tr>  
                    @else
                    
                        @foreach($invoice->invoiceMileage->mileages as $mileage)
                            <tr>
                                <td>{{ $mileage->description }}</td>
                                <td>{{ $mileage->formattedDate }}</td>
                                <td>{{ $mileage->miles }}</td>
                                @if($isEditable)                    
                                    <td class="table__icon-column">
                                        <a class="table__icon-button" href="{{ route('invoice.mileage.edit', [$invoice, $mileage]) }}">
                                            @svg( 'edit', 'icon')
                                        </a>
                                    </td>
                           
                                    <td class="text-right">
                                        <form 
                                            method="POST" 
                                            action="{{ route('invoice.mileage.delete', [$invoice, $mileage]) }}"
                                            class="[ js-confirmation ]"
                                            data-confirm="Are you sure you wish to delete this Mileage?"
                                        >
                                            {{ csrf_field() }}
                                            <input type="hidden" name="_method" value="delete" />
                                            <button class="button button--primary" type="submit">Delete</button>
                                        </form>
                                    </td>  
                                @endif                
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>

            @if($invoice->invoiceMileage->mileages->count())
                <p class="text-right mt-3"><span class="text--color-blue">Total Mileage Value:</span> £{{ $invoice->invoiceMileage->value }}</p>
            @endif
    
        </div>

        @if($isEditable)
            <a class="panel__call-to-action" href="{{ route('invoice.mileage.add', $invoice) }}">Add mileage to invoice</a>
        @endif
    </div>
</div>

<div class="panel">
    <div class="panel__inner">
        <div class="panel__header">
            <h2 class="panel__heading">
                Miscellaneous
            </h2>
        </div>

        <div class="panel__main">
            <table class="table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Amount</th>
                        @if($isEditable)
                            <th></th>
                            <th></th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if(!$invoice->miscellaneousLines->count())
                        <tr>
                            <td class="text-center" colspan="4">
                                No items
                            </td>
                        </tr>
                    @endif
                    

                    @foreach($invoice->miscellaneousLines as $line)
                        <tr>
                            <td>{{ $line->misc_name }}</td>
                            <td>{{ $line->formattedMiscDate }}</td>
                            <td>{{ $line->formattedValue }}</td>
                            @if($isEditable)                           
                                <td class="table__icon-column">
                                    <a class="table__icon-button" href="{{ route('invoice.misc.edit', [$invoice, $line]) }}">
                                        @svg( 'edit', 'icon')
                                    </a>
                                </td>
                                <td class="text-right">
                                    <form 
                                        method="POST" 
                                        action="{{ route('invoice.misc.delete', [$invoice, $line]) }}"
                                        class="[ js-confirmation ]"
                                        data-confirm="Are you sure you wish to delete this Miscellaneous item?"
                                    >
                                        {{ csrf_field() }}
                                        <input type="hidden" name="_method" value="delete" />
                                        <button class="button button--primary" type="submit">Delete</button>
                                    </form>
                                </td>
                            @endif
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($isEditable)
            <a class="panel__call-to-action" href="{{ route('invoice.misc.create', $invoice) }}">Add Miscellaneous to invoice</a>
        @endif
    </div>
</div>

<div class="panel">
    <div class="panel__inner">
        <div class="panel__main">
            <div class="row justify-content-end">
                <div class="col-md-4">
                    <dl class="definition-list definition-list--space-between">
                        @php 
                            $invoiceTotal = $invoice->overallValue;
                        @endphp

                        <dt>Net</dt>
                        <dd>&pound;{{ invoiceNumberFormat($invoiceTotal) }}</dd>

                        <dd class="definition-list__line-break" aria-hidden="true" role="presentation"></dd>

                         @if($coach->isVatRegistered())
                            <dt>VAT</dt>
                            <dd>&pound;{{ invoiceNumberFormat(($invoiceTotal / 100) * 20) }}</dd>

                            <dd class="definition-list__line-break" aria-hidden="true" role="presentation"></dd>
                        @endif

                        <dt>Total</dt>
                        <dd>
                            <span class="heading--1">&pound;{{ invoiceNumberFormat($invoiceTotal) }}</span>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

@if($invoice->canBeSubmitted())
    <form method="POST" action="{{ route('invoice.submit-review', [$invoice]) }}">
        {{ csrf_field() }}
        <button class="button button--success button--block" type="submit">Submit For Review</button>
    </form>
@endif

@if($invoice->submittableButOutOfWindow())
    <div class="panel__alert panel__alert--has-icon">
        <div>
            @svg( 'info-circle', 'icon')
            You can only submit this invoice between the {{ nextInvoicingPeriod() }}
        </div>
    </div>
@endif

@if($isEditableAndPendingReview)
    <form method="POST" action="{{ route('invoice.approve', [$invoice]) }}">
        {{ csrf_field() }}
        <button class="button button--success button--block" type="submit">Approve</button>
    </form>
@endif

<a href="{{ route('invoice.index', $coach) }}" class="button button--primary button--block">Back to Invoice Archive</a>

@endsection