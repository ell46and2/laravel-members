@extends('layouts.app')

@section('content')
    <attachment-upload
        model-type="activity"
        model-id="{{ $activity->id }}"
    />          
@endsection
