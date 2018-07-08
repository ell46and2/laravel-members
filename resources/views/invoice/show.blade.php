@extends('layouts.app')

@section('content')
<div class="container">
        
   <a href="{{ route('invoice.add', $invoice) }}" class="btn btn-primary">Add Items</a>

   <br><br>
    <h2>Activities by Jockey</h2>
    @foreach($coach->jockeys as $jockey)
       <h4>{{ $jockey->full_name }}</h4>
        
        <table class="table">
            <thead>
                <th scope="col">Type</th>
                <th scope="col">Date</th>
                <th scope="col">Duration</th>
                <th scope="col">Value</th>
                <th></th>
            </thead>
        
            <tbody>
                @foreach($invoice->activityLines as $line)
                    @if($line->activity->isAssignedToUser($jockey))

                        <tr>
                            <td>{{ $line->activity->formattedType }}</td>
                            <td>{{ $line->activity->startDate }}</td>
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
            <th scope="col">Date</th>
            <th scope="col">Divisions</th>
            <th scope="col">Value</th>
            <th></th>
        </thead>
    
        <tbody>
        @foreach($invoice->racingExcellenceLines as $line)
           
            <tr>
                <td>{{ $line->racingExcellence->startDate }}</td>
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
    <h2>Misc</h2>
    <form method="POST" action="{{ route('invoice.add-misc', $invoice) }}">
        {{ csrf_field() }}

        <table class="table">
            <thead>
                <th scope="col">Name</th>
                <th scope="col">Date</th>
                <th scope="col">Value</th>
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

                <tr>
                    <td>
                        <input type="text" name="misc_name">
                    </td>
                    <td>
                        <datepicker-component name="misc_date" placeholder="Select Date" old="{{ old('misc_date') }}"></datepicker-component>
                    </td>
                    <td>
                        <input type="number" name="value">
                    </td>
                    <td>
                        <button class="btn btn-primary" type="submit">Add</button>
                    </td>
                </tr>

            </tbody>
        </table>
    </form>
    

    <br><br>
    <h2>Total</h2>
    <p>£{{ $invoice->overallValue }}</p>
    <form method="POST" action="{{ route('invoice.submit-review', [$invoice]) }}">
        {{ csrf_field() }}
        <button class="btn btn-danger" type="submit">Submit For Review</button>
    </form>
</div>
@endsection
