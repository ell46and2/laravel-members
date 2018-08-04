@extends('layouts.base')

@section('content')


	<div class="flow-vertical--3 pb-3">
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
	                            <a class="button button--primary" href="{{ route('coach.show', $coach) }}">View profile</a>
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

	    @include('layouts.partials._messages-alert')

	    <div class="row row--wide-gutter">
	        <div class="[ col-md-12 col-xl-8 mb-2 mb-xl-0 ] [ flow-vertical--3 ]">  
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
	                                    <th>Jockey</th>
	                                    <th>Date</th>
	                                    <th>Time</th>
	                                    <th>Location</th>
	                                    <th></th>
	                                    <th></th>
	                                </tr>
	                            </thead>
	                            <tbody>
	                                @foreach($coach->dashboardRecentActivities as $activity)
	                                    <tr>
	                                        <td>
	                                        	<div class="d-flex align-items-center">
				                                    <span class="table__icon">
				                                        @svg( $activity->icon, 'icon')
				                                    </span>
				                                    <a class="table__link" href="">{{ $activity->formattedType }}</a>
				                                </div>
	                                        </td>
	                                        <td>{{ $activity->formattedJockeyOrGroup }}</td>
	                                        <td>{{ $activity->formattedStartDayMonth }}</td>
	                                        <td>{{ $activity->formattedStartTime }}</td>
	                                        <td>{{ $activity->formattedLocation }}</td>
	                                        <td class="table__icon-column">
                                                <a class="table__icon-button" href="{{ route('coach.activity.edit', $activity) }}">
                                                    @svg( 'edit', 'icon')
                                                </a>
                                            </td>
	                                        <td class="text-right">
	                                            <a class="button button--primary" href="{{ route('activity.show', $activity) }}">View</a>
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

				{{-- Invoices --}}
	            <div class="panel">
	                <div class="panel__inner">
	                    <div class="panel__header">
	                        <h2 class="panel__heading">
	                            Your invoices
	                        </h2>
	                    </div>

	                    <div class="panel__main flow-vertical--3">
	                        <div class="invoices-summary">
	                            <div class="invoices-summary__columns">
	                                <div class="invoices-summary__column">
	                                    <div class="invoices-summary__column-heading">
	                                        Activities
	                                    </div>
	                                    <div class="invoices-summary__column-main">
	                                        <div class="invoices-summary__description">
	                                            Activities completed but yet to be invoiced
	                                        </div>
	                                        <div class="[ d-flex align-items-start ] [ mt-2 ]">
	                                            <div class="invoices-summary__key-stat">
	                                                <span class="heading--1">{{ $coach->activitiesNotYetInvoicedCount() }}</span> activities
	                                            </div>
	                                            @if($latestOpenInvoice)
	                                            	<div class="text-right">
		                                                <a class="button button--primary" href="{{ route('invoice.add-lines', $latestOpenInvoice) }}">View</a>
		                                            </div>
	                                            @endif   
	                                        </div>
	                                    </div>
	                                </div>
	                                <div class="invoices-summary__column">
	                                    <div class="invoices-summary__column-heading">
	                                        Racing Excellence
	                                    </div>
	                                    <div class="invoices-summary__column-main">
	                                        <div class="invoices-summary__description">
	                                            Racing Excellence completed but yet to be invoiced
	                                        </div>
	                                        <div class="[ d-flex align-items-start ] [ mt-2 ]">
	                                            <div class="invoices-summary__key-stat">
	                                                <span class="heading--1">{{ $coach->racingExcellencesNotYetInvoicedCount() }}</span> races
	                                            </div>
	                                            @if($latestOpenInvoice)
	                                            	<div class="text-right">
		                                                <a class="button button--primary" href="{{ route('invoice.add-lines', $latestOpenInvoice) }}">View</a>
		                                            </div>
	                                            @endif
	                                        </div>
	                                    </div>
	                                </div>
	                                <div class="invoices-summary__column">
	                                    <div class="invoices-summary__column-heading">
	                                        Days left to submit
	                                    </div>
	                                    <div class="invoices-summary__column-main">
	                                        <div class="invoices-summary__description">
	                                            Days left to submit invoice for {{ currentInvoicingMonth() }} invoicing period.
	                                        </div>
	                                        <div class="[ d-flex align-items-start ] [ mt-2 ]">
	                                            <div class="invoices-summary__key-stat">
	                                                <span class="heading--1">{{ daysToSubmitInvoice() }}</span> {{ str_plural('Day', daysToSubmitInvoice()) }}
	                                            </div>
	                                            <div class="text-right">
	                                            	@if(!$latestOpenInvoice && !withinInvoicingPeriod(now()->day))
	                                            		<a class="button button--primary" href="{{ route('invoice.index', $coach) }}">Create invoice</a>
	                                            	@elseif($latestOpenInvoice)
														<a class="button button--primary" href="{{ route('invoice.show', $latestOpenInvoice) }}">View</a>
	                                            	@endif
	                                                
	                                            </div>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>


	                            @if($lastInvoice)
	                            	<div class="invoices-summary__last-invoice">
		                                <span>
		                                    Your last invoice for {{ $lastInvoice->invoicePeriodMonthSubmitted }} was <span class="[ heading--1 ] [ text--color-blue-dark ]">&pound;{{ $lastInvoice->overallValue }}</span> <span class="text--size-lg text--color-blue-dark">{{ $lastInvoice->formattedStatus }}</span>
		                                </span>
		                                <span>
		                                    Submitted on <span class="text--size-lg text--color-blue-dark">{{ $lastInvoice->submittedDateShort }}</span>
		                                </span>
		                                <span>
		                                    Invoice number <span class="text--size-lg text--color-blue-dark">{{ $lastInvoice->id }}</span>
		                                </span>
		                                <a class="button button--primary" href="{{ route('invoice.show', $lastInvoice) }}">View invoice</a>
		                            </div>
	                            @endif
	                            
	                        </div>
	                    </div>

	                    <a class="panel__call-to-action" href="{{ route('invoice.index', $coach) }}">View all invoices</a>
	                </div>
	            </div>
			</div>


			<div class="[ col-md-12 col-xl-4 ] [ flow-vertical--3 ]">
			    {{-- Button - Add 1:1 Activity & Add Group Activity --}}
			    <div class="flow-vertical--2">
			        <a href="{{ route('coach.1:1-activity.create') }}" class="button button--primary button--block">Add 1:1 Activity</a>
			        <a href="{{ route('coach.group-activity.create') }}" class="button button--primary button--block">Add Group Activity</a>
			    </div>


			    {{-- Panel - My Coaches --}}
			    <div class="panel">
			        <div class="panel__inner">
			            <div class="panel__header">
			                <h2 class="panel__heading">
			                    Most Active Jockeys
			                </h2>
			                <div class="panel__header-legend">
			                    <div class="panel__header-legend-item panel__header-legend-item--primary">
			                        Your coaching
			                    </div>
			                    <div class="panel__header-legend-item panel__header-legend-item--secondary">
			                        Others
			                    </div>
			                </div>
			            </div>

			            <div class="panel__main">
			                <div class="users-list">

			                	{{-- @foreach($coach->jockeys as $jockey)
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
								@endforeach --}}

			                    @foreach($coach->jockeys as $jockey)
			                    	@php
			                    		$trainingAllowance = $jockey->trainingAllowanceThisMonth();
			                        	$trainingWithCoach = $coach->trainingTimeWithJockeyThisMonth($jockey->id);
			                        	$trainingPercentage = getPercentage($trainingWithCoach->duration, $trainingAllowance);
			                        	$overallTrainingPercentage = getPercentage($jockey->trainingTimeThisMonth(), $trainingAllowance);
			                    	@endphp
			                        <div class="users-list__item">
			                            <div class="users-list__main">
			                                <div class="[ users-list__avatar ] [ avatar avatar--{{ $coach->lastActivityDateColourCode($jockey) }} ]">
			                                    <div class="avatar__image" style="background-image:url('{{ $jockey->getAvatar() }}');"></div>
			                                </div>
			                                <div class="users-list__details">
			                                    <div class="users-list__name">
			                                        {{ $jockey->full_name }}
			                                    </div>
			                                    <div class="users-list__info">{{ $trainingWithCoach->duration }} hours coaching this month</div>
			                                </div>
			                                <a class="button button--primary" href="">Message</a>
			                            </div>
			                            <div class="users-list__stats">
			                                <div class="users-list__stats-item">
			                                    <div class="users-list__stats-icon">
			                                        @svg( 'user-activity', 'icon')
			                                    </div>
			                                    <div class="users-list__stats-label">
			                                        {{ $trainingWithCoach->numActivities }} Activities
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="[ users-list__progress-bar ] [ progress-bar ]">
			                                <span class="sr-only">Progress: {{ $trainingPercentage }}%</span>
			                                <div class="progress-bar__bar" aria-hidden="true" role="presentation">
			                                    <div class="progress-bar__primary" style="width: {{ $trainingPercentage }}%;"></div>
			                                    <div class="progress-bar__secondary" style="width: {{ $overallTrainingPercentage }}%;"></div>
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

			            <a class="panel__call-to-action" href="">View my jockeys</a>
			        </div>
			    </div>
			</div>

		</div>
	</div>

@endsection