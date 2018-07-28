@extends('layouts.app')

@section('content')

@if(!$racingExcellence->hasCoach() && auth()->user()->isAdmin())
	<assign-coach-to-race
		resource="{{ json_encode($coachesResource) }}"
		:racing-excellence-id="{{ $racingExcellence->id }}"
	></assign-coach-to-race>
	<br><br><br>
@endif

	<racing-excellence-results
		resource="{{ json_encode($raceResource) }}"
		coach-id="{{ $racingExcellence->coach_id }}"
	></racing-excellence-results>

@endsection
