@extends('layouts.base')

@section('content')
@php
    $isAdmin = $currentRole === 'admin';
    $isCurrentUser = $currentUser->id === $jockey->id;
@endphp

<div class="panel">
    <div class="panel__inner">
        <div class="panel__main">
            <h1 class="[ heading--1 ] [ mb-1 ]">Profile</h1>       
            @if($isCurrentUser)
            	This is your Jockey Coaching Programme profile, here you can see your information and make changes to certain fields.
            @else
            	{{ $jockey->full_name }}'s Jockey Coaching Programme profile.
            @endif         
        </div>
    </div>
</div>

<div class="row row--wide-gutter">
    <div class="col-md-12 col-xl-4 flow-vertical--3">

		@include('user.partials._profile-picture-edit', [ 'user' => $jockey])

		@include('jockey.partials._racing-stats')

		@include('jockey.partials._jockey-status-edit', [ 'jockey' => $jockey])
	
		@include('user.partials._password-change', [ 'user' => $jockey])

    </div>

    <div class="col-md-12 col-xl-8 mb-2 mb-xl-0 flow-vertical--3">
        <div class="panel">
            <div class="panel__inner">
                @if($isAdmin || $isCurrentUser)
                	@include('jockey.partials._edit-form')
                @else
                	@include('jockey.partials._details')
                @endif
            </div>
        </div>

    </div>
</div>

@php
	$canAssignCoaches = $isAdmin && $jockey->status !== 'deleted';
@endphp

<div class="panel" style="z-index: 3;">
    <div class="panel__inner">
        <div class="panel__header">
            <h2 class="panel__heading">
            	@if($canAssignCoaches)
            		Assign Coaches
                	<div class="[ text--color-base text--size-base ] [ font-weight-normal ] [ mt-1 ]">Select Coach from the dropdown list</div>
                @else
                	Assigned Coaches
            	@endif
                
            </h2>
        </div>

        <coach-assign
        	:can-assign-coaches="{{ json_encode($canAssignCoaches) }}"
        	:jockey-id="{{ $jockey->id }}"
        	resource="{{ json_encode($coachesResource) }}"
        	:current="{{ $jockey->coaches->pluck('id') }}"
        ></coach-assign>
    </div>
</div>





@if($isAdmin || $jockey->isAssignedToCoach($currentUser->id))
	<div class="panel panel--is-stack">
        <div class="panel__inner">
            <div class="panel__header">
                <h2 class="panel__heading">
                    Recent Activities
                </h2>
            </div>
			<div class="panel__main">
		        <table class="table">
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
		                @foreach($jockey->lastFiveActivities as $activity)	                            
		                    <tr>
		                        <td>
                                	<div class="d-flex align-items-center">
	                                    <span class="table__icon">
	                                        @svg( $activity->icon, 'icon')
	                                    </span>
	                                    <a class="table__link" href="">{{ $activity->formattedType }}</a>
	                                </div>
		                        </td>
		                        <td>{{ $activity->coach->full_name }}</td>
		                        <td>{{ $activity->formattedStartDayMonth }}</td>
		                        <td>{{ $activity->formattedStartTime }}</td>
		                        <td>{{ $activity->formattedLocation }}</td>
		                        <td class="text-right">
		                            <a class="button button--primary" href="{{ $activity->notificationLink }}">View</a>
		                        </td>
		                    </tr>
		                @endforeach
		            </tbody>
		        </table>
		    </div>
		</div>
	</div>

	@if($jockey->lastFiveRacingExcellence->count())
		<div class="panel panel--is-stack">
		    <div class="panel__inner">
		        <div class="panel__header">
		            <h2 class="panel__heading">
		                Recent Excellence
		            </h2>
		        </div>
				<div class="panel__main">
			        <table class="table">
			            <thead>
			                <tr>
			                    <th>Series</th>
			                    <th>Coach</th>
			                    <th>Date</th>
			                    <th>Time</th>
			                    <th>Location</th>
			                    <th></th>
			                </tr>
			            </thead>
			            <tbody>
			                @foreach($jockey->lastFiveRacingExcellence as $race)	                            
			                    <tr>
			                        <td>

			                            <a class="table__link" href="">{{ $race->formattedSeriesName }}</a>
			                        </td>
			                        <td>{{ optional($race->coach)->full_name }}</td>
			                        <td>{{ $race->formattedStartDayMonth }}</td>
			                        <td>{{ $race->formattedStartTime }}</td>
			                        <td>{{ $race->formattedLocation }}</td>
			                        <td class="text-right">
			                            <a class="button button--primary" href="{{ route('racing-excellence.results.create', $race) }}">View</a>
			                        </td>
			                    </tr>
			                @endforeach
			            </tbody>
			        </table>
			    </div>
			</div>
		</div>
	@endif

	@if($jockey->lastSkillProfile)
		<div class="panel panel--is-stack">
		    <div class="panel__inner">
		        <div class="panel__header">
		            <h2 class="panel__heading">
		                Skills Profile
		            </h2>
		        </div>
		    	<div class="panel__main flow-vertical--3">
		    	
		            <div class="bar-chart">

		                @foreach(config('jcp.skills_profile_fields') as $field)
		                    <div class="bar-chart__row">
		                        <div class="bar-chart__label">
		                            {{ $field['label'] }}
		                            <span class="sr-only">: 4</span>
		                        </div>
		                        <div class="[ bar-chart__bar ] [ progress-bar progress-bar--trans ]" aria-hidden="true" role="presentation">
		                            <div class="progress-bar__bar">
		                                <div class="progress-bar__primary" style="width: {{ ($jockey->lastSkillProfile->{$field['field']}/5) * 100 }}%;"></div>
		                            </div>
		                        </div>
		                    </div>
		                @endforeach

		                <div class="bar-chart__x-axis" aria-hidden="true" role="presentation">
		                    <div class="bar-chart__x-axis-blank"></div>
		                    <div class="bar-chart__x-axis-labels">
		                        <div class="bar-chart__x-axis-labels-inner">
		                            @for ($i = 0; $i <= 5; $i++)
		                                <span>{{ $i }}</span>
		                            @endfor
		                        </div>
		                    </div>
		                </div>
		            </div>
		      	</div>
		    </div>
		</div>
    @endif	
@endif
@endsection

