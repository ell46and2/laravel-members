@extends('layouts.app')

@section('content')

<div>
	<p>Coach: {{ $racingExcellence->formattedCoach }}</p>
	<p>Date: {{ $racingExcellence->formattedStart }}</p>
	<p>Start Time: {{ $racingExcellence->formattedStartTimeFull }}</p>
	<p>Series: {{ $racingExcellence->series_name }}</p>
	<p>Location: {{ $racingExcellence->location }}</p>
	<p>ID {{ $racingExcellence->raceId }}</p>
</div>

	@foreach($racingExcellence->divisionResults() as $division)
		<div>
			<table class="table">
			  	<thead>
			    	<tr>
			    		<th scope="col">Name</th>
			      		<th scope="col">Place</th>
	
			    	</tr>
			  	</thead>
			  	<tbody>
			  		@foreach($division->participants as $participant)
				    	<tr class="{{ $participant->jockey_id === auth()->user()->id ? 'table-success' : '' }}">
				      		<td>{{ $participant->formattedName }}</td>
				      		<td>{{ $participant->formattedPlace }}</td>
				    	</tr>
			    	@endforeach
			  	</tbody>
			</table>
		</div>

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
