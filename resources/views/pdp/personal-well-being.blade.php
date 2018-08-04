@extends('layouts.base')

@section('content')

@component('pdp.components._pdp', compact('pdp', 'jockey'))

<div class="panel">
    
    @if(!$hideInput)
	<form method="POST" action="{{ route('pdp.personal-well-being.store', $pdp) }}">
        @csrf
    @endif

        <div class="panel__inner">

          <div class="panel__header">
              <h2 class="panel__heading">
                  Personal – Well-being
                  <div class="text--color-base text--size-base">Enter your personal well-being information</div>
              </h2>
          </div>

          <div class="panel__main">
            <dl class="definition-list definition-list--stacked">

                @include('form.partials._textarea', [
                    'label' => 'Longterm Goals in Life',
                    'field' => 'longterm_goals',
                    'errors' => $errors,
                    'value' => $pdp->longterm_goals,
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