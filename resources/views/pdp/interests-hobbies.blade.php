@extends('layouts.base')

@section('content')

@component('pdp.components._pdp', compact('pdp', 'jockey'))

<div class="panel">

    @if(!$hideInput)
	<form method="POST" action="{{ route('pdp.interests-hobbies.store', $pdp) }}">
        @csrf
    @endif

        <div class="panel__inner">

            <div class="panel__header">
                <h2 class="panel__heading">
                    Interests and Hobbies
                    <div class="text--color-base text--size-base">Enter your interests and hobbies information</div>
                </h2>
            </div>

            <div class="panel__main">
                <dl class="definition-list definition-list--stacked">
              
                    @include('form.partials._textarea', [
                        'label' => 'Interests and Hobbies',
                        'field' => 'interests_hobbies',
                        'errors' => $errors,
                        'value' => $pdp->interests_hobbies,
                        'placeholder' => 'Enter...',
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