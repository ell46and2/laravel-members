@extends('layouts.base')

@section('content')
@php
    $isAdmin = $currentRole === 'admin';
    $isCurrentUser = $currentUser->id === $coach->id;
@endphp

<div class="panel">
    <div class="panel__inner">
        <div class="panel__main">
            <div class="profile-summary">
                @if(!$isCurrentUser && !$isAdmin)
                    <div class="[ profile-summary__avatar ] [ avatar ]">
                        <div class="avatar__image" style="background-image:url({{ $coach->getAvatar() }});"></div>
                    </div>
                @endif
                <div class="profile-summary__main">
                    <h2 class="profile-summary__greeting-primary">
                        @if($isCurrentUser)
                            Profile
                        @else
                            {{ $coach->full_name }}
                        @endif
                    </h2>
                    <div class="profile-summary__greeting-secondary">
                        @if($isCurrentUser)
                            This is your Jockey Coaching Programme profile, here you can see your information and make changes to certain fields.
                        @else
                            Coach
                        @endif
                    </div>
                </div>
            </div>


           {{--  <div class="[ profile-summary__avatar ] [ avatar ]">
                            <div class="avatar__image" style="background-image:url({{ auth()->user()->getAvatar() }});"></div>
                        </div>
            <h1 class="[ heading--1 ] [ mb-1 ]">
                @if($isCurrentUser)
                    Profile
                @else
                    {{ $coach->full_name }}
                @endif
            </h1>       
            @if($isCurrentUser)
            	This is your Jockey Coaching Programme profile, here you can see your information and make changes to certain fields.
            @else
            	Coach profile.
            @endif --}}

            {{-- @if($currentRole === 'jockey')
                       
            @endif   --}}       
        </div>
    </div>
</div>

<div class="row row--wide-gutter">
    
        <div class="col-md-12 col-xl-4 flow-vertical--3">
            @if($isCurrentUser || $isAdmin)
                <avatar-upload 
                    :user-id="{{ $coach->id }}"
                    can-edit="{{ $isAdmin || $isCurrentUser }}"
                    avatar-image="{{ $coach->getAvatar() }}"
                    avatar-filename="{{ $coach->avatar_filename }}"
                ></avatar-upload>
            
        		{{-- @include('user.partials._profile-picture-edit', [ 'user' => $coach]) --}}
        		@include('coach.partials._coach-status-edit', [ 'coach' => $coach])
        		@include('user.partials._password-change', [ 'user' => $coach])
            @endif

            @if($currentRole === 'jockey')
                <div class="panel">
                    <div class="panel__inner">
                        <div class="panel__header">
                            <h2 class="panel__heading">
                                Your Training with {{ $coach->first_name }}
                            </h2>
                        </div>


                        <div class="panel__main">
                            <span class="text--color-blue-dark">This Month</span>
                            <dl class="definition-list mb-3">
                                <dt>Duration (hrs):</dt>
                                <dd>{{ $trainingWithCoachMonth->duration }}</dd>

                                <dt>No. Activities:</dt>
                                <dd>{{ $trainingWithCoachMonth->numActivities }}</dd>
                            </dl>
                            <span class="text--color-blue-dark">This Year</span>
                            <dl class="definition-list">
                                <dt>Duration (hrs):</dt>
                                <dd>{{ $trainingWithCoachYear->duration }}</dd>

                                <dt>No. Activities:</dt>
                                <dd>{{ $trainingWithCoachYear->numActivities }}</dd>
                            </dl>
                        </div>
                      
                    </div>
                </div>
            @endif
    	</div>
    

	<div class="flow-vertical--3 col-md-12 col-xl-8 mb-2 mb-xl-0">
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

@if($currentRole === 'jockey')
        <div class="panel panel--is-stack">
            <div class="panel__inner">
                <div class="panel__header">
                    <h2 class="panel__heading">
                        Recent Activities
                    </h2>
                </div>
                <div class="panel__main">
                    <table class="table table--stacked-xxs table--stacked-xs table--stacked-sm table--stacked-md">
                        <thead>
                            <tr>
                                <th>Activity type</th>
                                <th>Coach</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Location</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lastFiveActivities as $activity)                              
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="table__icon">
                                                @svg( $activity->icon, 'icon')
                                            </span>
                                            <a class="table__link" href="">{{ $activity->formattedType }}</a>
                                        </div>
                                    </td>
                                    <td aria-label="Coach">{{ $coach->full_name }}</td>
                                    <td aria-label="Date">{{ $activity->formattedStartDayMonth }}</td>
                                    <td aria-label="Time">{{ $activity->formattedStartTime }}</td>
                                    <td aria-label="Location">{{ $activity->formattedLocation }}</td>
                                    <td class="text-right">
                                        <a class="button button--primary" href="{{ $activity->notificationLink }}">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <a href="{{ route('jockey.activity-log', ['coach' => $coach->id]) }}" class="panel__call-to-action mt-2">All Activities</a>
                </div>
            </div>
        </div>
@else
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
@endif



@endsection