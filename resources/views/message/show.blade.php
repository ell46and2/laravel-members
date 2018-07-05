@extends('layouts.app')

@section('content')


<div class="container">
	<h2>{{ $message->subject }}</h2>
	<p>Subject: {{ $message->subject }}</p>
	<p>From: {{ $message->author->full_name }}</p>
	<p>Date: {{ $message->created_at->diffForHumans() }}</p>

	<p>{{ $message->body }}</p>
</div>

@endsection