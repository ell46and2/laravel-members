@extends('layouts.base')

@section('content')

<div class="panel">
    <div class="panel__inner">
        <div class="panel__main">
            <h1 class="[ heading--1 ] [ mb-1 ]">Edit {{ $activity->group ? 'Group' : '1:1' }} Activity</h1>
            Create an activity with a group of Jockeys
        </div>
    </div>
</div>

<form method="POST" action="{{ $activity->editRoute }}">
	@csrf
	@method('put')

	<div class="panel">
	    <div class="panel__inner">
	        <div class="panel__header">
	            <h2 class="panel__heading">
	                Choose Activity Type
	                <span class="[ text--size-sm text--color-base ] [ font-weight-normal ] [ ml-1 ]">Select which type of activity you would like to create</span>
	            </h2>
	        </div>

	        <div class="panel__main">
	            <div class="row row--grid">
	                @foreach($activityTypes as $activityType)
	                    <div class="col-md-4">
	                        <div class="[ icon-checkbox ]">
	                            <input 
	                            	class="[ icon-checkbox__input ] [ sr-only ]" 
	                            	type="checkbox" 
	                            	name="activity_type_id" 
	                            	id="activity_type-{{ $activityType->id }}" 
									value="{{ $activityType->id }}"
									@if($activity->activity_type_id === $activityType->id)
										checked
									@endif
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

	<div class="panel pt-3">
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
                    	<datepicker-component 
                    		name="start_date" 
                    		placeholder="Select Date" 
                    		old="{{ old('start_date', $activity->startDate ) }}"
                    	></datepicker-component>

                    	@if ($errors->has('start_date'))
				            <span class="invalid-feedback">
				                <strong>{{ $errors->first('start_date') }}</strong>
				            </span>
				        @endif
                    </div>
                    <div class="col">
            			<timepicker-component old="{{ old('start_time', $activity->formattedStartTime) }}">	
            			</timepicker-component>		
            			
            			@if ($errors->has('start_time'))
            	            <span class="invalid-feedback">
            	                <strong>{{ $errors->first('start_time') }}</strong>
            	            </span>
            	        @endif
                    </div>
                    <div class="col">Select Duration - Placeholder</div>
                </div>
            </div>

        </div>
    </div>

    <div class="panel pt-3">
        <div class="panel__inner">
            <div class="panel__header">
                <h2 class="panel__heading">
                	@if($activity->isGroup())
						Select the Jockeys
                    	<span class="[ text--size-sm text--color-base ] [ font-weight-normal ] [ ml-1 ]">Select which jockeys you want to create group activity for</span>
                	@else
                		Select a Jockey
                		<span class="[ text--size-sm text--color-base ] [ font-weight-normal ] [ ml-1 ]">Select the jockey you want to create an activity for</span>
                	@endif                  
                </h2>
            </div>

            @if($activity->isGroup())
				<users-selection 
					resource="{{ json_encode($jockeysResource) }}"
					:group="true"
					old="{{ json_encode(old('jockeys')) }}"
				></users-selection>
			@else
				<users-selection 
					resource="{{ json_encode($jockeysResource) }}"
					:group="false"
					old="{{ json_encode(old('jockeys')) }}"
				></users-selection>
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
               				old-location-id="{{ old('location_id', $activity->location_id) }}"
               				old-location-name="{{ old('location_name', $activity->location_name) }}"
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
							old-location-name="{{ old('location_name', $activity->location_name) }}"
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
                <textarea class="form-control" name="information" rows="8" cols="80"  placeholder="Enter Information...">{{ old('information', $activity->information) }}</textarea>

                @if ($errors->has('information'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('information') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-4 mt-3">
                <button class="button button--primary button--success button--squared button--block" type="submit">Update Activity</button>
            </div>
        </div>

    </div>

</form>
@endsection

{{-- 
<div class="container">

	<h1>Edit Activity</h1>
	<form method="POST" action="{{ $activity->editRoute }}">

		@csrf
		@method('put')
		
		<div class="form-group row">
			<div class="form-control{{ $errors->has('activity_type_id') ? ' is-invalid' : '' }}">
				@foreach($activityTypes as $activityType)
					<input 
						type="radio" 
						name="activity_type_id" 
						id="activity_type-{{ $activityType->id }}" 
						value="{{ $activityType->id }}"
						{{ old('activity_type_id', $activity->activity_type_id) == $activityType->id ? 'checked' : '' }}
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
				<datepicker-component name="start_date" placeholder="Select Date" old="{{ old('start_date', $activity->startDate ) }}"></datepicker-component>
				<p>old is {{ old('start_date') }}</p>
			</div>
			@if ($errors->has('start_date'))
	            <span class="invalid-feedback">
	                <strong>{{ $errors->first('start_date') }}</strong>
	            </span>
	        @endif
		</div>

		<br><br>

		<div class="form-group row">
			<div class="form-control{{ $errors->has('start_time') ? ' is-invalid' : '' }}">
				<timepicker-component old="{{ old('start_time', $activity->formattedStartTime) }}"></timepicker-component>		
			</div>
			@if ($errors->has('start_time'))
	            <span class="invalid-feedback">
	                <strong>{{ $errors->first('start_time') }}</strong>
	            </span>
	        @endif
		</div>

		<br><br>
	
	@if($activity->isGroup())
		<users-selection 
			resource="{{ json_encode($jockeysResource) }}"
			:group="true"
			old="{{ json_encode(old('jockeys')) }}"
		></users-selection>
	@else
		<users-selection 
			resource="{{ json_encode($jockeysResource) }}"
			:group="false"
			old="{{ json_encode(old('jockeys')) }}"
		></users-selection>
	@endif			

		<br><br>

		<div class="form-group">
            <label class="col-form-label text-md-right" for="location_id">Choose a Location</label>
            
            <div class="">
                <select class="form-control" name="location_id" id="location_id" required>
                   @foreach($locations as $location)
                        <option 
                        	value="{{ $location->id }}"
                        	{{ old('location_id', $activity->location_id) == $location->id ? 'selected' : '' }}
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

        <div class="form-group">
            <label class="col-form-label text-md-right" for="location_id">Choose a Location</label>
            
            <div class="">
               <location-selection 
               		class="form-control{{ $errors->has('location_id') ? ' is-invalid' : '' }}" locations-data="{{ json_encode($locations) }}"
               		old-location-id="{{ old('location_id', $activity->location_id) }}"
               		old-location-name="{{ old('location_name', $activity->location_name) }}"
               	></location-selection>

               <location-name-input 
					class="form-control{{ $errors->has('location_name') ? ' is-invalid' : '' }}"
					old-location-name="{{ old('location_name', $activity->location_name) }}"
               ></location-name-input>

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
            		{{ old('information', $activity->information) }}
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
                	Update
                </button>
            </div>
        </div>
	</form>

	
</div>
@endsection --}}