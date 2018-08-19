@extends('layouts.base')

@section('content')

<div class="flow-vertical--3 pb-3">

     {{-- Panel - Top --}}
	<div class="panel">
	    <div class="panel__inner">
	        <div class="panel__main panel__main--3-columns">
	            <div class="profile-summary">
	                <div class="[ profile-summary__avatar ] [ avatar ]">
	                    <div class="avatar__image" style="background-image:url({{ $admin->getAvatar() }});"></div>
	                </div>
	                <div class="profile-summary__main">
	                    <h2 class="profile-summary__greeting-primary">Hi {{ $admin->full_name }},</h2>
	                    <div class="profile-summary__greeting-secondary">Welcome back to your Admin dashboard</div>
	                    <div>
	                        <button class="button button--primary" type="button">View profile</button>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>

	<div class="row row--wide-gutter">
	    <div class="[ col-md-12 col-xl-8 mb-2 mb-xl-0 ] [ flow-vertical--3 ]">

	        {{-- Panel - Recent Activities --}}
	        <div class="panel panel--is-stack">
	            <div class="panel__inner">
	                <div class="panel__header">
	                    <h2 class="panel__heading">
	                        Jockeys Awaiting Approval
	                    </h2>
	                   {{--  <div class="panel__nav-buttons">
	                        <button class="panel__nav-button" type="button" disabled>
	                            <span class="sr-only">Previous</span>
	                            {% include "svg/angle-left.svg" %}
	                        </button>
	                        <button class="panel__nav-button" type="button">
	                            <span class="sr-only">Next</span>
	                            {% include "svg/angle-right.svg" %}
	                        </button>
	                    </div> --}}
	                </div>

	                <div class="panel__main">
	                    <table class="table table-hover">
	                        <thead>
	                            <tr>
	                                <th>Jockey</th>
	                                <th>Status</th>
	                                <th>Last Modified</th>
	                                <th>Joined</th>
	                                <th></th>
	                                <th></th>
	                            </tr>
	                        </thead>
	                        <tbody>
	                            @foreach($jockeysAwaitingApproval as $jockey)
	                                <tr>
	                                    <td>
	                                        <div class="d-flex align-items-center">
	                                            <div class="[ table__avatar ] [ avatar ]">
	                                                <div class="avatar__image" style="background-image:url({{ $jockey->getAvatar() }});"></div>
	                                            </div>
	                                            <a class="table__link" href="">{{ $jockey->full_name }}</a>
	                                        </div>
	                                    </td>
	                                    <td>Awaiting Approval</td>
	                                    <td>{{ $jockey->formattedLastModified }}</td>
	                                    <td>{{ $jockey->formattedJoined }}</td>
	                                    <td class="table__icon-column">
	                                        <a class="table__icon-button" href="">
	                                            @svg('edit', 'icon')
	                                        </a>
	                                    </td>
	                                    <td class="text-right">
	                                        <a class="button button--primary" href="">View</a>
	                                    </td>
	                                </tr> 
								@endforeach	                         
	                        </tbody>
	                    </table>
	                </div>

	                <a class="panel__call-to-action" href="">View all Jockeys</a>
	            </div>
	            {{-- <button class="panel__stack" type="button">
	                <span class="sr-only">Go to next page</span>
	            </button> --}}
	        </div>


	        <div class="row">

	             {{-- Panel - Invoices To Process --}}
	            <div class="col-6">
	                <div class="panel">
	                    <div class="panel__inner">
	                        <div class="panel__header">
	                            <h2 class="panel__heading">
	                                Invoices To Process
	                            </h2>
	                        </div>

	                        <div class="panel__main flow-vertical--3">
	                            <table class="table table-hover">
	                                <thead>
	                                    <tr>
	                                        <th>Coach</th>
	                                        <th>Submited Date</th>
	                                        <th>Amount</th>
	                                        <th></th>
	                                    </tr>
	                                </thead>
	                                <tbody>
	                                    @foreach($invoicesAwaitingReview->take(4) as $invoice)
	                                        <tr>
	                                            <td>{{ $invoice->coach->full_name }}</td>
	                                            <td>{{ $invoice->submittedDateShort }}</td>
	                                            <td>{{ $invoice->overallValue }}</td>
	                                            <td class="text-right">
	                                                <a class="button button--primary" href="">View</a>
	                                            </td>
	                                        </tr>
	                                    @endforeach
	                                </tbody>
	                            </table>
	                            <div class="row">
	                                <div class="col-8">
	                                    You have <span class="heading--1">{{ $invoicesAwaitingReview->count() }}</span> invoices ready for review.
	                                </div>
	                                <div class="col-4 text-right">
	                                    <button type="button" name="button" class="button button--primary">Review</button>
	                                </div>
	                            </div>
	                        </div>

	                        <a class="panel__call-to-action" href="">View all Invoices</a>
	                    </div>
	                </div>
	            </div>

	             {{-- Panel - Racing Excellence --}}
	            <div class="col-6">
	                <div class="panel">
	                    <div class="panel__inner">
	                        <div class="panel__header">
	                            <h2 class="panel__heading">
	                                Racing Excellence awaiting coach
	                            </h2>
	                        </div>

	                        <div class="panel__main flow-vertical--3">
	                            <table class="table table-hover">
	                                <thead>
	                                    <tr>
	                                        <th>location</th>
	                                        <th>Date</th>
	                                        <th>No of Jockeys</th>
	                                        <th></th>
	                                    </tr>
	                                </thead>
	                                <tbody>
	                                	@foreach($racesAwaitingCoachAssignment->take(4) as $race)
	                                        <tr>
	                                            <td>{{ $race->formattedLocation }}</td>
	                                            <td>{{ $race->startDate }}</td>
	                                            <td>{{ $race->participants->count() }}</td>
	                                            <td class="text-right">
	                                                <a class="button button--primary" href="{{ route('racing-excellence.show', $race) }}">View</a>
	                                            </td>
	                                        </tr>
	                                    @endforeach
	                                </tbody>
	                            </table>
	                            <div class="row">
	                                <div class="col-8">
	                                    Name, you have <span class="heading--1">{{ $racesAwaitingCoachAssignment->count() }}</span> upcoming racing excellence events awaiting coach assignment.
	                                </div>
	                                <div class="col-4 text-right">
	                                    <button type="button" name="button" class="button button--primary">Review</button>
	                                </div>
	                            </div>
	                        </div>

	                        <a class="panel__call-to-action" href="">View all Racing Excellence</a>
	                    </div>
	                </div>
	            </div>
	        </div>



	    </div>
	    <div class="[ col-md-12 col-xl-4 ] [ flow-vertical--3 ]">

	         {{-- Button - Add 1:1 Activity & Add Group Activity --}}
	        <div class="flow-vertical--2">
	            <button class="button button--primary button--block" type="button">Add a Coach</button>
	            <a class="button button--primary button--block" href="{{ route('message.create') }}">Send Messages</a>
	            <button class="button button--primary button--block" type="button">Process Invoices</button>
	        </div>

	         {{-- Panel - My Coaches --}}
	        <div class="panel">
	            <div class="panel__inner">
	                <div class="panel__header">
	                    <h2 class="panel__heading">
	                        Most Active Coaches
	                    </h2>
	                </div>

	                <div class="panel__main">
	                    <div class="users-list">

	                        @foreach($mostActiveCoaches as $coach)
	                        <div class="users-list__item">
	                            <div class="users-list__main">
	                                <div class="[ users-list__avatar ] [ avatar ]">
	                                    <div class="avatar__image" style="background-image:url('{{ $coach->getAvatar() }}');"></div>
	                                </div>
	                                <div class="users-list__details">
	                                    <div class="users-list__name">
	                                        {{ $coach->full_name }}
	                                        <a class="users-list__edit-user-button" href="">
	                                            @svg('edit', 'icon')
	                                        </a>
	                                    </div>
	                                    <div class="users-list__info">{{ $coach->overallTrainingTimeThisMonth() }} hours coaching this month</div>
	                                </div>
	                                <a class="button button--primary" href="{{ route('message.create', ['id' => $coach->id]) }}">Message</a>
	                            </div>
	                            <div class="users-list__stats">
	                                <div class="users-list__stats-item">
	                                    <div class="users-list__stats-icon">
	                                         @svg('nav-my-coaches', 'icon')
	                                    </div>
	                                    <div class="users-list__stats-label">
	                                        {{ $coach->jockeys->count() }} jockeys assigned
	                                    </div>
	                                </div>
	                                 @if(optional($coach->pendingReviewInvoice)->count())
		                                <div class="users-list__stats-item">
		                                    <div class="users-list__stats-icon">
		                                        @svg('nav-invoice', 'icon')
		                                    </div>
		                                    <div class="users-list__stats-label">
		                                        1 pending invoice
		                                    </div>           
		                                </div>
		                            @endif
	                            </div>
	                        </div>
						@endforeach
	                    </div>
	                </div>

	                <a class="panel__call-to-action" href="">View All Coaches</a>
	            </div>
	        </div>
	    </div>
	</div>
</div>

@endsection

{{-- <div class="pt-1 pb-3 flow-vertical--1">
	@include('layouts.partials._user-bar')
	
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">

					<img src="{{ $admin->getAvatar() }}" alt="{{ $admin->full_name }}">
					Hi {{ $admin->full_name }}, Welcome back to your admin dashboard

					<br><br>
					<div>
						<h3>Jockeys Awaiting Approval</h3>

						@foreach($jockeysAwaitingApproval as $jockey)
							<div>
								<img src="{{ $jockey->getAvatar() }}" alt="{{ $jockey->full_name }}">
								{{ $jockey->full_name }} <br>
								Awaiting approval <br>
								{{ $jockey->formattedJoined }} 
					
							</div><br><br>
						@endforeach
					</div>
					<br><br>
					<div>
						<h3>Most Active Coaches</h3>
						@foreach($mostActiveCoaches as $coach)
							<div>
								<img src="{{ $coach->getAvatar() }}" alt="{{ $coach->full_name }}">
								{{ $coach->overallTrainingTimeThisMonth() }} Hours coaching this month
								<br>
								{{ $coach->jockeys->count() }} Jockeys Assigned
								<br>
								@if(optional($coach->pendingReviewInvoice)->count())
									1 pending invoice
								@endif
								
							</div>
							<br>
						@endforeach
					</div>


					<br><br><br>
					<h3>Invoices to Process</h3>
					@foreach($invoicesAwaitingReview->take(4) as $invoice)
						<p>{{ $invoice->coach->full_name }}</p>
						<p>{{ $invoice->submittedDate }}</p>
						<p>£{{ $invoice->overallValue }}</p>
					@endforeach
					<p>You have {{ $invoicesAwaitingReview->count() }} invoices ready for review</p>
				</div>			
            </div>
        </div>
    </div>
</div> --}}