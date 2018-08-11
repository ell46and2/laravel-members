@extends('layouts.base')

@section('content')

<div class="panel">
    <div class="panel__inner">
        <div class="panel__main">
            <h1 class="[ heading--1 ] [ mb-1 ]">Complete Racing Excellence</h1>
            Copy Needed
        </div>
    </div>
</div>

@if(!$racingExcellence->hasCoach() && $currentRole === 'admin')
	<assign-coach-to-race
		resource="{{ json_encode($coachesResource) }}"
		:racing-excellence-id="{{ $racingExcellence->id }}"
	></assign-coach-to-race>
	<br><br><br>

@else

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
	            <racing-excellence-results
					resource="{{ json_encode($raceResource) }}"
					coach-id="{{ $racingExcellence->coach_id }}"
				></racing-excellence-results>
	        </div>
	    </div>
	</div>
@endforeach

	

@endif
@endsection
