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
    
    <form method="POST" action="{{ route('jets.crm.jockey-store') }}">
        @csrf


        <div class="panel__inner">

            {{-- <div class="panel__header">
                <h2 class="panel__heading">
                    Personal Details
                    <div class="text--color-base text--size-base">Enter your personal information</div>
                </h2>
            </div> --}}

            <div class="panel__main">
                <dl class="definition-list definition-list--stacked">



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

                    <dt>
                        <label class="text--color-blue" for="gender">Gender</label>
                    </dt>
                    <dd> 
                        <select class="form-control custom-select" name="gender" id="gender" required>
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
                    </dd>

                    <dt>
                        <label class="text--color-blue">Date Of Birth</label>
                    </dt>
                    <dd>
                        <datepicker-component name="date_of_birth" placeholder="Select Date" old="{{ old('date_of_birth') }}"></datepicker-component>

                        @if ($errors->has('date_of_birth'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('date_of_birth') }}</strong>
                            </span>
                        @endif
                    </dd>

                    <dt>
                        <label class="text--color-blue" for="country_id">Country</label>
                    </dt>    
                    <dd>
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
                    </dd>

                    <dt>
                        <label class="text--color-blue" for="county_id">County</label>
                    </dt>
                    <dd>
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
                    </dd>   

                    <dt>
                        <label class="text--color-blue" for="nationality_id">Nationality</label>
                    </dt>
                    <dd>           
                        <select class="form-control custom-select" name="nationality_id" id="nationality_id" required>
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
                    </dd>

                    @include('form.partials._input', [
                        'label' => 'Post code',
                        'field' => 'postcode',
                        'type' => 'text',
                        'attributes' => 'required',
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
                        'label' => 'Racing Post API ID',
                        'field' => 'api_id',
                        'type' => 'api_id',
                        'attributes' => 'required',
                        'errors' => $errors
                    ])

                </dl>
            </div>
        </div>
        <button type="submit" class="panel__call-to-action button--block">Create</button>
    </form>  
</div>
@endsection
