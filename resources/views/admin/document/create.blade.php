@extends('layouts.app')

@section('content')

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
</div>
@endsection
