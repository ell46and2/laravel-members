@extends('layouts.base')

@section('content')

<div class="pt-1 pb-3 flow-vertical--1">
	

	<div class="flow-vertical--2">
		<div class="panel">
		    <div class="panel__inner">
		        <div class="panel__main panel__main--3-columns">
		            <div class="profile-summary">
		                <div class="[ profile-summary__avatar ] [ avatar ]">
		                    <div class="avatar__image" style="background-image:url({{ auth()->user()->getAvatar() }});"></div>
		                </div>
		                <div class="profile-summary__main">
		                    <h2 class="profile-summary__greeting-primary">Hi {{ $jockey->full_name }},</h2>
		                    <div class="profile-summary__greeting-secondary">Welcome back to your Jockey dashboard</div>
		                    <div>
		                        <a class="button button--primary" href="{{ route('jockey.profile.index') }}">View profile</a>
		                    </div>
		                </div>
		            </div>
		            <div>
		                <dl class="definition-list">
		                    <dt>Licence type</dt>
		                    <dd>{{ $jockey->formattedLicenceType }}</dd>

		                    <dt>Season rides</dt>
		                    <dd>{{ $jockey->formattedRides }}</dd>

		                    <dt>Season wins</dt>
		                     <dd>{{ $jockey->formattedWins }}</dd>

		                    <dt>Prize money</dt>
		                    <dd>{{ $jockey->formattedPrizeMoney }}</dd>
		                </dl>
		            </div>
		            <div>
		                <dl class="definition-list">
		                    <dt>Current trainer</dt>
		                    <dd>{{ $jockey->formattedTrainer }}</dd>

		                    <dt>Riding weight</dt>
		                    <dd>8st 5lbs</dd>

		                    <dt>Associated content</dt>
		                    <dd><a class="link--underlined" href="">Stewards enquiries/reports</a></dd>
		                </dl>
		            </div>
		        </div>
		    </div>
		</div>
			
		@include('layouts.partials._messages-alert')
		

		<div class="row">
		    <div class="[ col-md-12 col-xl-8 mb-2 mb-xl-0 ] [ flow-vertical--2 ]">
		        {{--  Panel - Recent Activities --}}
		        <div class="panel panel--is-stack">
		            <div class="panel__inner">
		                <div class="panel__header">
		                    <h2 class="panel__heading">
		                        Recent Activities
		                    </h2>
		                    <div class="panel__nav-buttons">
		                        <button class="panel__nav-button" type="button" disabled>
		                            <span class="sr-only">Previous</span>
		                            @svg('angle-left', 'icon')
		                        </button>
		                        <button class="panel__nav-button" type="button">
		                            <span class="sr-only">Next</span>
		                            @svg('angle-right', 'icon')
		                        </button>
		                    </div>
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
		                            @foreach($jockey->dashboardRecentActivities as $activity)	                            
		                                <tr>
		                                    <td>
		                                        <a class="table__link" href="">{{ $activity->formattedType }}</a>
		                                    </td>
		                                    <td>{{ $activity->coach->full_name }}</td>
		                                    <td>{{ $activity->formattedStartDayMonth }}</td>
		                                    <td>{{ $activity->formattedStartTime }}</td>
		                                    <td>{{ $activity->formattedLocation }}</td>
		                                    <td class="text-right">
		                                        <a class="button button--primary" href="{{ route('activity.show', $activity) }}">View</a>
		                                    </td>
		                                </tr>
		                            @endforeach
		                        </tbody>
		                    </table>
		                </div>

		                <a class="panel__call-to-action" href="{{ route('jockey.activity-log') }}">View all activities</a>
		            </div>
		            <button class="panel__stack" type="button">
		                <span class="sr-only">Go to next page</span>
		            </button>
		        </div>

		        
		        {{-- Panel - Skills Profile --}}
		        <div class="panel">
		            <div class="panel__inner">
		                <div class="panel__header">
		                    <h2 class="panel__heading">
		                        Skills Profile
		                    </h2>
		                    @if($jockey->lastSkillProfile)
		                    	<div class="panel__header-meta">
			                        Last updated on {{ $jockey->lastSkillProfile->updatedAtshortDate }}
			                    </div>
		                    @endif               
		                </div>

		                <div class="panel__main flow-vertical--3">
		                	@if($jockey->lastSkillProfile)
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
			                @else
			                	<p>No Skills Profile</p>
		                    @endif
		                    <div class="row">
		                        <div class="col-8">
		                            In <span class="heading--1">48</span> days you will need to complete a Skills Profile
		                        </div>
		                        <div class="col-4 text-right">
		                            <button class="button button--primary" type="button">Complete</button>
		                        </div>
		                    </div>
		                </div>

		                <a class="panel__call-to-action" href="">View All Skills Profile</a>
		            </div>
		        </div>
		    </div>

		    <div class="col-md-12 col-xl-4">        
		        {{-- Panel - My Coaches --}}
		        <div class="panel panel--is-stack">
		            <div class="panel__inner">
		                <div class="panel__header">
		                    <h2 class="panel__heading">
		                        My Coaches
		                    </h2>
		                </div>

		                <div class="panel__main">
		                    <div class="users-list">
		                    		@php
									    $trainingAllowance = $jockey->trainingAllowanceThisMonth();

									@endphp 

		                        @foreach($jockey->coaches as $coach)
									@php
									    $trainingTimeWithCoach = $coach->trainingTimeWithJockeyThisMonth($jockey->id);

									    $trainingPercentage = getPercentage($trainingTimeWithCoach->duration, $trainingAllowance);
									@endphp 

		                            <div class="users-list__item">
		                                <div class="users-list__main">
		                                    <div class="[ users-list__avatar ] [ avatar avatar--{{ $coach->lastActivityDateColourCode($jockey) }}  }} ]">
		                                        <div class="avatar__image" style="background-image:url({{ $coach->getAvatar() }});"></div>
		                                    </div>
		                                    <div class="users-list__details">
		                                        <div class="users-list__name">
		                                            {{ $coach->full_name }}
		                                        </div>
		                                        <div class="users-list__info">{{ $trainingTimeWithCoach->duration }} hours coaching this month</div>
		                                    </div>
		                                    <a class="button button--primary" href="{{ route('coach.show', $coach) }}">View</a>
		                                </div>
		                                <div class="users-list__stats">
		                                    <div class="users-list__stats-item">
		                                        <div class="users-list__stats-icon">
		                                            {{-- {% include "svg/nav-my-coaches.svg" %} --}}
		                                            @svg('nav-activity-log', 'icon')
		                                        </div>
		                                        <div class="users-list__stats-label">
		                                            {{ $trainingTimeWithCoach->numActivities }} Activities
		                                        </div>
		                                    </div>
		                                </div>
		                                <div class="[ users-list__progress-bar ] [ progress-bar ]">
		                                    <span class="sr-only">Progress: 70%</span>
		                                    <div class="progress-bar__bar" aria-hidden="true" role="presentation">
		                                        <div class="progress-bar__primary" style="width: {{ $trainingPercentage }}%;"></div>
		                                        {{-- <div class="progress-bar__secondary" style="width: 80%;"></div> --}}
		                                    </div>
		                                    <div class="progress-bar__labels">
		                                        <div>0 hours</div>
		                                        <div>{{ $trainingAllowance }} hours</div>
		                                    </div>
		                                </div>
		                            </div>
		                        @endforeach
		                    </div>
		                </div>

		                <a class="panel__call-to-action" href="">View my coaches</a>
		            </div>
		        </div>
		    </div>
		</div>
	</div>

</div>


@endsection