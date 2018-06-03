@extends('layouts.app')

@section('content')

@if(Session::has('registered'))
<p class="alert alert-info">You have registered</p>
@endif

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit Profile</div>

                <div class="card-body">
                    <form 
                        method="POST" 
                        action="{{ route('jockey.profile.update') }}"
                        enctype="multipart/form-data"
                    >
                        @csrf

                        {{ method_field('PUT') }} 

                        @include('form.partials._input', [
                            'label' => 'Middle name',
                            'field' => 'middle_name',
                            'type' => 'text',
                            'errors' => $errors,
                            'value' => $jockey->middle_name
                        ])

                        @include('form.partials._input', [
                            'label' => 'Alias',
                            'field' => 'alias',
                            'type' => 'text',
                            'errors' => $errors,
                            'value' => $jockey->alias
                        ])

                        @include('form.partials._input', [
                            'label' => 'Address line 1',
                            'field' => 'address_1',
                            'type' => 'text',
                            'attributes' => 'required',
                            'errors' => $errors,
                            'value' => $jockey->address_1
                        ])

                        @include('form.partials._input', [
                            'label' => 'Address line 2',
                            'field' => 'address_2',
                            'type' => 'text',
                            'errors' => $errors,
                            'value' => $jockey->address_2
                        ])

                        @include('form.partials._input', [
                            'label' => 'County',
                            'field' => 'county',
                            'type' => 'text',
                            'attributes' => 'required',
                            'errors' => $errors,
                            'value' => $jockey->county
                        ])

                        @include('form.partials._input', [
                            'label' => 'Country',
                            'field' => 'country',
                            'type' => 'text',
                            'attributes' => 'required',
                            'errors' => $errors,
                            'value' => $jockey->country
                        ])

                        @include('form.partials._input', [
                            'label' => 'Post code',
                            'field' => 'postcode',
                            'type' => 'text',
                            'attributes' => 'required',
                            'errors' => $errors,
                            'value' => $jockey->postcode
                        ])

                        @include('form.partials._input', [
                            'label' => 'Telephone',
                            'field' => 'telephone',
                            'type' => 'text',
                            'attributes' => 'required',
                            'errors' => $errors,
                            'value' => $jockey->telephone
                        ])

                        @include('form.partials._input', [
                            'label' => 'Twitter handle',
                            'field' => 'twitter_handle',
                            'type' => 'text',
                            'errors' => $errors,
                            'value' => $jockey->twitter_handle
                        ])

                        @include('form.partials._input', [
                            'label' => 'Email Address',
                            'field' => 'email',
                            'type' => 'email',
                            'attributes' => 'required',
                            'errors' => $errors,
                            'value' => $jockey->email
                        ])

                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right" for="avatar_image">Avatar image</label>
                            <div class="col-md-6">
                                <input class="form-control" type="file" name="avatar_image" id="avatar_image">  
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Update
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
