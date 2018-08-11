@extends('layouts.base')

@section('content')
@php
    $isAdmin = $currentRole === 'admin';
    $isCurrentUser = $currentUser->id === $coach->id;
@endphp

<div class="panel">
    <div class="panel__inner">
        <div class="panel__main">
            <h1 class="[ heading--1 ] [ mb-1 ]">Profile</h1>       
            @if($isCurrentUser)
            	This is your Jockey Coaching Programme profile, here you can see your information and make changes to certain fields.
            @else
            	{{ $coach->full_name }} Coach profile.
            @endif         
        </div>
    </div>
</div>

<div class="row row--wide-gutter">
    <div class="col-md-12 col-xl-4 flow-vertical--3">

		@include('user.partials._profile-picture-edit', [ 'user' => $coach])
		@include('coach.partials._coach-status-edit', [ 'coach' => $coach])
		@include('user.partials._password-change', [ 'user' => $coach])

	</div>

	<div class="col-md-12 col-xl-8 mb-2 mb-xl-0 flow-vertical--3">
        <div class="panel">
            <div class="panel__inner">
				@if($isAdmin || $isCurrentUser)
					@include('coach.partials._edit-form')
				@else
					@include('coach.partials._details')
				@endif
            </div>
        </div>
    </div>

</div>

@php
	$canAssignJockeys = $isAdmin && $coach->status !== 'deleted';
@endphp

<div class="panel" style="z-index: 3;">
    <div class="panel__inner">
        <div class="panel__header">
            <h2 class="panel__heading">
            	@if($canAssignJockeys)
            		Assign Jockeys
                	<div class="[ text--color-base text--size-base ] [ font-weight-normal ] [ mt-1 ]">Select Jockey from the dropdown list</div>
                @else
                	Assigned Jockeys
            	@endif
                
            </h2>
        </div>

        <jockey-assign
			:can-assign-jockeys="{{ json_encode($canAssignJockeys) }}"
			:coach-id="{{ $coach->id }}"
			resource="{{ json_encode($jockeysResource) }}"
			:current="{{ $coach->jockeys->pluck('id') }}"
		>
		</jockey-assign>
    </div>
</div>

@endsection