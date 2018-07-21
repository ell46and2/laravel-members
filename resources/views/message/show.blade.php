@extends('layouts.app')

@section('content')


<div class="container">
	<form method="POST" action="{{ route('message.delete', $message) }}">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="delete" />
        <button class="btn btn-danger" type="submit">Delete</button>
    </form>
	<h2>{{ $message->subject }}</h2>
	<p>From: <br> 
	<div class="[ profile-summary__avatar ] [ avatar ]">
		<div class="avatar__image" style="background-image:url({{ $message->author->getAvatar() }});"></div>
	</div>
	{{ $message->author->full_name }} <br>
	{{ $message->author->formattedRoleName }}
	</p>
	<br><br>
	<p>Date: {{ $message->created_at->diffForHumans() }}</p>
	<br><br>
	<p>Message</p>
	<p><pre style="font-family: inherit;">{{ $message->body }}</pre></p>

	<br><br><br>
	<a href="{{ route('messages.index') }}" class="btn btn-primary">Back to Inbox</a>
</div>

@endsection