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
                        @method('put')

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

                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right" for="country_id">Country</label>
                            
                            <div class="col-md-6">
                                <select class="form-control" name="country_id" id="country_id" required>
                                   @foreach($countries as $country)
                                        <option value="{{ $country->id }}"{{ $jockey->country_id === $country->id ? ' selected="selected"' : '' }}>
                                            {{ ucfirst($country->name) }}
                                        </option>
                                   @endforeach
                                </select>

                                @if ($errors->has('country_id'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('country_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right" for="county_id">County</label>
                            
                            <div class="col-md-6">
                                <select class="form-control" name="county_id" id="county_id" required>
                                   @foreach($counties as $county)
                                        <option value="{{ $county->id }}"{{ $jockey->county_id === $county->id ? ' selected="selected"' : '' }}>
                                            {{ ucfirst($county->name) }}
                                        </option>
                                   @endforeach
                                </select>

                                @if ($errors->has('county_id'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('county_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

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
