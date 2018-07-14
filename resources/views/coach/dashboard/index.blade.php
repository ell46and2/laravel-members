@extends('layouts.base')

@section('content')

{{-- <div class="nummessages">
	{{-- pass auth()->user()->unreadMessagesCount() into layout partial via view composer --}}
	{{-- You have {{ auth()->user()->unreadMessagesCount() }} unread {{ str_plural('message', auth()->user()->unreadMessagesCount()) }} --}}
{{-- </div> --}}

<div class="pt-1 pb-3 flow-vertical--1">
	<div class="[ user-bar ] [ flow-horizontal--1 ]">
	    <div class="[ user-bar__avatar ] [ avatar ]">
	        <div class="avatar__image" style="background-image:url({{ auth()->user()->getAvatar() }});"></div>
	    </div>
	    <span class="user-bar__name">{{ auth()->user()->full_name }}</span>
	    <span class="user-bar__sign-out">
	    	Not you? Sign out 
	    	<a 
	    		class="link--underlined font-bold" 
	    		href="{{ route('logout') }}"
	    		onclick="event.preventDefault();
                document.getElementById('logout-form').submit();"
	    	>here</a>.
	    </span>
	    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
	</div>


	<div class="flow-vertical--2">
		<div class="panel">
	        <div class="panel__inner">
	            <div class="panel__main panel__main--3-columns">
	                <div class="profile-summary">
	                    <div class="[ profile-summary__avatar ] [ avatar avatar--red ]">
	                        <div class="avatar__image" style="background-image:url({{ auth()->user()->getAvatar() }});"></div>
	                    </div>
	                    <div class="profile-summary__main">
	                        <h2 class="profile-summary__greeting-primary">Hi {{ $coach->full_name }},</h2>
	                        <div class="profile-summary__greeting-secondary">Welcome back to your coaching dashboard</div>
	                        <div>
	                            <a class="button button--primary" href="">View profile</a>
	                        </div>
	                    </div>
	                </div>
	                <div>
	                    {{-- <dl class="definition-list">
	                        <dt>Licence type</dt>
	                        <dd>Apprentice</dd>

	                        <dt>Season rides</dt>
	                        <dd>51</dd>

	                        <dt>Season wins</dt>
	                        <dd>376</dd>

	                        <dt>Prize money</dt>
	                        <dd>&pound;306,146.25</dd>
	                    </dl> --}}
	                </div>
	                <div>
	                    {{-- <dl class="definition-list">
	                        <dt>Current trainer</dt>
	                        <dd>Name Surname</dd>

	                        <dt>Riding weight</dt>
	                        <dd>8st 5lbs</dd>

	                        <dt>Associated content</dt>
	                        <dd><a class="link--underlined" href="">Stewards enquiries/reports</a></dd>
	                    </dl> --}}
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
	             {{-- Panel - Recent Activities --}}
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
	                                {{-- {% include "svg/angle-left.svg" %} --}}
	                            </button>
	                            <button class="panel__nav-button" type="button">
	                                <span class="sr-only">Next</span>
	                                @svg('angle-right', 'icon')
	                                {{-- {% include "svg/angle-right.svg" %} --}}
	                            </button>
	                        </div>
	                    </div>

	                    <div class="panel__main">
	                        <table class="table">
	                            <thead>
	                                <tr>
	                                    <th>Activity type</th>
	                                    <th>Jockey</th>
	                                    <th>Date</th>
	                                    <th>Time</th>
	                                    <th>Location</th>
	                                    <th></th>
	                                </tr>
	                            </thead>
	                            <tbody>
	                                @foreach($coach->dashboardRecentActivities as $activity)
	                                    <tr>
	                                        <td>
	                                            <a class="table__link" href="">{{ $activity->formattedType }}</a>
	                                        </td>
	                                        <td>{{ $activity->formattedJockeyOrGroup }}</td>
	                                        <td>{{ $activity->formattedStartDayMonth }}</td>
	                                        <td>{{ $activity->formattedStartTime }}</td>
	                                        <td>{{ $activity->formattedLocation }}</td>
	                                        <td class="text-right">
	                                            <a class="button button--primary" href="{{ route('coach.activity.show', $activity) }}">View</a>
	                                        </td>
	                                    </tr>
	                                @endforeach
	                            </tbody>
	                        </table>
	                    </div>

	                    <a class="panel__call-to-action" href="{{ route('coach.activity-log') }}">View all activities</a>
	                </div>
	                <button class="panel__stack" type="button">
	                    <span class="sr-only">Go to next page</span>
	                </button>
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

					<img src="{{ $coach->getAvatar() }}" alt="{{ $coach->full_name }}">
					Hi {{ $coach->full_name }}, Welcome back to your coaching dashboard

					<br><br>
					<div>
						<h3>Jockeys</h3>

						@foreach($coach->jockeys as $jockey)
							<div>
								{{ $jockey->full_name }} <br>
								{{ $coach->lastActivityDateColourCode($jockey) }} <br>
								{{ $coach->trainingTimeWithJockeyThisMonth($jockey->id) }} Hours coaching this month
								<br>
								Total training time is {{ $jockey->trainingTimeThisMonth() }}
								<br>
								@if($jockey->coaches->count() > 1)
									{{ $jockey->coaches->count() }} Coaches Assigned
								@endif
							</div><br><br>
						@endforeach
					</div>
					<br>
					<div>
						<h3>Upcoming Activities</h3>
						{{-- Split out into a partial --}}
						@foreach($coach->dashboardUpcomingActivities as $activity)
							{{ $activity->id }}<br>
							{{ $activity->formattedType }}<br>
							{{ $activity->formattedStartDayMonth }}<br>
							{{ $activity->start->format('H:i') }}<br>
							{{ $activity->formattedLocation }}<br>
							<a href="{{ route('coach.activity.show', $activity) }}">View</a>
							<br><br>
						@endforeach
					</div>
					<br>
					<div>
						<h3>Recent Activities</h3>
						{{-- Split out into a partial --}}
						@foreach($coach->dashboardRecentActivities as $activity)
							{{ $activity->id }}<br>
							{{ $activity->formattedType }}<br>
							{{ $activity->start->format('d/m/Y') }}<br>
							{{ $activity->start->format('H:i') }}<br>
							{{ $activity->formattedLocation }}<br>
							<a href="{{ route('coach.activity.show', $activity) }}">View</a>
							<br><br>
						@endforeach
					</div>

					<br><br><br>
					<h3>Your Invoices</h3>
					<div>
						Activities completed but yet to be invoiced
						{{ $coach->activitiesNotYetInvoicedCount() }}
					</div>
					<div>
						Racing Excellence completed but yet to be invoiced
						{{ $coach->racingExcellencesNotYetInvoicedCount() }}
					</div>
					<div>
						Days left to submit invoice for {{ currentInvoicingMonth() }} invoicing period. <br>
						{{ daysToSubmitInvoice() }} {{ str_plural('Day', daysToSubmitInvoice()) }}
					</div>
				</div>			
            </div>
        </div>
    </div>
</div>

{{-- <notifications
	resource="{{ json_encode($notificationsResource) }}"
></notifications> --}}

@endsection