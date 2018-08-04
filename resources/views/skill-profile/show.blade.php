@extends('layouts.app')

@section('content')


<div class="container">
	<div>
		<h2>Skills Profile</h2>
	</div>

	<p>Coach {{ $skillProfile->coach->full_name }}</p>
	<p>Date {{ $skillProfile->formattedStart }}</p>
	<p>Start Time {{ $skillProfile->formattedStartTimeAmPm }}</p>


		<br><br>
		
		<range-slider-skills
			:is-disabled="true"
			name="riding_rating"
			:old-value="{{ $skillProfile->riding_rating }}"
		></range-slider-skills>
		<p>{{ $skillProfile->riding_observation }}</p>

        <br><br>

        <range-slider-skills
			:is-disabled="true"
			name="simulator_rating"
			:old-value="{{ $skillProfile->simulator_rating }}"
		></range-slider-skills>
		<div class="form-control">
        	<p>{{ $skillProfile->simulator_observation }}</p>
        </div>
        <br><br>

        <range-slider-skills
			:is-disabled="true"
			name="race_riding_skills_rating"
			:old-value="{{ $skillProfile->race_riding_skills_rating }}"
		></range-slider-skills>
		<div class="form-control">
        	<p>{{ $skillProfile->race_riding_skills_observation }}</p>
        </div>
        <br><br>

        <range-slider-skills
			:is-disabled="true"
			name="whip_rating"
			:old-value="{{ $skillProfile->whip_rating }}"
		></range-slider-skills>
		<div class="form-control">
        	<p>{{ $skillProfile->whip_observation }}</p>
        </div>
        <br><br>

        <range-slider-skills
			:is-disabled="true"
			name="fitness_rating"
			:old-value="{{ $skillProfile->fitness_rating }}"
		></range-slider-skills>
		<div class="form-control">
        	<p>{{ $skillProfile->fitness_observation }}</p>
        </div>
        <br><br>

        <range-slider-skills
			:is-disabled="true"
			name="weight_rating"
			:old-value="{{ $skillProfile->weight_rating }}"
		></range-slider-skills>
		<div class="form-control">
        	<p>{{ $skillProfile->weight_observation }}</p>
        </div>
        <br><br>

        <range-slider-skills
			:is-disabled="true"
			name="communication_rating"
			:old-value="{{ $skillProfile->communication_rating }}"
		></range-slider-skills>
		<div class="form-control">
        	<p>{{ $skillProfile->communication_observation }}</p>
        </div>
        <br><br>

        <range-slider-skills
			:is-disabled="true"
			name="professionalism_rating"
			:old-value="{{ $skillProfile->professionalism_rating }}"
		></range-slider-skills>
		<div class="form-control">
        	<p>{{ $skillProfile->professionalism_observation }}</p>
        </div>
        <br><br>
	
		<div class="form-control">
        	<p>{{ $skillProfile->summary }}</p>
        </div>

        <br><br><br>

        <h3>Skills Profile Archive</h3>
        @foreach($previousSkillProfiles as $profile)
        	<div>
        		<p>{{ $profile->formattedStart }}</p>
        		<a href="{{ route('skill-profile.show', $profile) }}" class="btn btn-preview">View</a>
        		<br><br>
        	</div>
        @endforeach

</div>

@endsection