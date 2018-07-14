@extends('layouts.app')

@section('content')
<div class="container">

    @if($invoice->isOpen())
       {{ daysToSubmitInvoice() }} {{ str_plural('Day', daysToSubmitInvoice()) }} left to submit invoice for {{ currentInvoicingMonth() }}'s invoicing period
    @endif

    <br><br>
        
   <a href="{{ route('invoice.add', $invoice) }}" class="btn btn-primary">Add Items</a>


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
                <th></th>
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
                            <td>
                                <form method="POST" action="{{ route('invoice.remove-line', [$invoice, $line]) }}">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="_method" value="delete" />
                                    <button class="btn btn-danger" type="submit">Remove</button>
                                </form>
                            </td>
                        </tr>
                    
                    @endif
                @endforeach
            </tbody>
        </table>
         <br>
    @endforeach

    <br><br>
    <h2>Racing Excellence</h2>
    <table class="table">
        <thead>
            <th scope="col">Race Name</th>
            <th scope="col">Date</th>
            <th scope="col">Number of Divisions</th>
            <th scope="col">Amount</th>
            <th></th>
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
                <td>
                    <form method="POST" action="{{ route('invoice.remove-line', [$invoice, $line]) }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="delete" />
                        <button class="btn btn-danger" type="submit">Remove</button>
                    </form>
                </td>
            </tr>

        @endforeach
        </tbody>
    </table>

    <br><br>
    <a href="{{ route('invoice.create-misc', $invoice) }}" class="btn btn-primary">Add Misc</a>
    <h2>Misc</h2>

        <table class="table">
            <thead>
                <th scope="col">Name</th>
                <th scope="col">Date</th>
                <th scope="col">Amount</th>
                <th></th>
            </thead>
            <tbody>
                @foreach($invoice->miscellaneousLines as $line)
                    <tr>
                        <td>{{ $line->misc_name }}</td>
                        <td>{{ $line->formattedMiscDate }}</td>
                        <td>{{ $line->formattedValue }}</td>
                        <td>
                            <form method="POST" action="{{ route('invoice.delete-misc', [$invoice, $line]) }}">
                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="delete" />
                                <button class="btn btn-danger" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </form>
    

    <br><br>
    <h2>Total</h2>
    <p>£{{ $invoice->overallValue }}</p>

    @if($invoice->canBeSubmitted())
        <form method="POST" action="{{ route('invoice.submit-review', [$invoice]) }}">
            {{ csrf_field() }}
            <button class="btn btn-danger" type="submit">Submit For Review</button>
        </form>
    @endif
    
</div>
@endsection
