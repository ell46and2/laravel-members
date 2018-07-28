@extends('layouts.app')

@section('content')
@php
    $isAdmin = auth()->user()->isAdmin();
@endphp


@include('user.partials._profile-picture-edit', [ 'user' => $jet])


<br><br><br>
@if($isAdmin)
	@include('jet.partials._edit-form')
@else
	@include('jet.partials._details')
@endif

@if($isAdmin)
	<div>
		<h3>Status</h3>
		<form 
			method="POST" 
			action="{{ route('jet.status.update', $jet) }}"
			class="[ js-confirmation ]"
		    data-confirm="Are you sure you want to change the status?"
		>
			{{ csrf_field() }}
			@method('put')

			<div class="form-group row">
			    
			    <div class="col-md-6">
			        <select class="form-control" name="status" id="status" required>
			           @foreach(['active', 'suspended', 'gone away', 'deleted'] as $status)
			                <option value="{{ $status }}" {{ old('status', $jet->status) === $status ? 'selected' : '' }}>
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



@endsection

