<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
    .page-break {
        page-break-after: always;
    }
        
    </style>

</head>
<body>
    <div id="app" class="app js-app">      
        <main class="main">
            
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
                                </tr>
                            </thead>
                            @foreach($coach->jockeys as $jockey)
                                @php 
                                    // $trainingTimeThisMonth = $jockey->trainingTimeThisMonth();
                                    $trainingTime = $jockey->trainingTimeForInvoiceMonth($invoice);
                                    $allowedTime = $jockey->trainingTimeAllowanceForInvoiceMonth($invoice);
                                @endphp
                                <tbody class="page-break">
                                    <tr>
                                        <td colspan="4">
                                            <div class="table__group-head">
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
                                        </td>
                                    </tr>
                                    
                                    @php
                                        $jockeyActivityCount = 0;
                                    @endphp

                                    @foreach($invoice->activityLines as $line)
                                        
                                        @if($line->activity->isAssignedToUser($jockey))
                                            <tr>
                                                {{-- <td class="table__group-item-decor"></td> --}}
                                                <td>
                                                    <a class="table__link" href="">{{ $line->activity->formattedType }}</a>
                                                </td>
                                                <td>{{ $line->activity->formattedStartDayMonth }}</td>
                                                <td>{{ $line->activity->duration }}</td>
                                                <td>{{ $line->formattedValuePerJockey }}</td>
                                                
                                            </tr>
                                            @php
                                                $jockeyActivityCount++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    
                                    @if(!$jockeyActivityCount)
                                        <tr>
                                            <td class="text-center" colspan="4">
                                                No items
                                            </td>
                                        </tr>
                                    @endif
                                    
                                </tbody>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>

            <br>

            @if($invoice->racingExcellenceLines->count())
                <div class="panel page-break">
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
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <br>

            <div class="panel page-break">
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
                                </tr>
                            </thead>
                            <tbody>
                                @if(!$invoice->invoiceMileage)
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
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>

                        @if($invoice->invoiceMileage)
                            <p>Total Mileage Value: £{{ $invoice->invoiceMileage->value }}</p>
                        @endif
                
                    </div>
                </div>
            </div>

           <br>

            <div class="panel page-break">
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
                                </tr>
                            </thead>
                            <tbody>
                                @if(!$invoice->miscellaneousLines->count())
                                    <tr>
                                        <td class="text-center" colspan="3">
                                            No items
                                        </td>
                                    </tr>
                                @endif
                                

                                @foreach($invoice->miscellaneousLines as $line)
                                    <tr>
                                        <td>{{ $line->misc_name }}</td>
                                        <td>{{ $line->formattedMiscDate }}</td>
                                        <td>{{ $line->formattedValue }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <br>

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

                                   

                                     @if($coach->isVatRegistered())
                                        <dt>VAT</dt>
                                        <dd>&pound;{{ invoiceNumberFormat(($invoiceTotal / 100) * 20) }}</dd>

                                        
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
        </main>
    </div>
</body>
</html>