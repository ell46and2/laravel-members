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

<form 
    method="POST" 
    action="{{ route('admin.document.store') }}"
    enctype="multipart/form-data"
>
    {{ csrf_field() }}
    <div class="panel">
        <div class="panel__inner">
            <div class="panel__header">
                <h2 class="panel__heading">
                  Add a Document
                  <div class="[ text--color-base text--size-base ] [ font-weight-normal ] [ mt-1 ]">Add a Document for all other users to access</div>
                </h2>
            </div>

            <div class="panel__main flow-vertical--3">

                <div class="mt-2 form-group flow-vertical--1">
                  <label class="text--color-blue">Document Name</label>
                  <input class="form-control" type="text" name="title" value="" placeholder="Enter name...">
                </div>

                <div class="mt-2 form-group flow-vertical--1">
                  <label class="text--color-blue">Description</label>
                  <input class="form-control" type="text" name="description" value="" placeholder="Enter description...">
                </div>

                <div class="flow-vertical--1">
                  <label class="text--color-blue">Upload Document</label>
                    
                    <input 
                        type="file"
                        class="attachment-upload__input" 
                        name="document" 
                        id="document"
                    >
                    <label class="panel__call-to-action" for="document">Attach a File</label>

                </div>
            </div>

        </div>
    </div>

  <button class="button button--primary button--block" type="submit">Upload</button>
</form>

@endsection

{{-- 
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Upload Document</div>

                <div class="card-body">
                    <form 
                        method="POST" 
                        action="{{ route('admin.document.store') }}"
                        enctype="multipart/form-data"
                    >
                        @csrf

                        @include('form.partials._input', [
                            'label' => 'Document title',
                            'field' => 'title',
                            'type' => 'text',
                            'attributes' => 'required autofocus',
                            'errors' => $errors
                        ])

                        @include('form.partials._input', [
                            'label' => 'Document description',
                            'field' => 'description',
                            'type' => 'text',
                            'attributes' => 'required',
                            'errors' => $errors
                        ])

                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right" for="document">Document</label>
                            <div class="col-md-6">

                                <input class="form-control" type="file" name="document" id="document">  
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Upload
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> --}}