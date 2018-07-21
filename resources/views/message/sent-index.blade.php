@extends('layouts.app')

@section('content')


<div class="container">
    <a href="{{ route('message.create') }}" class="btn btn-primary">Compose New Message</a>
	<div class="card-body">
        <a href="{{ route('messages.index') }}" class="btn btn-primary">View Inbox</a>
        <h2>Sent</h2>
        <table class="table">
            <thead>
                <th scope="col">Subject</th>
                <th scope="col">To</th>
                <th scope="col">Message</th>
                <th scope="col">Date</th>
                <th scope="col"></th>
            </thead>
        
            <tbody>
                @foreach($messages as $message)
                    <tr>
                        <td>{{ $message->subject }}</td>
                        <td>
                            @if($message->recipients->count() === 1)
                                <div class="[ user-bar__avatar ] [ avatar ]">
                                    <div class="avatar__image" style="background-image:url({{ $message->author->getAvatar() }});"></div>
                                </div>
                                {{ $message->recipients->first()->full_name }}
                            @else
                                [Group Icon] {{ $message->recipients->count()  }} users
                            @endif
                        </td>
                        <td>{{ $message->created_at->format('l jS F') }}</td>
                        <td>
                            <a href="{{ route('messages.show', $message) }}" class="btn btn-primary">View</a>
                            <form method="POST" action="{{ route('sent-message.delete', $message) }}">
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