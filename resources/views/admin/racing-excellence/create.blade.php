@extends('layouts.app')

@section('content')

<autocomplete
	resource="{{ json_encode($jockeysResource) }}"
></autocomplete>

@endsection