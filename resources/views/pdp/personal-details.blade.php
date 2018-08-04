@extends('layouts.base')

@section('content')

@component('pdp.components._pdp', compact('pdp', 'jockey'))

<div class="panel">
    
    @if(!$hideInput)
    <form method="POST" action="{{ route('pdp.personal-details.store', $pdp) }}">
        @csrf
    @endif

        <div class="panel__inner">

            <div class="panel__header">
                <h2 class="panel__heading">
                    Personal Details
                    <div class="text--color-base text--size-base">Enter your personal information</div>
                </h2>
            </div>

            <div class="panel__main">
                <dl class="definition-list definition-list--stacked">
                    <dt>Name</dt>
                    <dd>{{ $jockey->full_name }}</dd>

                    <dt>D.O.B.</dt>
                    <dd>{{ $jockey->formattedDateOfBirth }}</dd>

                    <dt>Phone number</dt>
                    <dd>{{ $jockey->telephone }}</dd>

                  {{--   <dt>Mobile number</dt>
                    <dd>0123456789</dd> --}}

                    <dt>Address</dt>
                    <dd>
                        {!! $jockey->fullAddress !!}
                    </dd>

                    <dt>Email address</dt>
                    <dd>{{ $jockey->email }}</dd>

                    <dt>Twitter handle</dt>
                    <dd>{{ $jockey->FormattedTwitterHandle }}</dd>

                    @include('form.partials._input', [
                        'label' => 'Currently Studying',
                        'field' => 'currently_studying',
                        'type' => 'text',
                        'errors' => $errors,
                        'value' => $pdp->currently_studying,
                        'placeholder' => 'Enter Subject...',
                        'disabled' => $hideInput
                    ])

                    @include('form.partials._input', [
                        'label' => 'Education',
                        'field' => 'education',
                        'type' => 'text',
                        'errors' => $errors,
                        'value' => $pdp->education,
                        'placeholder' => 'Enter Education...',
                        'disabled' => $hideInput
                    ])

                    @include('form.partials._input', [
                        'label' => 'Path Into Racing',
                        'field' => 'path_into_racing',
                        'type' => 'text',
                        'errors' => $errors,
                        'value' => $pdp->path_into_racing,
                        'placeholder' => 'Enter Path...',
                        'disabled' => $hideInput
                    ])

                    @include('form.partials._input', [
                        'label' => 'Length Of Time In Racing',
                        'field' => 'time_in_racing',
                        'type' => 'text',
                        'errors' => $errors,
                        'value' => $pdp->time_in_racing,
                        'placeholder' => 'Enter Time...',
                        'disabled' => $hideInput
                    ])
                </dl>
            </div>
        </div>

    @if(!$hideInput)
        <button  type="submit" class="panel__call-to-action button--block">Save & Next</button>
    </form>
    @endif

</div>

@endcomponent

@endsection