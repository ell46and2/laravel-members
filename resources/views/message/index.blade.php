@extends('layouts.app')

@section('content')


<div class="container">
	<a href="{{ route('message.create') }}" class="btn btn-primary">Compose New Message</a>
	<div class="card-body">
        <a href="{{ route('messages.sent') }}" class="btn btn-primary">View Sent</a>
        <h2>Inbox</h2>
        <table class="table">
            <thead>
                <th scope="col">Subject</th>
                <th scope="col">From</th>
                <th scope="col">Message</th>
                <th scope="col">Date</th>
                <th scope="col"></th>
            </thead>
        
            <tbody>
                @foreach($messages as $message)
                    <tr>
                        <td>
                            @if(!$message->pivot->read)
                                [NEW]   
                            @endif
                            {{ $message->subject }}
                        </td>
                        <td>
                            <div class="[ user-bar__avatar ] [ avatar ]">
                                <div class="avatar__image" style="background-image:url({{ $message->author->getAvatar() }});"></div>
                            </div>
                            {{ $message->author->fullName }}
                        </td>
                        <td>{{ $message->excerpt }}</td>
                        <td>{{ $message->created_at->format('l jS F') }}</td>
                        <td>
                            <a href="{{ route('messages.show', $message) }}" class="btn btn-primary">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

       {{ $messages->links() }}
    </div>

</div>

@endsection