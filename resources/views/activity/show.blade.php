@extends('layouts.app')

@section('content')
@php
   
@endphp

	<h1>{{ $activity->formattedType }}</h1>
		@if($isAdmin || $isAssignedCoach)
			<a href="{{ $activity->editRoute }}" class="btn btn-primary">Edit</a>
		@endif
		
	<p>I.D {{ $activity->id }}</p>
	<p>Last updated on {{ $activity->formattedUpdatedOn }}</p>

	<br><br>
	<p>Date {{ $activity->formattedStart }}</p>
	<p>Start Time {{ $activity->formattedStartTimeAmPm }}</p>
	<p>Duration</p>
	<p>Location {{ $activity->formattedLocation }}</p>

	@if($isAdmin || $isAssignedCoach)
		<form 
			method="POST" 
			action="{{ route('activity.delete', $activity) }}"
			class="[ js-confirmation ]"
		    data-confirm="Are you sure you want to delete this activity?"
		>
			{{ csrf_field() }}
    		<input type="hidden" name="_method" value="delete" />
    		<button class="btn btn-danger" type="submit">Delete</button>
		</form>
	@endif

	<div>
		<h2>{{ str_plural('Jockey', $activity->jockeys->count()) }}</h2>
		@foreach($activity->jockeys as $jockey)
			<div class="media mt-4 mb-4">
				<div class="media-body">
					<img class="mr-3" src="{{ $jockey->getAvatar() }}" alt="{{ $jockey->full_name }}">
					<p>{{ $jockey->full_name }}</p>
				</div>
			</div>
		@endforeach
	</div>

	<br><br>
	<div>
		<h2>Activity Information</h2>
		<p>{{ $activity->information }}</p>
	</div>
	
<br><br><br>
    <attachment-upload
        model-type="activity"
        model-id="{{ $activity->id }}"
        resource="{{ json_encode($attachmentsResource) }}"
        can-edit="{{ $isAdmin || $isAssignedCoach }}"
    ></attachment-upload>

	<br><br><br><br>


	<attachment-modal></attachment-modal>

	<br><br>

	{{-- if more than one jockey use tabs --}}
	@if($isAdmin || $isAssignedCoach)
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
				is-current-user-jockey="{{ $currentUser->isJockey() }}"
				can-user-add-comments="{{ $activity->isAssignedToUser($currentUser) }}"
			></comments>
		@endforeach
	@elseif($isCoach)
		@foreach($activity->jockeys as $jockey)
			@if($jockey->isAssignedToCoach($currentUser->id))
				<add-feedback 
					activity-id="{{ $activity->id }}" 
					jockey-id="{{ $jockey->id }}"
					current-feedback="{{ $jockey->pivot->feedback }}"
				></add-feedback>

				<comments
					endpoint="{{ route('activity.comment.index', $activity) }}"
					recipient-id="{{ $jockey->id }}"
					jockey-id="{{ $jockey->id }}"
					is-current-user-jockey="{{ $currentUser->isJockey() }}"
					can-user-add-comments="{{ $activity->isAssignedToUser($currentUser) }}"
				></comments>
			@endif			
		@endforeach
	@endif
         
@endsection
