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

<form method="POST" action="{{ route('skill-profile.store') }}">
	{{ csrf_field() }}

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
                </div>
            </div>

        </div>
    </div>

    <div class="panel pt-3">
         <div class="panel__inner">
             <div class="panel__header">
                 <h2 class="panel__heading">
                     Select a Jockey
                     <span class="[ text--size-sm text--color-base ] [ font-weight-normal ] [ ml-1 ]">Copy needed</span>
                 </h2>
             </div>

             <users-selection 
 				resource="{{ json_encode($jockeysResource) }}"
 				:group="false"
 				old="{{ json_encode(old('jockeys')) }}"
 			></users-selection>

         </div>
    </div>

    <div class="panel pt-3">
		<div class="panel__inner">
		    <div class="panel__header">
		        <h2 class="panel__heading">
		            Ratings
		            <span class="[ text--size-sm text--color-base ] [ font-weight-normal ] [ ml-1 ]">Copy needed</span>
		        </h2>
		    </div>
		

			<div class="panel__main">
			  	<dl class="definition-list definition-list--stacked">
			    
				    <range-slider-skills
				      label="Riding"
				      name="riding_rating"
				      :old-value="{{ old('riding_rating') ?? 0 }}"
				    ></range-slider-skills>
			    
				    @include('form.partials._textarea', [
				        'field' => 'riding_observation',
				        'errors' => $errors,
				        'value' => old('riding_observation'),
				        'placeholder' => 'Add Comment',
				    ])

				    <range-slider-skills
				      label="Simulator"
				      name="simulator_rating"
				      :old-value="{{ old('simulator_rating') ?? 0 }}"
				    ></range-slider-skills>
			    
				    @include('form.partials._textarea', [
				        'field' => 'simulator_observation',
				        'errors' => $errors,
				        'value' => old('simulator_observation'),
				        'placeholder' => 'Add Comment',
				    ])
					
					<range-slider-skills
					  label="Race Riding Skills"
					  name="race_riding_skills_rating"
					  :old-value="{{ old('race_riding_skills_rating') ?? 0 }}"
					></range-slider-skills>
					
					@include('form.partials._textarea', [
					    'field' => 'race_riding_skills_observation',
					    'errors' => $errors,
					    'value' => old('race_riding_skills_observation'),
					    'placeholder' => 'Add Comment',
					])

					<range-slider-skills
					  label="Use of the Whip"
					  name="whip_rating"
					  :old-value="{{ old('whip_rating') ?? 0 }}"
					></range-slider-skills>
					
					@include('form.partials._textarea', [
					    'field' => 'whip_observation',
					    'errors' => $errors,
					    'value' => old('whip_observation'),
					    'placeholder' => 'Add Comment',
					])

					<range-slider-skills
					  label="Fitness"
					  name="fitness_rating"
					  :old-value="{{ old('fitness_rating') ?? 0 }}"
					></range-slider-skills>
					
					@include('form.partials._textarea', [
					    'field' => 'fitness_observation',
					    'errors' => $errors,
					    'value' => old('fitness_observation'),
					    'placeholder' => 'Add Comment',
					])
	
					<range-slider-skills
					  label="Weight and Nutrition"
					  name="weight_rating"
					  :old-value="{{ old('weight_rating') ?? 0 }}"
					></range-slider-skills>
					
					@include('form.partials._textarea', [
					    'field' => 'weight_observation',
					    'errors' => $errors,
					    'value' => old('weight_observation'),
					    'placeholder' => 'Add Comment',
					])

					<range-slider-skills
					  label="Communication"
					  name="communication_rating"
					  :old-value="{{ old('communication_rating') ?? 0 }}"
					></range-slider-skills>
					
					@include('form.partials._textarea', [
					    'field' => 'communication_observation',
					    'errors' => $errors,
					    'value' => old('communication_observation'),
					    'placeholder' => 'Add Comment',
					])

					<range-slider-skills
					  label="Professionalism"
					  name="professionalism_rating"
					  :old-value="{{ old('professionalism_rating') ?? 0 }}"
					></range-slider-skills>
					
					@include('form.partials._textarea', [
					    'field' => 'professionalism_observation',
					    'errors' => $errors,
					    'value' => old('professionalism_observation'),
					    'placeholder' => 'Add Comment',
					])
				  
				</dl>
			</div>
		</div>
    </div>

    <div class="panel pt-3">
		<div class="panel__inner">
		    <div class="panel__header">
		        <h2 class="panel__heading">
		           	Skills Feedback
		            <span class="[ text--size-sm text--color-base ] [ font-weight-normal ] [ ml-1 ]">Copy needed</span>
		        </h2>
		    </div>
		

			<div class="panel__main">
				<textarea class="form-control" name="summary" rows="8" cols="80"  placeholder="Enter feedback...">{{ old('summary') }}</textarea>

				@if ($errors->has('summary'))
				    <span class="invalid-feedback">
				        <strong>{{ $errors->first('summary') }}</strong>
				    </span>
				@endif
			</div>
		</div>

		<div class="row">
		    <div class="col-12 mt-3">
		        <button class="button button--primary button--success button--squared button--block" type="submit">Save</button>
		    </div>
		</div>
	</div>

</form>

@endsection

{{-- <div class="container">
	<div>
		<h2>Skills Profile</h2>
	</div>
	<form method="POST" action="{{ route('skill-profile.store') }}">

		{{ csrf_field() }}
	
		<div class="form-group row">
			<div class="form-control{{ $errors->has('start_date') ? ' is-invalid' : '' }}">
				<datepicker-component name="start_date" placeholder="Select Date" old="{{ old('start_date') }}"></datepicker-component>
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
		
		<range-slider-skills
			:is-disabled="true"
			name="riding_rating"
		></range-slider-skills>
		<div class="form-control">
        	<textarea name="riding_observation" cols="30" rows="10">{{ old('riding_observation') }}</textarea>
        </div>
        <br><br>

        <range-slider-skills
			:is-disabled="true"
			name="simulator_rating"
		></range-slider-skills>
		<div class="form-control">
        	<textarea name="simulator_observation" cols="30" rows="10">{{ old('simulator_observation') }}</textarea>
        </div>
        <br><br>

        <range-slider-skills
			:is-disabled="true"
			name="race_riding_skills_rating"
		></range-slider-skills>
		<div class="form-control">
        	<textarea name="race_riding_skills_observation" cols="30" rows="10">{{ old('race_riding_skills_observation') }}</textarea>
        </div>
        <br><br>

        <range-slider-skills
			:is-disabled="true"
			name="whip_rating"
		></range-slider-skills>
		<div class="form-control">
        	<textarea name="whip_observation" cols="30" rows="10">{{ old('whip_observation') }}</textarea>
        </div>
        <br><br>

        <range-slider-skills
			:is-disabled="true"
			name="fitness_rating"
		></range-slider-skills>
		<div class="form-control">
        	<textarea name="fitness_observation" cols="30" rows="10">{{ old('fitness_observation') }}</textarea>
        </div>
        <br><br>

        <range-slider-skills
			:is-disabled="true"
			name="weight_rating"
		></range-slider-skills>
		<div class="form-control">
        	<textarea name="weight_observation" cols="30" rows="10">{{ old('weight_observation') }}</textarea>
        </div>
        <br><br>

        <range-slider-skills
			:is-disabled="true"
			name="communication_rating"
		></range-slider-skills>
		<div class="form-control">
        	<textarea name="communication_observation" cols="30" rows="10">{{ old('communication_observation') }}</textarea>
        </div>
        <br><br>

        <range-slider-skills
			:is-disabled="true"
			name="professionalism_rating"
		></range-slider-skills>
		<div class="form-control">
        	<textarea name="professionalism_observation" cols="30" rows="10">{{ old('professionalism_observation') }}</textarea>
        </div>
        <br><br>
	
		<div class="form-control">
        	<textarea name="summary" cols="30" rows="10">{{ old('summary') }}</textarea>
        </div>


		<div class="form-group row mb-0">
            <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-primary">
                	Create
                </button>
            </div>
        </div>

	</form>
</div> --}}