@extends('layouts.base')

@section('content')

<div class="panel">
    <div class="panel__inner">
        <div class="panel__main">
            <h1 class="[ heading--1 ] [ mb-1 ]">Compose new message</h1>
            {{-- A list of jockeys you current coach --}}
        </div>
    </div>
</div>

<message-create
	resources="{{ json_encode($messageResource) }}"
	current-role="{{ $currentRole }}"
	preselect-recipient-id="{{ $preselectRecipientId }}"
></message-create>

@endsection