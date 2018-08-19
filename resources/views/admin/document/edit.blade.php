@extends('layouts.base')

@section('content')

<div class="panel">
    <div class="panel__inner">
        <div class="panel__main">
            <h1 class="[ heading--1 ] [ mb-1 ]">Edit Document</h1>
        </div>
    </div>
</div>

<upload-document
	old-document="{{ json_encode($document) }}"
></upload-document>

@endsection
