@extends('layouts.app')

@section('content')


<div class="container">
	<div>
		<h2>Skills Profile</h2>
	</div>
	<form method="POST" action="{{ route('skill-profile.update', $skillProfile) }}">

		@csrf
		@method('put')
	
		<div class="form-group row">
			<div class="form-control{{ $errors->has('start_date') ? ' is-invalid' : '' }}">
				<datepicker-component name="start_date" placeholder="Select Date" old="{{ old('start_date', $skillProfile->startDate) }}"></datepicker-component>
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
				<timepicker-component old="{{ old('start_time', $skillProfile->formattedStartTime) }}"></timepicker-component>		
			</div>
			@if ($errors->has('start_time'))
	            <span class="invalid-feedback">
	                <strong>{{ $errors->first('start_time') }}</strong>
	            </span>
	        @endif
		</div>

		<br><br>
		
		<range-slider-skills
			:is-disabled="true"
			name="riding_rating"
			:old-value="{{ $skillProfile->riding_rating }}"
		></range-slider-skills>
		<div class="form-control">
        	<textarea name="riding_observation" cols="30" rows="10">{{ old('riding_observation', $skillProfile->riding_observation) }}</textarea>
        </div>
        <br><br>

        <range-slider-skills
			:is-disabled="true"
			name="simulator_rating"
			:old-value="{{ $skillProfile->simulator_rating }}"
		></range-slider-skills>
		<div class="form-control">
        	<textarea name="simulator_observation" cols="30" rows="10">{{ old('simulator_observation', $skillProfile->simulator_observation) }}</textarea>
        </div>
        <br><br>

        <range-slider-skills
			:is-disabled="true"
			name="race_riding_skills_rating"
			:old-value="{{ $skillProfile->race_riding_skills_rating }}"
		></range-slider-skills>
		<div class="form-control">
        	<textarea name="race_riding_skills_observation" cols="30" rows="10">{{ old('race_riding_skills_observation', $skillProfile->race_riding_skills_observation) }}</textarea>
        </div>
        <br><br>

        <range-slider-skills
			:is-disabled="true"
			name="whip_rating"
			:old-value="{{ $skillProfile->whip_rating }}"
		></range-slider-skills>
		<div class="form-control">
        	<textarea name="whip_observation" cols="30" rows="10">{{ old('whip_observation', $skillProfile->whip_observation) }}</textarea>
        </div>
        <br><br>

        <range-slider-skills
			:is-disabled="true"
			name="fitness_rating"
			:old-value="{{ $skillProfile->fitness_rating }}"
		></range-slider-skills>
		<div class="form-control">
        	<textarea name="fitness_observation" cols="30" rows="10">{{ old('fitness_observation', $skillProfile->fitness_observation) }}</textarea>
        </div>
        <br><br>

        <range-slider-skills
			:is-disabled="true"
			name="weight_rating"
			:old-value="{{ $skillProfile->weight_rating }}"
		></range-slider-skills>
		<div class="form-control">
        	<textarea name="weight_observation" cols="30" rows="10">{{ old('weight_observation', $skillProfile->weight_observation) }}</textarea>
        </div>
        <br><br>

        <range-slider-skills
			:is-disabled="true"
			name="communication_rating"
			:old-value="{{ $skillProfile->communication_rating }}"
		></range-slider-skills>
		<div class="form-control">
        	<textarea name="communication_observation" cols="30" rows="10">{{ old('communication_observation', $skillProfile->communication_observation) }}</textarea>
        </div>
        <br><br>

        <range-slider-skills
			:is-disabled="true"
			name="professionalism_rating"
			:old-value="{{ $skillProfile->professionalism_rating }}"
		></range-slider-skills>
		<div class="form-control">
        	<textarea name="professionalism_observation" cols="30" rows="10">{{ old('professionalism_observation', $skillProfile->professionalism_observation) }}</textarea>
        </div>
        <br><br>
	
		<div class="form-control">
        	<textarea name="summary" cols="30" rows="10">{{ old('summary', $skillProfile->summary) }}</textarea>
        </div>


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