@extends('layouts.app')

@section('content')

<notifications
	resource="{{ json_encode($notificationsResource) }}"
></notifications>

@endsection