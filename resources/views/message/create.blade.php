@extends('layouts.app')

@section('content')


<div class="container">
	
	<message-create
		resources="{{ json_encode($emailResource) }}"
	></message-create>

</div>

@endsection