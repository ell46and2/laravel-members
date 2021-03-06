@extends('layouts.app')

@section('content')

@if(Session::has('registered'))
<p class="alert alert-info">You have registered</p>
@endif

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        @include('form.partials._input', [
                            'label' => 'First name',
                            'field' => 'first_name',
                            'type' => 'text',
                            'attributes' => 'required autofocus',
                            'errors' => $errors
                        ])

                        @include('form.partials._input', [
                            'label' => 'Last name',
                            'field' => 'last_name',
                            'type' => 'text',
                            'attributes' => 'required',
                            'errors' => $errors
                        ])

                        @include('form.partials._input', [
                            'label' => 'Middle name',
                            'field' => 'middle_name',
                            'type' => 'text',
                            'errors' => $errors
                        ])

                        @include('form.partials._input', [
                            'label' => 'Alias',
                            'field' => 'alias',
                            'type' => 'text',
                            'errors' => $errors
                        ])

                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right" for="name">Gender</label>
                            
                            <div class="col-md-6">
                                <select class="form-control" name="gender" id="gender" required>
                                   @foreach(['male', 'female'] as $gender)
                                        <option value="{{ $gender }}">
                                            {{ ucfirst($gender) }}
                                        </option>
                                   @endforeach
                                </select>

                                @if ($errors->has('gender'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('gender') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        @include('form.partials._input', [
                            'label' => 'Date of birth',
                            'field' => 'date_of_birth',
                            'type' => 'text',
                            'errors' => $errors
                        ])

                        @include('form.partials._input', [
                            'label' => 'Address line 1',
                            'field' => 'address_1',
                            'type' => 'text',
                            'attributes' => 'required',
                            'errors' => $errors
                        ])

                        @include('form.partials._input', [
                            'label' => 'Address line 2',
                            'field' => 'address_2',
                            'type' => 'text',
                            'errors' => $errors
                        ])


                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right" for="country_id">Country</label>
                            
                            <div class="col-md-6">
                                <country-select 
                                    class="form-control"
                                    resource="{{ json_encode($countries) }}"
                                    old="{{ old('country_id') }}"
                                ></country-select>

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
                                <county-select 
                                    class="form-control"
                                    resource="{{ json_encode($counties) }}"
                                    country-id="{{ old('country_id') }}"
                                    old="{{ old('county_id') }}"
                                ></county-select>

                                @if ($errors->has('county_id'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('county_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right" for="nationality_id">Nationality</label>
                            
                            <div class="col-md-6">
                                <select class="form-control" name="nationality_id" id="nationality_id" required>
                                   @foreach($nationalities as $nationality)
                                        <option value="{{ $nationality->id }}">
                                            {{ ucfirst($nationality->name) }}
                                        </option>
                                   @endforeach
                                </select>

                                @if ($errors->has('nationality_id'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('nationality_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        @include('form.partials._input', [
                            'label' => 'Post code',
                            'field' => 'postcode',
                            'type' => 'text',
                            'attributes' => 'required',
                            'errors' => $errors
                        ])

                        @include('form.partials._input', [
                            'label' => 'Telephone',
                            'field' => 'telephone',
                            'type' => 'text',
                            'attributes' => 'required',
                            'errors' => $errors
                        ])

                        @include('form.partials._input', [
                            'label' => 'Twitter handle',
                            'field' => 'twitter_handle',
                            'type' => 'text',
                            'errors' => $errors
                        ])

                        @include('form.partials._input', [
                            'label' => 'Email Address',
                            'field' => 'email',
                            'type' => 'email',
                            'attributes' => 'required',
                            'errors' => $errors
                        ])

                        @include('form.partials._input', [
                            'label' => 'Password',
                            'field' => 'password',
                            'type' => 'password',
                            'attributes' => 'required',
                            'errors' => $errors
                        ])

                        @include('form.partials._input', [
                            'label' => 'Confirm password',
                            'field' => 'password_confirmation',
                            'type' => 'password',
                            'attributes' => 'required',
                            'errors' => $errors
                        ])

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
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
