@extends('layouts.app')

@section('content')
@php
    $isAdmin = $currentUser->isAdmin();
@endphp

@if (session()->has('error'))
   <div class="alert alert-danger">{{ session()->get('error') }}</div>
@endif
@if (session()->has('success'))
   <div class="alert alert-info">{{ session()->get('success') }}</div>
@endif

@include('user.partials._profile-picture-edit', [ 'user' => $jockey])

<br><br><br>
@include('jockey.partials._racing-stats')

<br><br><br>
@if($isAdmin)
	@include('jockey.partials._edit-form')
@else
	{{-- Add jockey details partial --}}
@endif

@if($isAdmin)
	<div>
		<h3>Status</h3>
		<form 
			method="POST" 
			action="{{ route('jockey.status.update', $jockey) }}"
			class="[ js-confirmation ]"
		    data-confirm="Are you sure you want to change the status?"
		>
			{{ csrf_field() }}
			@method('put')

			<div class="form-group row">
			    
			    <div class="col-md-6">
			        <select class="form-control" name="status" id="status" required>
			           @foreach(['active', 'suspended', 'gone away', 'deleted'] as $status)
			                <option value="{{ $status }}" {{ old('status', $jockey->status) === $status ? 'selected' : '' }}>
			                    {{ ucfirst($status) }}
			                </option>
			           @endforeach
			        </select>

			        @if ($errors->has('status'))
			            <span class="invalid-feedback">
			                <strong>{{ $errors->first('status') }}</strong>
			            </span>
			        @endif
			    </div>
			</div>
			<button class="btn btn-danger" type="submit">Update</button>
		</form>
	</div>
@endif

<br><br><br>

@if($isAdmin && $jockey->status !== 'deleted')
	<div>
		Coaches (assign/unassign)
		<coach-assign
			:jockey-id="{{ $jockey->id }}"
			resource="{{ json_encode($coachesResource) }}"
			:current="{{ $jockey->coaches->pluck('id') }}"
		></coach-assign>
	</div>
@else 
	<div>
		Coaches (just list)
		@foreach($jockey->coaches as $coach)
			<div>
				<p>{{ $coach->full_name }}</p>
			</div>
		@endforeach
	</div>
@endif

<br><br><br>

@if($isAdmin || $jockey->isAssignedToCoach($currentUser->id))
	<div>
		<h3>Recent Activities (last 5)</h3>
		<div class="panel__main">
	        <table class="table">
	            <thead>
	                <tr>
	                    <th>Activity type</th>
	                    <th>Coach</th>
	                    <th>Date</th>
	                    <th>Time</th>
	                    <th>Location</th>
	                    <th></th>
	                </tr>
	            </thead>
	            <tbody>
	                @foreach($jockey->lastFiveActivities as $activity)	                            
	                    <tr>
	                        <td>
	                            <a class="table__link" href="">{{ $activity->formattedType }}</a>
	                        </td>
	                        <td>{{ $activity->coach->full_name }}</td>
	                        <td>{{ $activity->formattedStartDayMonth }}</td>
	                        <td>{{ $activity->formattedStartTime }}</td>
	                        <td>{{ $activity->formattedLocation }}</td>
	                        <td class="text-right">
	                            <a class="button button--primary" href="{{ $activity->notificationLink }}">View</a>
	                        </td>
	                    </tr>
	                @endforeach
	            </tbody>
	        </table>
	    </div>

		<h3>Recent Racing Excellence (last 5)</h3>
		<div class="panel__main">
	        <table class="table">
	            <thead>
	                <tr>
	                    <th>Series</th>
	                    <th>Coach</th>
	                    <th>Date</th>
	                    <th>Time</th>
	                    <th>Location</th>
	                    <th></th>
	                </tr>
	            </thead>
	            <tbody>
	                @foreach($jockey->lastFiveRacingExcellence as $race)	                            
	                    <tr>
	                        <td>
	                            <a class="table__link" href="">{{ $race->formattedSeriesName }}</a>
	                        </td>
	                        <td>{{ optional($race->coach)->full_name }}</td>
	                        <td>{{ $race->formattedStartDayMonth }}</td>
	                        <td>{{ $race->formattedStartTime }}</td>
	                        <td>{{ $race->formattedLocation }}</td>
	                        <td class="text-right">
	                            <a class="button button--primary" href="{{ route('racing-excellence.results.create', $race) }}">View</a>
	                        </td>
	                    </tr>
	                @endforeach
	            </tbody>
	        </table>
	    </div>

		<h3>Skills Profile (show bar chart - as on jockey dashboard)</h3>
	</div>
@endif
@endsection

