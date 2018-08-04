@extends('layouts.app')

@section('content')


<div class="container">

	<h4>Send For Approval</h4>

	<form method="POST" action="{{ route('pdp.submit.store', $pdp) }}">
        @csrf
        
        <div class="form-group row mb-0">
            <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-primary">
                    Send For Approval
                </button>
            </div>
        </div>
    </form>
</div>

@endsection