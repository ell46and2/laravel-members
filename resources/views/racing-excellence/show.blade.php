@extends('layouts.base')

@section('content')

<div class="panel">
    <div class="panel__inner">
        <div class="panel__main">
            <h1 class="[ heading--1 ] [ mb-1 ]">Racing Excellence</h1>
            Copy Needed
        </div>
    </div>
</div>


{{-- Need add tabs if more than one division --}}
@foreach($racingExcellence->divisionResults() as $division)
	<div class="panel panel--is-stack">
	    <div class="panel__inner">
	        <div class="panel__pre-header">
	            <div class="panel__pre-header-primary">
	                <ul class="panel__pre-header-definition-list">
	                    <li>
	                        <dl>
	                            <dt>Coach</dt>
	                            <dd>{{ $racingExcellence->coach ? $racingExcellence->coach->full_name : '' }}</dd>
	                        </dl>
	                    </li>
	                    <li>
	                        <dl>
	                            <dt>Date</dt>
	                            <dd>{{ $racingExcellence->formattedStart }}</dd>
	                        </dl>
	                    </li>
	                    <li>
	                        <dl>
	                            <dt>Start time</dt>
	                            <dd>{{ $racingExcellence->formattedStartTimeFull }}</dd>
	                        </dl>
	                    </li>
	                    <li>
	                        <dl>
	                            <dt>Series</dt>
	                            <dd>{{ $racingExcellence->formattedSeriesName }}</dd>
	                        </dl>
	                    </li>
	                    <li>
	                        <dl>
	                            <dt>Location</dt>
	                            <dd>{{ $racingExcellence->formattedLocation }}</dd>
	                        </dl>
	                    </li>
	                </ul>
	            </div>
	            <div class="panel__pre-header-secondary">
	                <span class="text-nowrap">I.D {{ $racingExcellence->raceId }}</span>
	            </div>
	        </div>
	        <div class="panel__header">
	            <h2 class="panel__heading">
	                Racing Results
	            </h2>
	        </div>

	        <div class="panel__main">
	            <table class="table table-hover">
	                <thead>
	                    <tr>
	                        <th></th>
	                        <th>Jockey</th>
	                    </tr>
	                </thead>
	                <tbody>
	                    @foreach($division->participants as $participant)
	                        <tr>
	                            <td class="table__result-column">
	                                <div class="table__result-column-inner">
	                                    <span class="table__result-position">
	                                        {{ $participant->formattedPlace }}
	                                        @if($participant->formattedPlace !== 'dnf')
	                                        	<span class="table__result-position-suffix" style="margin-left: -4px;">
		                                            {{ numberOrdinalSuffix($participant->place) }}
		                                        </span>
		                                    @else
		                                    	dnf
	                                        @endif
	                                        
	                                    </span>
	                                    @if(in_array($participant->place, [1,2,3,4]))
	                                    	<span class="table__icon
	                                    		@if($participant->place == 1)
	                                    			table__icon--gold
	                                    		@elseif($participant->place == 2)
													table__icon--silver
	                                    		@elseif($participant->place == 3)
													table__icon--bronze
	                                    		@endif
	                                        ">
	                                        @svg('rosette', 'icon')
	                                        </span>
	                                    @endif
	                                </div>
	                            </td>
	                            <td>{{ $participant->formattedName }}</td>
	                        </tr>
	                    @endforeach
	                </tbody>
	            </table>
	        </div>
	    </div>
	</div>
@endforeach

{{-- show feedback and points if currentuser is jockey OR coach is assigned to the jockey --}}


	@foreach($racingExcellence->divisionResults() as $division)
		@if($usersParticpantIds && $division->getDivisionParticipantsByJockeyIds($usersParticpantIds))
			@foreach($division->getDivisionParticipantsByJockeyIds($usersParticpantIds) as $participant)
				<br>
				<p>{{ $participant->formattedName }}</p>


				<table class="table">
				  	<thead>
				    	<tr>
				    		<th scope="col">Name</th>
				      		<th scope="col">Place</th>
				      		<th scope="col">Place Points</th>
				      		<th scope="col">Presentation</th>
				      		<th scope="col">Professionalism</th>
				      		<th scope="col">Course Walk</th>
				      		<th scope="col">Riding</th>
				      		<th scope="col">Total</th>
				    	</tr>
				  	</thead>
				  	<tbody>
				  		
					    	<tr>
					      		<td>{{ $participant->formattedName }}</td>
					      		<td>{{ $participant->formattedPlace }}</td>
					      		<td>{{ $participant->place_points }}</td>
					      		<td>{{ $participant->presentation_points }}</td>
					      		<td>{{ $participant->professionalism_points }}</td>
					      		<td>{{ $participant->coursewalk_points }}</td>
					      		<td>{{ $participant->riding_points }}</td>
					      		<td>{{ $participant->total_points }}</td>
					    	</tr>
				    	
				  	</tbody>
				</table>

				<p>Feedback</p>
				<p>{{ $participant->feedback }}</p>
			@endforeach
		@endif

		<br><br><br>
	@endforeach	

@endsection
