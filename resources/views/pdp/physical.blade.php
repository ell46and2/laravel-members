@extends('layouts.base')

@section('content')

@component('pdp.components._pdp', compact('pdp', 'jockey'))

<div class="panel">

	<h4></h4>
    
    @if(!$hideInput)
	<form method="POST" action="{{ route('pdp.physical.store', $pdp) }}">
        @csrf
    @endif 

    <div class="panel__inner">

      <div class="panel__header">
          <h2 class="panel__heading">
              Physical - Strength and Conditioning
              <div class="text--color-base text--size-base">Enter your strength and conditioning information</div>
          </h2>
      </div>

      <div class="panel__main">
        <dl class="definition-list definition-list--stacked">

            @include('form.partials._checkbox', [
                'label' => 'Physical',
                'name' => 'Completed',
                'field' => 'physical',
                'value' => $pdp->physical,
                'placeholder' => 'Enter...',
                'disabled' => $hideInput
            ])
            
            @include('form.partials._textarea', [
                'field' => 'physical_comment',
                'errors' => $errors,
                'value' => $pdp->physical_comment,
                'placeholder' => 'Add Comment',
                'disabled' => $hideInput
            ])

            @include('form.partials._textarea', [
                'label' => 'Notes/Actions',
                'field' => 'physical_notes',
                'errors' => $errors,
                'value' => $pdp->physical_notes,
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