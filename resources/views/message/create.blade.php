@extends('layouts.app')

@section('content')


<div class="container">
	
	<message-create
		resources="{{ json_encode($messageResource) }}"
	></message-create>

</div>

@endsection