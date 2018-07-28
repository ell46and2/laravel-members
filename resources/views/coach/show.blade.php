@extends('layouts.app')

@section('content')
@php
    $isAdmin = auth()->user()->isAdmin();
@endphp

@include('user.partials._profile-picture-edit', [ 'user' => $coach])

<br><br><br>
@if($isAdmin)
	<div>
		<h3>Status</h3>
		<form 
			method="POST" 
			action="{{ route('coach.status.update', $coach) }}"
			class="[ js-confirmation ]"
		    data-confirm="Are you sure you want to change the status?"
		>
			{{ csrf_field() }}
			@method('put')

			<div class="form-group row">
			    
			    <div class="col-md-6">
			        <select class="form-control" name="status" id="status" required>
			           @foreach(['active', 'suspended', 'deleted'] as $status)
			                <option value="{{ $status }}" {{ old('status', $coach->status) === $status ? 'selected' : '' }}>
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
@if($isAdmin)
	@include('coach.partials._edit-form')
@else
	@include('coach.partials._details')
@endif

@if($isAdmin && $coach->status !== 'deleted')
	<jockey-assign
		:coach-id="{{ $coach->id }}"
		resource="{{ json_encode($jockeysResource) }}"
		:current="{{ $coach->jockeys->pluck('id') }}"
	>
	</jockey-assign>
@else
	<h3>Jockeys</h3>
	@foreach($coach->jockeys as $jockey)
		<div>
			<p>{{ $jockey->full_name }}</p>
		</div>
	@endforeach
@endif

@endsection