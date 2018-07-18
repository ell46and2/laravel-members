@extends('layouts.app')

@section('content')


<div class="container">
	<h2>Skills Profile</h2>

	<range-slider
		:maximum="7"
		:minimum="1"
		:interval="1"
	></range-slider>
</div>

@endsection