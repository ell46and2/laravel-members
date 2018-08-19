@extends('layouts.base')

@section('content')

	<div class="flow-vertical--2">
		<div class="panel">
	        <div class="panel__inner">
	            <div class="panel__main">
	                <div class="profile-summary">
	                    <div class="[ profile-summary__avatar ] [ avatar avatar--red ]">
	                        <div class="avatar__image" style="background-image:url({{ auth()->user()->getAvatar() }});"></div>
	                    </div>
	                    <div class="profile-summary__main">
	                        <h2 class="profile-summary__greeting-primary">Hi {{ $jet->full_name }},</h2>
	                        <div class="profile-summary__greeting-secondary">Welcome back to your JETS dashboard</div>
	                        <div>
	                            <a class="button button--primary" href="{{ route('jet.show', $jet) }}">View profile</a>
	                        </div>
	                    </div>
	                </div>
	            </div>   
	        </div>
	    </div>

	    @include('layouts.partials._messages-alert')

	    <div class="row">
		    <div class="[ col-12 mb-2 mb-xl-0 ] [ flow-vertical--2 ]">
				<div class="panel panel--is-stack">
				    <div class="panel__inner">
				        <div class="panel__header">
				            <h2 class="panel__heading">
				                PDP's Awaiting Review
				            </h2>
				        </div>

				        <div class="panel__main">
				            <table class="table table--stacked-md">
				                <thead>
				                    <tr>
										<th>Jockey</th>
										<th>PDP Name</th>
										<th>Status</th>
										<th>Submitted</th>
										<th></th>
				                    </tr>
				                </thead>
				                <tbody>
				                	@if($pdpAwaitingReview->count())
				                		@foreach($pdpAwaitingReview as $pdp)	                            
					                        <tr>
					                            <td aria-label="Jockey">{{ $pdp->jockey->full_name }}</td>
					                            <td aria-label="Name">{{ $pdp->name }}</td>
					                            <td aria-label="Status">{{ $pdp->status }}</td>
					                            <td aria-label="Submitted">{{ $pdp->formattedSubmitted }}</td>
					                            <td class="text-right">
					                                <a class="button button--primary" href="{{ route('pdp.personal-details', $pdp) }}">View</a>
					                            </td>
					                        </tr>
					                    @endforeach
					                @else
					                	<tr>
					                        <td class="text-center" colspan="5">
					                            No items
					                        </td>
					                    </tr>
				                	@endif
				                    
				                </tbody>
				            </table>
				        </div>

				        <a class="panel__call-to-action" href="{{ route('pdp.list') }}">View all PDP's</a>
				    </div>
				</div>

				<div class="panel panel--is-stack">
				    <div class="panel__inner">
				        <div class="panel__header">
				            <h2 class="panel__heading">
				                Recent CRM
				            </h2>
				        </div>

				        <div class="panel__main">
				            <table class="table table--stacked-md">
				                <thead>
				                    <tr>
				                        <th>Jockey</th>
				                        <th>Location</th>
				                        <th>Date</th>
				                        <th>Follow up</th>
				                        <th></th>
				                    </tr>
				                </thead>
				                <tbody>
				                	@if($recentCrmRecords->count())
				                		@foreach($recentCrmRecords as $record)	                            
					                        <tr>
					                            <td aria-label="Jockey">{{ $record->managable->full_name }}</td>
					                            <td aria-label="Location">{{ $record->location }}</td>
					                            <td aria-label="Date">{{ $record->formattedCreatedAt }}</td>
					                            <td aria-label="Follow Up">{{ $record->formattedReviewDate }}</td>
					                            <td class="text-right">
					                                <a class="button button--primary" href="{{ route('jets.crm.show', $record) }}">View</a>
					                            </td>
					                        </tr>
					                    @endforeach
					                @else
					                	<tr>
					                        <td class="text-center" colspan="5">
					                            No items
					                        </td>
					                    </tr>
				                	@endif
				                    
				                </tbody>
				            </table>
				        </div>

				        <a class="panel__call-to-action" href="{{ route('jets.crm.index') }}">View all CRM</a>
				    </div>
				</div>
		    </div>
		</div>
	</div>

@endsection