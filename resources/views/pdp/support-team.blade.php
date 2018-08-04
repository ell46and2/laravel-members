@extends('layouts.base')

@section('content')

@component('pdp.components._pdp', compact('pdp', 'jockey'))

<div class="panel">

    @if(!$hideInput)
	<form method="POST" action="{{ route('pdp.support-team.store', $pdp) }}">
        @csrf
    @endif

        <div class="panel__inner">

            <div class="panel__header">
                <h2 class="panel__heading">
                    Support Team
                    <div class="text--color-base text--size-base">Enter your support team information</div>
                </h2>
            </div>

            <div class="panel__main">
                <dl class="definition-list definition-list--stacked">
              
                    @include('form.partials._input', [
                        'label' => 'Family',
                        'field' => 'family',
                        'type' => 'text',
                        'errors' => $errors,
                        'value' => $pdp->family,
                        'disabled' => $hideInput
                    ])

                    @include('form.partials._input', [
                        'label' => 'Friends',
                        'field' => 'friends',
                        'type' => 'text',
                        'errors' => $errors,
                        'value' => $pdp->friends,
                        'disabled' => $hideInput
                    ])

                    @include('form.partials._input', [
                        'label' => 'Partner',
                        'field' => 'partner',
                        'type' => 'text',
                        'errors' => $errors,
                        'value' => $pdp->partner,
                        'disabled' => $hideInput
                    ])

                    @include('form.partials._input', [
                        'label' => 'Employer',
                        'field' => 'employer',
                        'type' => 'text',
                        'errors' => $errors,
                        'value' => $pdp->employer,
                        'disabled' => $hideInput
                    ])

                    @include('form.partials._input', [
                        'label' => 'Jockey Coach',
                        'field' => 'jockey_coach',
                        'type' => 'text',
                        'errors' => $errors,
                        'value' => $pdp->jockey_coach,
                        'disabled' => $hideInput
                    ])

                    @include('form.partials._input', [
                        'label' => 'Agent',
                        'field' => 'agent',
                        'type' => 'text',
                        'errors' => $errors,
                        'value' => $pdp->agent,
                        'disabled' => $hideInput
                    ])

                    @include('form.partials._input', [
                        'label' => 'Physio',
                        'field' => 'physio',
                        'type' => 'text',
                        'errors' => $errors,
                        'value' => $pdp->physio,
                        'disabled' => $hideInput
                    ])

                    @include('form.partials._input', [
                        'label' => 'S&C coach/Fitness trainer',
                        'field' => 'coach_fitness_trainer',
                        'type' => 'text',
                        'errors' => $errors,
                        'value' => $pdp->coach_fitness_trainer,
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