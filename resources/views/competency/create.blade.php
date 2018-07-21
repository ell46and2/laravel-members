@extends('layouts.app')

@section('content')


<div class="container">
	<h2>Skills Profile</h2>

	<h4>PDP slider</h4>
	<range-slider-pdp
		name="test2"
		:old-value="5"
		:is-disabled="true"
	></range-slider-pdp>

	<h4>Skills Profile slider</h4>

	<range-slider-skills
		:is-disabled="true"
		name="test2"
		:old-value="2.5"
	></range-slider-skills>
</div>

@endsection