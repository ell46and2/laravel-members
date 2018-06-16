@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Change Password</div>

                <div class="card-body">
                    <form 
                        method="POST" 
                        action="{{ route('profile.password.update') }}"
                        enctype="multipart/form-data"
                    >
                        @csrf
                        @method('put')

                        @include('form.partials._input', [
                            'label' => 'Password',
                            'field' => 'old_password',
                            'type' => 'password',
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
                                    Change
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
