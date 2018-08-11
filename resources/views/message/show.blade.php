@extends('layouts.base')

@section('content')

<div class="panel">
    <div class="panel__inner">
        <div class="panel__main">
            <h1 class="[ heading--1 ] [ mb-1 ]">Read message</h1>
            {{-- You have received a new message --}}
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel__inner">
        <div class="panel__header">
            <h2 class="panel__heading">
                Message Subject
            </h2>
            <div class="panel__heading-meta">
            	<form 
            		method="POST" 
            		action="{{ route('message.delete', $message) }}"
            		class="[ js-confirmation ]"
                    data-confirm="Are you sure you wish to delete this Miscellaneous item?"
            	>
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="delete" />
                    <button class="button button--primary" type="submit">Delete</button>
                </form>
            </div>
        </div>

        <div class="[ panel__main ] [ flow-vertical--3 ]">
            {{-- {# From #} --}}
            <div class="flow-vertical--1">
                <div class="text--size-sm text--color-blue">From</div>
                <div class="[ user-card user-card--has-meta ] [ mb-3 ]">
                    <div class="[ user-card__avatar ] [ avatar ]">
                        <div class="avatar__image" style="background-image:url({{ $message->author->getAvatar() }});"></div>
                    </div>
                    <div class="user-card__main">
                        <div class="user-card__name">{{ $message->author->full_name }}</div>
                        <div class="user-card__meta">
                            {{ $message->author->formattedRoleName }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- {# Date #} --}}
            <div class="flow-vertical--1">
                <div class="text--size-sm text--color-blue">Date</div>
                <div>
                    {{ $message->created_at->diffForHumans() }}
                </div>
            </div>

            {{-- {# Message #} --}}
            <div class="flow-vertical--1">
                <div class="text--size-sm text--color-blue">Message</div>
                <div class="flow-vertical--2">
                    {{ $message->body }}
                </div>
            </div>
        </div>

        <a class="panel__call-to-action" href="{{ route('messages.index') }}">Back to inbox</a>
    </div>
</div>

@endsection


{{-- 

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
 --}}