@extends('layouts.base')

@section('content')

<div class="panel">
    <div class="panel__inner">
        <div class="panel__main">
            <h1 class="[ heading--1 ] [ mb-1 ]">Documents</h1>
            {{-- A list of All Jockeys on the Jockey Coaching Programme --}}
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel__inner">
        <div class="panel__header">
            <h2 class="panel__heading">
                List of Documents
                <div class="[ text--color-base text--size-base ] [ font-weight-normal ] [ mt-1 ]">Click Download to view the file</div>
            </h2>

        </div>

        <div class="panel__main flow-vertical--3">
            <div class="five-col-grid">
                @foreach($documents as $document)
                    <div class="five-col-grid__col">
                        <div class="document-card">
                            <div class="document-card__icon">
                                @svg( 'document', 'icon')
                                <div class="document-card__extension">{{ $document->extension }}</div>
                            </div>
                            <div class="document-card__main">
                                <div class="document-card__filename">{{ $document->title }}</div>
                                <div class="document-card__description">
                                    {{ $document->description }}
                                </div>
                                <a class="button button--primary" href="{{ $document->getDocument() }}">Download</a>
                                @if($currentRole === 'admin')
                                	<div class="document-card__options">
	                                    <a class="link--underlined" href="{{ route('admin.document.edit', $document) }}">Edit</a>
                                        <form 
                                            method="POST" 
                                            action="{{ route('admin.document.delete', $document) }}"
                                            class="[ js-confirmation ]"
                                            data-confirm="Are you sure you wish to delete this Document?"
                                        >
                                            {{ csrf_field() }}
                                            <input type="hidden" name="_method" value="delete" />
                                            <button class="button button--text" type="submit">Remove</button>
                                        </form>
	                                    {{-- <a class="link--underlined" href="{{ route('admin.document.delete', $document) }}">Remove</a> --}}
	                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>

@endsection