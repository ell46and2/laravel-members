@extends('layouts.base')

@section('content')
@php
    $isAdmin = $currentRole === 'admin';
    $isCurrentUser = $currentUser->id === $jet->id;
@endphp

<div class="panel">
    <div class="panel__inner">
        <div class="panel__main">
            <h1 class="[ heading--1 ] [ mb-1 ]">Profile</h1>       
            @if($isCurrentUser)
            	This is your Jockey Coaching Programme profile, here you can see your information and make changes to certain fields.
            @else
            	{{ $jet->full_name }} JETS profile.
            @endif         
        </div>
    </div>
</div>

<div class="row row--wide-gutter">
    <div class="col-md-12 col-xl-4 flow-vertical--3">
		<avatar-upload 
            :user-id="{{ $jet->id }}"
            can-edit="{{ $isAdmin || $isCurrentUser }}"
            avatar-image="{{ $jet->getAvatar() }}"
            avatar-filename="{{ $jet->avatar_filename }}"
        ></avatar-upload>

		@include('jet.partials._jet-status-edit', [ 'jet' => $jet])
		@include('user.partials._password-change', [ 'user' => $jet])
	</div>
	<div class="col-md-12 col-xl-8 mb-2 mb-xl-0 flow-vertical--3">
        <div class="panel">
            <div class="panel__inner">
				@if($isAdmin || $isCurrentUser)
					@include('jet.partials._edit-form')
				@else
					@include('jet.partials._details')
				@endif
			</div>
		</div>
	</div>
</div>

@endsection