@extends('layouts.base')

@section('content')

@component('pdp.components._pdp', compact('pdp', 'jockey'))

<div class="panel">

	<form method="POST" action="{{ route('pdp.actions.store', $pdp) }}">
        @csrf

        <div class="panel__inner">

            <div class="panel__header">
                <h2 class="panel__heading">
                    Actions
                    <div class="text--color-base text--size-base">Enter your actions</div>
                </h2>
            </div>

            <div class="panel__main">
                <dl class="definition-list definition-list--stacked">
              
                    @include('form.partials._textarea', [
                        'label' => 'JETS manager actions',
                        'field' => 'jets_actions',
                        'errors' => $errors,
                        'value' => $pdp->jets_actions,
                        'disabled' => $hideInput
                    ])
                    @include('form.partials._checkbox', [
                        'name' => 'Completed',
                        'field' => 'jets_actions_completed',
                        'value' => $pdp->jets_actions_completed,
                    ])

                    @include('form.partials._textarea', [
                        'label' => 'Support team actions',
                        'field' => 'support_team_actions',
                        'errors' => $errors,
                        'value' => $pdp->support_team_actions,
                        'disabled' => $hideInput
                    ])
                    @include('form.partials._checkbox', [
                        'name' => 'Completed',
                        'field' => 'support_team_actions_completed',
                        'value' => $pdp->support_team_actions_completed,
                    ])
                           
                </dl>
            </div>
        </div>
              
        
          <button  type="submit" class="panel__call-to-action button--block">Save & Next</button>
        </form>
        

    </div>

@endcomponent

@endsection