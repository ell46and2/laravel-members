@extends('layouts.base')

@section('content')

<div class="panel">
    <div class="panel__inner">
        <div class="panel__main">
            <div class="row">
                <div class="col-6">
                    <h1 class="[ heading--1 heading--has-button ] [ mb-1 ]">{{ $activity->isGroup() ? 'Group' : '1:1' }} Simulator Session                      
                        @if($isAdmin || $isAssignedCoach)
                        	<a class="[ button button--primary ] [ heading__button ]" href="">Edit</a>
							<form 
								method="POST" 
								action="{{ route('activity.delete', $activity) }}"
								class="[ js-confirmation ]"
							    data-confirm="Are you sure you want to delete this activity?"
							>
								{{ csrf_field() }}
					    		<input type="hidden" name="_method" value="delete" />
					    		<button 
					    			style="display: inherit;" 
					    			class="[ button button--primary ] [ heading__button ]" 
					    			type="submit"
					    		>Delete</button>
							</form>
						@endif
                    </h1>
                    Your Activity Log shows your recent and upcoming activities
                </div>
                <div class="col-6">
                    <div class="text-right text--color-blue-dark">
                        I.D {{ $activity->id }}
                    </div>
                    <div class="text-right text--color-blue-lighter text--size-sm">
                        Last Updated on {{ $activity->formattedUpdatedOn }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel__inner">
        <div class="panel__pre-header">
            <div class="panel__pre-header-primary">
                <ul class="panel__pre-header-definition-list">
                    <li>
                        <dl>
                            <dt>Date</dt>
                            <dd>{{ $activity->formattedStart }}</dd>
                        </dl>
                    </li>
                    <li>
                        <dl>
                            <dt>Start time</dt>
                            <dd>{{ $activity->formattedStartTimeAmPm }}</dd>
                        </dl>
                    </li>
                    <li>
                        <dl>
                            <dt>Duration</dt>
                            <dd>{{ $activity->formattedDuration }}</dd>
                        </dl>
                    </li>
                    <li>
                        <dl>
                            <dt>Location</dt>
                            <dd>{{ $activity->formattedLocation }}</dd>
                        </dl>
                    </li>
                </ul>
            </div>
        </div>

        <div class="panel__header">
            <h2 class="panel__heading">
                {{ str_plural('Jockey', $activity->jockeys->count()) }}
            </h2>
        </div>

        <div class="panel__main">
            <div class="row row--grid">
                @foreach($activity->jockeys as $jockey)
                    <div class="col-md-3">
                        <div class="user-card">
                            <div class="[ user-card__avatar ] [ avatar ]">
                                <div class="avatar__image" style="background-image:url('{{ $jockey->getAvatar() }}');"></div>
                            </div>
                            <div class="user-card__main">
                                <div class="user-card__name">{{ $jockey->full_name }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel__inner">
        <div class="panel__header">
            <h2 class="panel__heading">
                {{ $activity->isGroup() ? 'Group ' : '' }}Activity Information
            </h2>
        </div>

        <div class="panel__main">
            {!! $activity->information ?? '<i>no information</i>' !!}
        </div>

    </div>
</div>

<attachment-upload
    model-type="activity"
    model-id="{{ $activity->id }}"
    resource="{{ json_encode($attachmentsResource) }}"
    can-edit="{{ $isAdmin || $isAssignedCoach }}"
></attachment-upload>





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
