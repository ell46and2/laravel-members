@extends('layouts.base')

@section('content')

<div class="panel">
    <div class="panel__inner">
        <div class="panel__main">
            <h1 class="[ heading--1 ] [ mb-1 ]">JETS CRM - Create a CRM Jockey</h1>
            Create a CRM Jockey if the Jockey doesn't already exist on the system.  
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel__inner">
        <div class="panel__header">
            <h2 class="panel__heading">
                All CRM Records
            </h2>
        </div>

        <div class="panel__main">
        	<crm-jockey-search
        		resource="{{ json_encode($crmJockeysResource) }}"
        	></crm-jockey-search>

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Jockey</th>
                        <th>Location</th>
                        <th>Date</th>
                        <th>Follow up</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($crmRecords as $record)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="[ table__avatar ] [ avatar ]">
                                        <div class="avatar__image" style="background-image:url('{{ $record->managable->getAvatar() }}');"></div>
                                    </div>
                                    {{ $record->managable->full_name }}
                                </div>
                            </td>
                            <td>{{ $record->location }}</td>
                            <td>{{ $record->formattedCreatedAt }}</td>
                            <td>{{ $record->formattedReviewDate }}</td>
                            <td class="text-right">
                                <a class="button button--primary" href="{{ route('jets.crm.show', $record) }}">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
        {{ $crmRecords->links() }}

        <a href="{{ route('jets.crm.jockey-create') }}" class="button button--primary button--block mb-3 mt-1">Create CRM Jockey</a>
    </div>
</div>

@endsection