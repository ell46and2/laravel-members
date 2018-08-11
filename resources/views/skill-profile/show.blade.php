@extends('layouts.base')

@section('content')

<div class="panel">
    <div class="panel__inner">
        <div class="panel__main">
            <h1 class="[ heading--1 ] [ mb-1 ]">Skills Profile</h1>
            Copy Needed
        </div>
    </div>
</div>

<div class="panel panel--is-stack">
    <div class="panel__inner">
        <div class="panel__pre-header">
            <div class="panel__pre-header-primary">
                <ul class="panel__pre-header-definition-list">
                    <li>
                        <dl>
                            <dt>Coach</dt>
                            <dd>{{ $skillProfile->coach->full_name }}</dd>
                        </dl>
                    </li>
                    <li>
                        <dl>
                            <dt>Date</dt>
                            <dd>{{ $skillProfile->formattedStart }}</dd>
                        </dl>
                    </li>
                    <li>
                        <dl>
                            <dt>Start time</dt>
                            <dd>{{ $skillProfile->formattedStartTime }}</dd>
                        </dl>
                    </li>
                </ul>
            </div>
            <div class="panel__pre-header-secondary">
                <span class="text-nowrap">I.D {{ $skillProfile->id }}</span>
            </div>
        </div>
        <div class="panel__header">
            <h2 class="panel__heading">
                Ratings
            </h2>
        </div>

        <div class="panel__main">
        	<dl class="definition-list definition-list--stacked">
				<range-slider-skills
					label="Riding"
					:is-disabled="true"
					name="riding_rating"
					:old-value="{{ $skillProfile->riding_rating }}"
				></range-slider-skills>
				<p class="pb-2">{{ $skillProfile->riding_observation }}</p>

				<range-slider-skills
					label="Simulator"
					:is-disabled="true"
					name="simulator_rating"
					:old-value="{{ $skillProfile->simulator_rating }}"
				></range-slider-skills>
		        <p class="pb-2">{{ $skillProfile->simulator_observation }}</p>

		       

		        <range-slider-skills
		        	label="Race Riding Skills"
					:is-disabled="true"
					name="race_riding_skills_rating"
					:old-value="{{ $skillProfile->race_riding_skills_rating }}"
				></range-slider-skills>
				<p class="pb-2">{{ $skillProfile->race_riding_skills_observation }}</p>


		        <range-slider-skills
		        	label="Use of the Whip"
					:is-disabled="true"
					name="whip_rating"
					:old-value="{{ $skillProfile->whip_rating }}"
				></range-slider-skills>
				<p class="pb-2">{{ $skillProfile->whip_observation }}</p>


		        <range-slider-skills
		        	label="Fitness"
					:is-disabled="true"
					name="fitness_rating"
					:old-value="{{ $skillProfile->fitness_rating }}"
				></range-slider-skills>
				<p class="pb-2">{{ $skillProfile->fitness_observation }}</p>

		        <range-slider-skills
		        	label="Weight and Nutrition"
					:is-disabled="true"
					name="weight_rating"
					:old-value="{{ $skillProfile->weight_rating }}"
				></range-slider-skills>
				<p class="pb-2">{{ $skillProfile->weight_observation }}</p>

		        <range-slider-skills
		        	label="Communication"
					:is-disabled="true"
					name="communication_rating"
					:old-value="{{ $skillProfile->communication_rating }}"
				></range-slider-skills>
				<p class="pb-2">{{ $skillProfile->communication_observation }}</p>

		        <range-slider-skills
		        	label="Professionalism"
					:is-disabled="true"
					name="professionalism_rating"
					:old-value="{{ $skillProfile->professionalism_rating }}"
				></range-slider-skills>
				<p class="pb-2">{{ $skillProfile->professionalism_observation }}</p>
        	</dl>
        </div>
    </div>
</div>

<div class="panel">
	<div class="panel__inner">
	    <div class="panel__header">
	        <h2 class="panel__heading">
	           	Skills Feedback
	        </h2>
	    </div>
	

		<div class="panel__main">
			<p>{{ $skillProfile->summary }}</p>
		</div>
	</div>
</div>

{{-- <h3>Skills Profile Archive</h3>
@foreach($previousSkillProfiles as $profile)
	<div>
		<p>{{ $profile->formattedStart }}</p>
		<a href="{{ route('skill-profile.show', $profile) }}" class="btn btn-preview">View</a>
		<br><br>
	</div>
@endforeach --}}

@endsection