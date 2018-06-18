@extends('layouts.app')

@section('content')

    <attachment-upload
        model-type="activity"
        model-id="{{ $activity->id }}"
    ></attachment-upload>

	<br><br><br><br>

	<comments
		endpoint="{{ route('activity.comment.index', $activity) }}"
		recipient-id="2"
	></comments> 

         
@endsection
