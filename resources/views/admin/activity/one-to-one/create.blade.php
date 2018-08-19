@extends('layouts.base')

@section('content')

<div class="panel">
    <div class="panel__inner">
        <div class="panel__main">
            <h1 class="[ heading--1 ] [ mb-1 ]">Add 1:1 Activity</h1>
            Create an activity with a single Jockey
        </div>
    </div>
</div>

<form method="POST" action="{{ route('admin.activity.store') }}">
{{ csrf_field() }}

	<div class="panel">
	    <div class="panel__inner">
	        <div class="panel__header">
	            <h2 class="panel__heading">
	                Choose a coach
	                <span class="[ text--size-sm text--color-base ] [ font-weight-normal ] [ ml-1 ]">Select a Coach to run the activity</span>
	            </h2>
	        </div>
			<coaches-selection
				class="form-control{{ $errors->has('coach_id') ? ' is-invalid' : '' }}"
				resource="{{ json_encode($coachesResource) }}"
				:group="false"
				:old="{{ json_encode(old('coach_id')) }}"
			></coaches-selection>
			@if ($errors->has('coach_id'))
		        <span class="invalid-feedback">
		            <strong>{{ $errors->first('coach_id') }}</strong>
		        </span>
		    @endif
	    </div>
	</div>

	<div class="panel">
	    <div class="panel__inner">
	        <div class="panel__header">
	            <h2 class="panel__heading">
	                Choose Activity Type
	                <span class="[ text--size-sm text--color-base ] [ font-weight-normal ] [ ml-1 ]">Select which group activity you would like to create</span>
	            </h2>
	        </div>

	        <div class="panel__main">
	            <div class="row row--grid">
	                @foreach($activityTypes as $activityType)
	                    <div class="col-md-4">
	                        <div class="[ icon-checkbox ]">
	                            <input 
	                            	class="[ icon-checkbox__input ] [ sr-only ]" 
	                            	type="radio" 
	                            	name="activity_type_id" 
	                            	id="activity_type-{{ $activityType->id }}" 
									value="{{ $activityType->id }}"
	                            >
	                            <label class="icon-checkbox__label" for="activity_type-{{ $activityType->id }}">
	                                <div class="icon-checkbox__icon">
	                                    @svg( $activityType->icon, 'icon')
	                                </div>
	                                <span class="icon-checkbox__name">{{ $activityType->name }}</span>
	                            </label>
	                        </div>
	                    </div>
	                @endforeach

            	    @if ($errors->has('activity_type_id'))
                        <span class="invalid-feedback">
                        	<strong>{{ $errors->first('activity_type_id') }}</strong>
                        </span>
                    @endif
	            </div>
	        </div>

	    </div>
	</div>

	<div class="panel pt-3" style="z-index: 3;">
        <div class="panel__inner">
            <div class="panel__header">
                <h2 class="panel__heading">
                    Choose Date, Time & Duration
                    <span class="[ text--size-sm text--color-base ] [ font-weight-normal ] [ ml-1 ]">Description</span>
                </h2>
            </div>

            <div class="panel__main">
                <div class="row row--grid">
                    <div class="col">
                        <span class="text--color-blue">Select Date</span>
                    	<datepicker-component name="start_date" placeholder="Select Date" old="{{ old('start_date') }}"></datepicker-component>

                    	@if ($errors->has('start_date'))
				            <span class="invalid-feedback">
				                <strong>{{ $errors->first('start_date') }}</strong>
				            </span>
				        @endif
                    </div>
                    <div class="col">
                        <span class="text--color-blue">Select Start Time</span>
            			<timepicker-component old="{{ old('start_time') }}"></timepicker-component>		
            			
            			@if ($errors->has('start_time'))
            	            <span class="invalid-feedback">
            	                <strong>{{ $errors->first('start_time') }}</strong>
            	            </span>
            	        @endif
                    </div>
                    <div class="col">
                        <span class="text--color-blue">Select Duration</span>
                        <div class="form-group form-group--has-icon mt-1">
                            <input class="form-control form-control--has-icon" type="text" name="duration" value="{{ old('duration') }}" placeholder="Select Duration">
                            <span class="form-group__input-icon" aria-hidden="true" role="presentation">
                                @svg( 'duration', 'icon')
                            </span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="panel pt-3">
        <div class="panel__inner">
            <div class="panel__header">
                <h2 class="panel__heading">
                    Select a Jockey
                    <span class="[ text--size-sm text--color-base ] [ font-weight-normal ] [ ml-1 ]">Select the jockey you want to create an activity for</span>
                </h2>
            </div>

            <users-selection 
				resource=""
				:group="false"
				old="{{ json_encode(old('jockeys')) }}"
				:coach-id="{{ json_encode(old('coach_id')) }}"
			></users-selection>
			@if ($errors->has('jockeys'))
	            <span class="invalid-feedback">
	                <strong>{{ $errors->first('jockeys') }}</strong>
	            </span>
	        @endif

        </div>
    </div>

    <div class="panel pt-3">
        <div class="panel__inner">
            <div class="panel__header">
                <h2 class="panel__heading">
                    Choose Location
                    <span class="[ text--size-sm text--color-base ] [ font-weight-normal ] [ ml-1 ]">Description</span>
                </h2>
            </div>

            <div class="panel__main">
                <div class="row">
                    <div class="col-3">
                        <location-selection 
		               		class="form-control{{ $errors->has('location_id') ? ' is-invalid' : '' }}" locations-data="{{ json_encode($locations) }}"
		               		old-location-id="{{ old('location_id') }}"
		               		old-location-name="{{ old('location_name') }}"
		               	></location-selection>
						
						@if ($errors->has('location_id'))
						    <span class="invalid-feedback">
						        <strong>{{ $errors->first('location_id') }}</strong>
						    </span>
						@endif
		            </div>
					
					<div class="col-3">
		               	<location-name-input 
							class="form-control{{ $errors->has('location_name') ? ' is-invalid' : '' }}"
							old-location-name="{{ old('location_name') }}"
		               	></location-name-input>   
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="panel pt-3">
        <div class="panel__inner">
            <div class="panel__header">
                <h2 class="panel__heading">
                    Activity Information
                    <span class="[ text--size-sm text--color-base ] [ font-weight-normal ] [ ml-1 ]">Description</span>
                </h2>
            </div>

            <div class="panel__main">
                <textarea class="form-control" name="information" rows="8" cols="80"  placeholder="Enter Information...">{{ old('information') }}</textarea>

                @if ($errors->has('information'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('information') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-4 mt-3">
                <button class="button button--primary button--success button--squared button--block" type="submit">Create Activity</button>
            </div>
        </div>

    </div>

</form>
@endsection





{{-- 
<div class="container">

	<h1>Add 1:1 Activity</h1>
	<form method="POST" action="{{ route('admin.activity.store') }}">

		{{ csrf_field() }}
	
		<div class="form-group">
			<coaches-selection
				class="form-control{{ $errors->has('coach_id') ? ' is-invalid' : '' }}"
				resource="{{ json_encode($coachesResource) }}"
				:group="false"
				:old="{{ json_encode(old('coach_id')) }}"
			></coaches-selection>
			@if ($errors->has('coach_id'))
		        <span class="invalid-feedback">
		            <strong>{{ $errors->first('coach_id') }}</strong>
		        </span>
		    @endif
		</div>

		<br><br>
		
		<div class="form-group">
			<div class="form-control{{ $errors->has('activity_type_id') ? ' is-invalid' : '' }}">
				@foreach($activityTypes as $activityType)
					<input 
						type="radio" 
						name="activity_type_id" 
						id="activity_type-{{ $activityType->id }}" 
						value="{{ $activityType->id }}"
						{{ old('activity_type_id') == $activityType->id ? 'checked' : '' }}
					>
					<label for="activity_type-{{ $activityType->id }}">
						{{ $activityType->name }}
					</label>
				@endforeach				
		    </div>
		    @if ($errors->has('activity_type_id'))
	            <span class="invalid-feedback">
	                <strong>{{ $errors->first('activity_type_id') }}</strong>
	            </span>
	        @endif
	    </div>
		<br><br>
		
		<div class="form-group row">
			<div class="form-control{{ $errors->has('start_date') ? ' is-invalid' : '' }}">
				<datepicker-component name="start_date" placeholder="Select Date" old="{{ old('start_date') }}"></datepicker-component>
				<p>old is {{ old('start_date') }}</p>
			</div>
			@if ($errors->has('start_date'))
	            <span class="invalid-feedback">
	                <strong>{{ $errors->first('start_date') }}</strong>
	            </span>
	        @endif
		</div>

		<br><br>

		<div class="form-group">
			<div class="form-control{{ $errors->has('start_time') ? ' is-invalid' : '' }}">
				<timepicker-component old="{{ old('start_time') }}"></timepicker-component>		
			</div>
			@if ($errors->has('start_time'))
	            <span class="invalid-feedback">
	                <strong>{{ $errors->first('start_time') }}</strong>
	            </span>
	        @endif
		</div>

		<br><br>
		
		<div class="form-group{{ $errors->has('jockeys') ? ' is-invalid' : '' }}">
			<users-selection 
				class="form-control"
				resource=""
				:group="false"
				old="{{ json_encode(old('jockeys')) }}"
				:coach-id="{{ json_encode(old('coach_id')) }}"
			></users-selection>
			@if ($errors->has('jockeys'))
	            <span class="invalid-feedback">
	                <strong>{{ $errors->first('jockeys') }}</strong>
	            </span>
	        @endif
		</div>

		<br><br>

		<div class="form-group">
            <label class="col-form-label text-md-right" for="location_id">Choose a Location</label>
            
            <div class="">
                <select class="form-control" name="location_id" id="location_id" required>
                   @foreach($locations as $location)
                        <option 
                        	value="{{ $location->id }}"
                        	{{ old('location_id') == $location->id ? 'selected' : '' }}
                        >
                            {{ ucfirst($location->name) }}
                        </option>
                   @endforeach
                </select>

                @if ($errors->has('location_id'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('location_id') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <br><br>

        <div class="form-group">
            <label class="col-form-label text-md-right" for="location_id">Activity Information</label>

            <div class="form-control">
            	<textarea name="information" cols="30" rows="10">
            		{{ old('information') }}
            	</textarea>
            </div>

            @if ($errors->has('information'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('information') }}</strong>
                </span>
            @endif
        </div>

        <br><br>

		<div class="form-group row mb-0">
            <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-primary">
                	Create Activity
                </button>
            </div>
        </div>
	</form>

	
</div>
@endsection --}}

{{-- <script>
	window.oldFormValues = {
		start_date: {!! json_encode(old('start_date', null)) !!}
	};
</script>  --}}