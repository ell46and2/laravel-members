@extends('layouts.app')

@section('content')


<div class="container">
	
	<div class="card-body">
        <table class="table">
            <thead>
                <th scope="col">Subject</th>
                <th scope="col">Date</th>
                <th scope="col"></th>
            </thead>
        
            <tbody>
                @foreach($messages as $message)
                    <tr>
                        <td>{{ $message->subject }}</td>
                        <td>{{ $message->created_at->diffForHumans() }}</td>
                        <td><a href="{{ route('messages.show', $message) }}" class="btn btn-primary">View</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

       {{ $messages->links() }}
    </div>

</div>

@endsection