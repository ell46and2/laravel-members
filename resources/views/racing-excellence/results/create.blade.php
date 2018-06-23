@extends('layouts.app')

@section('content')

	<racing-excellence-results
		resource="{{ json_encode($raceResource) }}"
	></racing-excellence-results>

@endsection
