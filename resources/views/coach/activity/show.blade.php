@extends('layouts.app')

@section('content')

    <attachment-upload
        model-type="activity"
        model-id="{{ $activity->id }}"
    ></attachment-upload>

	<br><br><br><br>

	<attachments></attachments>

	<attachment-modal></attachment-modal>

	{{-- <video-player
		video-uid="{{ $video->uid }}"
		video-url="{{ $video->getStreamUrl() }}"
		thumbnail-url="{{ $video->getThumb() }}"
	></video-player> --}}

	<br><br><br><br>

	{{-- Loop through each jockey and create tab with comments in  --}}
	<comments
		endpoint="{{ route('activity.comment.index', $activity) }}"
		recipient-id="2"
		jockey-id="2"
		is-current-user-jockey="{{ auth()->user()->isJockey() }}"
		can-user-add-comments="{{ $activity->isAssignedToUser(auth()->user()) }}"
	></comments> 

         
@endsection
