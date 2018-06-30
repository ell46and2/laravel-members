@extends('layouts.app')

@section('content')

<div class="container">
	

		<divisions
			jockeys-resource="{{ json_encode($jockeysResource) }}"
			race-resource="{{ json_encode($raceResource) }}"
			:is-edit="true"
			external-avatar-url="{{ App\Models\Jockey::getExternalJockeyAvatar() }}"
		></divisions>
		
</div>

@endsection