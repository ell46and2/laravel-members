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
                Inbox
            </h2>
            @if($currentRole !== 'jockey')
                <div class="panel__heading-meta">
                    <a href="{{ route('messages.sent') }}" class="button button--text">View Sent</a>
                </div>
            @endif
        </div>

        <div class="panel__main">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>From</th>
                        <th>Message</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @if($messages->count())
                        @foreach($messages as $message)
                            <tr>
                                <td>
                                    @if(!$message->pivot->read)
                                        <span class="[ badge badge-pill badge-dark ] [ mr-1 ]">NEW</span>
                                    @endif
                                    <a class="table__link" href="{{ route('messages.show', $message) }}">{{ $message->subject }}</a>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="[ avatar avatar--blue ] [ table__avatar ]">
                                            <div class="avatar__image" style="background-image:url({{ $message->author->getAvatar() }});"></div>
                                        </div>
                                        <a class="table__link" href="">{{ $message->author->fullName }}</a>
                                    </div>
                                </td>
                                <td>{{ $message->excerpt }}</td>
                                <td>{{ $message->created_at->format('l jS F') }}</td>
                                <td class="text-right">
                                    <a class="button button--primary" href="{{ route('messages.show', $message) }}">View</a>
                                </td>
                            </tr>
                        @endforeach 
                    @else
                        <tr>
                            <td class="text-center" colspan="5">
                                No messages
                            </td>
                        </tr>
                    @endif
                    
                </tbody>
            </table>

            {{ $messages->links() }}
            
 {{--    <button class="panel__stack" type="button">
        <span class="sr-only">View sent</span>
    </button> --}}
</div>

@endsection


{{-- 

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

@endsection --}}