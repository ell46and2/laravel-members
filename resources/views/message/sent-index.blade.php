@extends('layouts.base')

@section('content')

<div class="panel">
    <div class="panel__inner">
        <div class="panel__main">
            <h1 class="[ heading--1 ] [ mb-1 ]">Messages</h1>
            View your inbox 
            @if($currentRole !== 'jockey')
                or sent messages
            @endif 
        </div>
    </div>
</div>

@if($currentRole !== 'jockey')
    <a class="button button--primary button--block" href="{{ route('message.create') }}">Compose new message</a>
@endif

<div class="panel">
    <div class="panel__inner">
        <div class="panel__header">
            <h2 class="panel__heading">
                Sent
            </h2>
            @if($currentRole !== 'jockey')
                <div class="panel__heading-meta">
                    <a href="{{ route('messages.index') }}" class="button button--text">View Inbox</a>
                </div>
            @endif
        </div>

        <div class="panel__main">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>To</th>
                        <th>Message</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($messages as $message)
                        <tr>
                            <td>
                                <a class="table__link" href="{{ route('messages.show', $message) }}">{{ $message->subject }}</a>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($message->recipients->count() === 1)
                                        <div class="[ avatar avatar--blue ] [ table__avatar ]">
                                            <div class="avatar__image" style="background-image:url({{ $message->recipients->first()->getAvatar() }});"></div>
                                        </div>
                                        <a class="table__link" href="">{{ $message->recipients->first()->fullName }}</a>
                                        @else
                                            @svg( 'group', 'icon') {{ $message->recipients->count()  }} users
                                        @endif
                                </div>
                            </td>
                            <td>{{ $message->excerpt }}</td>
                            <td>{{ $message->created_at->format('l jS F') }}</td>
                            <td class="text-right">
                                <a class="button button--primary" href="{{ route('messages.show', $message) }}">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $messages->links() }}
            
{{--     <button class="panel__stack" type="button">
        <span class="sr-only">View sent</span>
    </button> --}}
</div>

@endsection


{{-- 
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

@endsection --}}