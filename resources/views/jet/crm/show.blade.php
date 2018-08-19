@extends('layouts.base')

@section('content')
@php
    $isJets = $currentRole = 'jets';
@endphp

<div class="panel">
    <div class="panel__inner">
        <div class="panel__main">
            <h1 class="[ heading--1 ] [ mb-1 ]">JETS CRM - {{ $crmRecord->managable->full_name }}
                @if($isJets || $isAssignedCoach)
                	<a class="[ button button--primary ] [ heading__button ]" href="{{ route('jets.crm.edit', $crmRecord) }}">Edit</a>
					<form
						method="POST" 
						action="{{ route('jets.crm.delete', $crmRecord) }}"
						class="[ js-confirmation ] d-inline-flex"
					    data-confirm="Are you sure you want to delete this CRM record?"
					>
						{{ csrf_field() }}
			    		<input type="hidden" name="_method" value="delete" />
			    		<button 
			    			style="display: inherit;" 
			    			class="[ button button--primary ] [ heading__button ]" 
			    			type="submit"
			    		>Delete</button>
					</form>
				@endif
            </h1>

        </div>
    </div>
</div>

<div class="panel">
    <div class="panel__inner">
        <div class="panel__pre-header">
            <div class="panel__pre-header-primary">
                <ul class="panel__pre-header-definition-list">
                    <li>
                        <dl>
                            <dt>Date</dt>
                            <dd>{{ $crmRecord->formattedCreatedAt }}</dd>
                        </dl>
                    </li>
                    <li>
                        <dl>
                            <dt>Location</dt>
                            <dd>{{ $crmRecord->location }}</dd>
                        </dl>
                    </li>
                    <li>
                        <dl>
                            <dt>Review Date</dt>
                            <dd>{{ $crmRecord->formattedReviewDate }}</dd>
                        </dl>
                    </li>
                </ul>
            </div>
        </div>

        <div class="panel__header">
            <h2 class="panel__heading">
                Comment
            </h2>
        </div>

        <div class="panel__main">
                {{ $crmRecord->comment }}
        </div>

        {{-- if has attachment --}}
        @if($crmRecord->document_filename)
        	<div class="panel__header">
        	    <h2 class="panel__heading">
        	       	Attachment
        	    </h2>
        	</div>

        	<div class="panel__main">
        	        {{-- {{ $crmRecord->document_filename }} attachment file for download here --}}
    	        <div class="document-card">
                    <div class="document-card__icon">
                        @svg( 'document', 'icon')
                        <div class="document-card__extension">{{ $crmRecord->extension }}</div>
                    </div>
                    <div class="document-card__main">
                        <div class="document-card__filename">{{ $crmRecord->document_filename }}</div>
                            <a class="button button--primary" href="{{ $crmRecord->getDocument() }}">Download</a>
                        </div>
                    </div>
        	</div>
        @endif
        
    </div>
</div>

@endsection