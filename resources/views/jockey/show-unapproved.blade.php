@extends('layouts.base')

@section('content')

<div class="panel">
    <div class="panel__inner">
        <div class="panel__main">
            <h1 class="[ heading--1 ] [ mb-1 ]">Profile</h1>       
            {{ $jockey->full_name }}'s Jockey Coaching Programme profile.

            <form method="POST" action="{{ route('admin.jockey.approve', $jockey) }}" class="pt-3">
			    {{ csrf_field() }}
			    <button class="button button--primary button--block" type="submit">Approve</button>
			</form>

			<form 
				method="POST" 
				action="{{ route('admin.jockey.decline', $jockey) }}"
				class="[ js-confirmation ] pt-2"
			    data-confirm="Are you sure? This Jockey will be permanently deleted."
			>
			    {{ csrf_field() }}
			    <input type="hidden" name="_method" value="delete" />
			    <button class="button button--error button--block" type="submit">Decline</button>
			</form>
        </div>
    </div>
</div>



<div class="row row--wide-gutter">
    <div class="col-md-12 col-xl-4 flow-vertical--3">
		<avatar-upload 
            :user-id="{{ $jockey->id }}"
            :can-edit="{{ $isAdmin}}"
            avatar-image="{{ $jockey->getAvatar() }}"
            avatar-filename="{{ $jockey->avatar_filename }}"
        ></avatar-upload>
		
		@include('jockey.partials._racing-stats')
	</div>

	<div class="col-md-12 col-xl-8 mb-2 mb-xl-0 flow-vertical--3">
		@include('jockey.partials._edit-form')
	</div>
</div>

@endsection