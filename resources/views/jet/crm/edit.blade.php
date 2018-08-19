@extends('layouts.base')

@section('content')

<div class="panel">
    <div class="panel__inner">
        <div class="panel__main">
            <h1 class="[ heading--1 ] [ mb-1 ]">JETS CRM - Edit</h1>
            Copy needed
        </div>
    </div>
</div>

<crm-record-create
    user-id="{{ $jockey->id }}"
    user-name="{{ $jockey->full_name }}"
    user-type="{{ isset($type) ? $type : 'jockey' }}"
    old-record="{{ json_encode($crmRecord) }}"
></crm-record-create>

@endsection
