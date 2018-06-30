@extends('layouts.app')

@section('content')

<div class="container">
	
	<form method="POST" action="{{ route('admin.racing-excellence.update', $racingExcellence) }}">

		@csrf
        @method('put')

        <div class="form-group row">
			<div class="form-control{{ $errors->has('coach_id') ? ' is-invalid' : '' }}">
				@foreach($coaches as $coach)
					<input 
						type="radio" 
						name="coach_id" 
						id="coach-{{ $coach->id }}" 
						value="{{ $coach->id }}"
						{{ old('coach_id', $racingExcellence->coach_id) == $coach->id ? 'checked' : '' }}
					>
					<label for="coach-{{ $coach->id }}">
						{{ $coach->full_name }}
					</label>
					<br>
				@endforeach				
		    </div>
		    @if ($errors->has('coach_id'))
	            <span class="invalid-feedback">
	                <strong>{{ $errors->first('coach_id') }}</strong>
	            </span>
	        @endif
	    </div>
		<br><br>

		<div class="form-group row">
			<div class="form-control{{ $errors->has('start_date') ? ' is-invalid' : '' }}">
				<datepicker-component name="start_date" placeholder="Select Date" old="{{ old('start_date', $racingExcellence->startDate ) }}"></datepicker-component>
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
				<timepicker-component old="{{ old('start_time', $racingExcellence->formattedStartTime) }}"></timepicker-component>		
			</div>
			@if ($errors->has('start_time'))
	            <span class="invalid-feedback">
	                <strong>{{ $errors->first('start_time') }}</strong>
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
                        	{{ old('location_id', $racingExcellence->location_id) == $location->id ? 'selected' : '' }}
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
            <label class="col-form-label text-md-right" for="location_id">Choose a Series</label>
            
            <div class="">
                <select class="form-control" name="series_id" id="series_id" required>
                   @foreach($seriesTypes as $series)
                        <option 
                        	value="{{ $series->id }}"
                        	{{ old('series_id', $racingExcellence->series_id) == $series->id ? 'selected' : '' }}
                        >
                            {{ ucfirst($series->name) }}
                        </option>
                   @endforeach
                </select>

                @if ($errors->has('series_id'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('series_id') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <br><br>

		<divisions
			jockeys-resource="{{ json_encode($jockeysResource) }}"
			race-resource="{{ json_encode($raceResource) }}"
			:is-edit="true"
			external-avatar-url="{{ App\Models\Jockey::getExternalJockeyAvatar() }}"
			old="{{ json_encode(old('divisions')) }}"
		></divisions>
		
		<br><br>

		<div class="form-group row mb-0">
            <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-primary">
                	Save
                </button>
            </div>
        </div>
		
	</form>
</div>

@endsection