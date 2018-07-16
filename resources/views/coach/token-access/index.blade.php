@extends('layouts.app')

@section('content')


<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Coach Set Password</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('coach.token-access.update') }}">
                        @csrf

                        <input type="hidden" name="email" value="{{ $email }}" />
                        <input type="hidden" name="token" value="{{ $token }}" />

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
                                    Set Password
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