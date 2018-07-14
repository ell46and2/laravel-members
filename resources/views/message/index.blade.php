@extends('layouts.app')

@section('content')


<div class="container">
	
	<div class="card-body">
        <table class="table">
            <thead>
                <th scope="col">Subject</th>
                <th scope="col">From</th>
                <th scope="col">Date</th>
                <th scope="col"></th>
            </thead>
        
            <tbody>
                @foreach($messages as $message)
                    <tr>
                        <td>{{ $message->subject }}</td>
                        <td>{{ $message->author->fullName }}</td>
                        <td>{{ $message->created_at->diffForHumans() }}</td>
                        <td>
                            <a href="{{ route('messages.show', $message) }}" class="btn btn-primary">View</a>
                            <form method="POST" action="{{ route('message.delete', $message) }}">
                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="delete" />
                                <button class="btn btn-danger" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

       {{ $messages->links() }}
    </div>

</div>

@endsection