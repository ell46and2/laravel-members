@extends('layouts.base')

@section('content')

<div class="pt-1 pb-3 flow-vertical--1">
	@include('layouts.partials._user-bar')

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
		                    <dd>Apprentice</dd>

		                    <dt>Season rides</dt>
		                    <dd>51</dd>

		                    <dt>Season wins</dt>
		                    <dd>376</dd>

		                    <dt>Prize money</dt>
		                    <dd>&pound;306,146.25</dd>
		                </dl>
		            </div>
		            <div>
		                <dl class="definition-list">
		                    <dt>Current trainer</dt>
		                    <dd>Name Surname</dd>

		                    <dt>Riding weight</dt>
		                    <dd>8st 5lbs</dd>

		                    <dt>Associated content</dt>
		                    <dd><a class="link--underlined" href="">Stewards enquiries/reports</a></dd>
		                </dl>
		            </div>
		        </div>
		    </div>
		</div>

		<div class="alert alert--error">
		    <div>
		        You have <span class="badge badge-light">{{ $numUnreadMessages }} new</span> unread {{ str_plural('message', $numUnreadMessages) }}
		    </div>
		    <div>
		        <a class="button button--white" href="{{ route('messages.index') }}">View messages</a>
		    </div>
		</div>

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
		                                        <a class="button button--primary" href="{{ route('jockey.activity.show', $activity) }}">View</a>
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

		        
		        {{-- Panel - Competency assessment --}}
		        <div class="panel">
		            <div class="panel__inner">
		                <div class="panel__header">
		                    <h2 class="panel__heading">
		                        Competency assessment
		                    </h2>
		                    <div class="panel__header-meta">
		                        Last updated on 23/05/2018
		                    </div>
		                </div>

		                <div class="panel__main">
		                    <div class="bar-chart">

		                        {{-- {% for item in content.competency_assessment.items %}
		                            <div class="bar-chart__row">
		                                <div class="bar-chart__label">
		                                    {{ item.label }}
		                                    <span class="sr-only">: 4</span>
		                                </div>
		                                <div class="[ bar-chart__bar ] [ progress-bar progress-bar--trans ]" aria-hidden="true" role="presentation">
		                                    <div class="progress-bar__bar">
		                                        <div class="progress-bar__primary" style="width: {{ (item.value/content.competency_assessment.max_value) * 100 }}%;"></div>
		                                    </div>
		                                </div>
		                            </div>
		                        {% endfor %} --}}

		                        <div class="bar-chart__x-axis" aria-hidden="true" role="presentation">
		                            <div class="bar-chart__x-axis-blank"></div>
		                            <div class="bar-chart__x-axis-labels">
		                                <div class="bar-chart__x-axis-labels-inner">
		                                    {{-- {% for i in range(0, content.competency_assessment.max_value + 1) %}
		                                        <span>{{ i }}</span>
		                                    {% endfor %} --}}
		                                </div>
		                            </div>
		                        </div>

		                    </div>
		                </div>

		                <a class="panel__call-to-action" href="">View all competency assessments</a>
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

		                        @foreach($jockey->coaches as $coach)
		                            <div class="users-list__item">
		                                <div class="users-list__main">
		                                    <div class="[ users-list__avatar ] [ avatar ]">
		                                        <div class="avatar__image" style="background-image:url({{ $coach->getAvatar() }});"></div>
		                                    </div>
		                                    <div class="users-list__details">
		                                        <div class="users-list__name">
		                                            {{ $coach->full_name }}
		                                            {{-- <a class="users-list__edit-user-button" href=""> --}}
		                                            	{{-- @svg('edit', 'icon') --}}
		                                                {{-- {% include "svg/edit.svg" %} --}}
		                                            {{-- </a> --}}
		                                        </div>
		                                        <div class="users-list__info">{{ $coach->trainingTimeWithJockeyThisMonth($jockey->id) }} hours coaching this month</div>
		                                    </div>
		                                </div>
		                                <div class="users-list__stats">
		                                    <div class="users-list__stats-item">
		                                        <div class="users-list__stats-icon">
		                                            {{-- {% include "svg/nav-my-coaches.svg" %} --}}
		                                        </div>
		                                        <div class="users-list__stats-label">
		                                            20 jockeys assigned
		                                        </div>
		                                    </div>
		                                    <div class="users-list__stats-item">
		                                        <div class="users-list__stats-icon">
		                                            {{-- {% include "svg/nav-my-coaches.svg" %} --}}
		                                        </div>
		                                        <div class="users-list__stats-label">
		                                            1 pending invoice
		                                        </div>
		                                    </div>
		                                </div>
		                                <div class="[ users-list__progress-bar ] [ progress-bar ]">
		                                    <span class="sr-only">Progress: 70%</span>
		                                    <div class="progress-bar__bar" aria-hidden="true" role="presentation">
		                                        <div class="progress-bar__primary" style="width: 60%;"></div>
		                                        <div class="progress-bar__secondary" style="width: 80%;"></div>
		                                    </div>
		                                    <div class="progress-bar__labels">
		                                        <div>0 hours</div>
		                                        <div>6 hours</div>
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

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">

					<img src="{{ $jockey->getAvatar() }}" alt="{{ $jockey->full_name }}">
					Hi {{ $jockey->full_name }}, this is your Jockey Dashboard

					<div>
						number of wins
					</div>
					<div>
						number of races
					</div>
					<div>
						{{ $jockey->trainingTimeThisMonth() }} Hours of coaching with all my coaches this month
					</div>
					<br><br>
					<div>
						<h3>Coaches</h3>

						@foreach($jockey->coaches as $coach)
							<div>
								{{ $coach->full_name }} <br>
								{{ $coach->trainingTimeWithJockeyThisMonth($jockey->id) }} hours coaching this month
							</div><br><br>
						@endforeach
					</div>
					<br>
					<div>
						<h3>Upcoming Activities</h3>
						{{-- Split out into a partial --}}
						@foreach($jockey->dashboardUpcomingActivities as $activity)
							{{ $activity->id }}<br>
							{{ $activity->formattedType }}<br>
							{{ $activity->start->format('d/m/Y') }}<br>
							{{ $activity->start->format('H:i') }}<br>
							{{ $activity->formattedLocation }}<br>
							<a href="{{ route('jockey.activity.show', $activity) }}">View</a>
							<br><br>
						@endforeach
					</div>
					<br>
					<div>
						<h3>Recent Activities</h3>
						{{-- Split out into a partial --}}
						@foreach($jockey->dashboardRecentActivities as $activity)
							{{ $activity->id }}<br>
							{{ $activity->formattedType }}<br>
							{{ $activity->start->format('d/m/Y') }}<br>
							{{ $activity->start->format('H:i') }}<br>
							{{ $activity->formattedLocation }}<br>
							<a href="{{ route('jockey.activity.show', $activity) }}">View</a>
							<br><br>
						@endforeach
					</div>
				</div>
            </div>
        </div>
    </div>
</div>

@endsection