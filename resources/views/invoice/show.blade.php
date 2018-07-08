@extends('layouts.app')

@section('content')
<div class="container">
        
   <a href="{{ route('invoice.add', $invoice) }}" class="btn btn-primary">Add Items</a>

   <br><br>
    <h2>Activities by Jockey</h2>
    @foreach($coach->jockeys as $jockey)
       <p>{{ $jockey->full_name }}</p>
        
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
    <h2>Total</h2>
    <p>£{{ $invoice->overallValue }}</p>
</div>
@endsection
