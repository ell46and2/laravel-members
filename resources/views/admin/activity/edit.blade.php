@extends('layouts.app')

@section('content')


<div class="container">

	<h1>Edit Activity</h1>
	<form method="POST" action="{{ $activity->editRoute }}">

		@csrf
		@method('put')

		<div class="form-group">
			<coaches-selection
				class="form-control{{ $errors->has('coach_id') ? ' is-invalid' : '' }}"
				resource="{{ json_encode($coachesResource) }}"
				:group="false"
				:old="{{ json_encode(old('coach_id', $activity->coach_id)) }}"
			></coaches-selection>
			@if ($errors->has('coach_id'))
		        <span class="invalid-feedback">
		            <strong>{{ $errors->first('coach_id') }}</strong>
		        </span>
		    @endif
		</div>
		
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
	
		<div class="form-group{{ $errors->has('jockeys') ? ' is-invalid' : '' }}">
			<users-selection
				class="form-control"
				resource=""
				group="{{ $activity->group }}"
				old="{{ json_encode(old('jockeys', $activity->jockeysOldDataFormat())) }}"
				:coach-id="{{ json_encode(old('coach_id', $activity->coach_id)) }}"
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
@endsection