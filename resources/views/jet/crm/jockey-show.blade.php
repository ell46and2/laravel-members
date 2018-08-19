@extends('layouts.base')

@section('content')

<div class="panel">
    <div class="panel__inner">
        <div class="panel__main">
            <h1 class="[ heading--1 ] [ mb-1 ]">JETS CRM</h1>
            Copy needed  
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel__inner">
        <div class="panel__header">
            <h2 class="panel__heading">
                CRM Records {{ $currentRole === 'jets' ? "for {$jockey->full_name}" : '' }}
            </h2>
        </div>

        <div class="panel__main">
        	
           <a href="{{ route($jockey->crmRecordCreateLink, $jockey) }}" class="panel__call-to-action button--block mb-3 mt-1">Create CRM Record</a>

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Location</th>
                        <th>Date</th>
                        <th>Follow up</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($crmRecords as $record)
                        <tr>
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
    </div>
</div>


@endsection