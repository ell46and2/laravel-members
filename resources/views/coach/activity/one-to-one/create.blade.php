@extends('layouts.app')

@section('content')


<div class="container">

	<h1>Add 1:1 Activity</h1>
	<form method="POST" action="{{ route('coach.activity.store') }}">

		{{ csrf_field() }}
		
		<div class="form-group row">
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

		<div class="form-group row">
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
		
		<users-selection 
			resource="{{ json_encode($jockeysResource) }}"
			:group="false"
			old="{{ json_encode(old('jockeys')) }}"
		></users-selection>

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

		<div class="form-group row mb-0">
            <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-primary">
                	Create Activity
                </button>
            </div>
        </div>
	</form>

	
</div>
@endsection

{{-- <script>
	window.oldFormValues = {
		start_date: {!! json_encode(old('start_date', null)) !!}
	};
</script>  --}}