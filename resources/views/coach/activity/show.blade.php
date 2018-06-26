@extends('layouts.app')

@section('content')

	<h1>{{ $activity->formattedType }}</h1>
	<p>I.D {{ $activity->id }}</p>
	<p>Last updated on {{ $activity->formattedUpdatedOn }}</p>

	<br><br>
	<p>Date {{ $activity->formattedStart }}</p>
	<p>Start Time {{ $activity->formattedStartTimeAmPm }}</p>
	<p>Duration</p>
	<p>Location {{ $activity->formattedLocation }}</p>
	
    <attachment-upload
        model-type="activity"
        model-id="{{ $activity->id }}"
        resource="{{ json_encode($attachmentsResource) }}"
        can-edit="{{ auth()->user()->isCoachOrAdmin() }}"
    ></attachment-upload>

	<br><br><br><br>


	<attachment-modal></attachment-modal>



	{{-- Loop through each jockey and create tab with comments in  --}}
	<comments
		endpoint="{{ route('activity.comment.index', $activity) }}"
		recipient-id="2"
		jockey-id="2"
		is-current-user-jockey="{{ auth()->user()->isJockey() }}"
		can-user-add-comments="{{ $activity->isAssignedToUser(auth()->user()) }}"
	></comments> 

         
@endsection
