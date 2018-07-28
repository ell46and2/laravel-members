@extends('layouts.app')

@section('content')

<form method="POST" action="{{ route('admin.jockey.approve', $jockey) }}">
    {{ csrf_field() }}
    <button class="btn btn-danger" type="submit">Approve</button>
</form>

<form 
	method="POST" 
	action="{{ route('admin.jockey.decline', $jockey) }}"
	class="[ js-confirmation ]"
    data-confirm="Are you sure? This Jockey will be permanently deleted."
>
    {{ csrf_field() }}
    <input type="hidden" name="_method" value="delete" />
    <button class="btn btn-danger" type="submit">Decline</button>
</form>
<br><br><br>
@include('user.partials._profile-picture-edit', [ 'user' => $jockey])
<br><br><br>
@include('jockey.partials._racing-stats')

<br><br><br>
@include('jockey.partials._edit-form')

@endsection