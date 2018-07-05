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

	<div>
		@foreach($activity->jockeys as $jockey)
			<div class="media mt-4 mb-4">
				<div class="media-body">
					<img class="mr-3" src="{{ $jockey->getAvatar() }}" alt="{{ $jockey->full_name }}">
					<p>{{ $jockey->full_name }}</p>
				</div>
			</div>
		@endforeach
	</div>
	

    <attachment-upload
        model-type="activity"
        model-id="{{ $activity->id }}"
        resource="{{ json_encode($attachmentsResource) }}"
        can-edit="{{ auth()->user()->isCoachOrAdmin() }}"
    ></attachment-upload>

	<br><br><br><br>


	<attachment-modal></attachment-modal>

	<br><br>

	{{-- if more than one jockey use tabs --}}
	@foreach($activity->jockeys as $jockey)
		<add-feedback 
			activity-id="{{ $activity->id }}" 
			jockey-id="{{ $jockey->id }}"
			current-feedback="{{ $jockey->pivot->feedback }}"
		></add-feedback>

		<comments
			endpoint="{{ route('activity.comment.index', $activity) }}"
			recipient-id="{{ $jockey->id }}"
			jockey-id="{{ $jockey->id }}"
			is-current-user-jockey="{{ auth()->user()->isJockey() }}"
			can-user-add-comments="{{ $activity->isAssignedToUser(auth()->user()) }}"
		></comments>
	@endforeach

         
@endsection
