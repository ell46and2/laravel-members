@extends('layouts.base')

@section('content')

@component('pdp.components._pdp', compact('pdp', 'jockey'))

<div class="panel">

  @if(!$hideInput)
	<form method="POST" action="{{ route('pdp.mental-well-being', $pdp) }}">
    @csrf
  @endif

    <div class="panel__inner">

      <div class="panel__header">
          <h2 class="panel__heading">
              Mental Well-being
              <div class="text--color-base text--size-base">Enter your mental well-being information</div>
          </h2>
      </div>

      <div class="panel__main">
        <dl class="definition-list definition-list--stacked">
          
          <range-slider-pdp
            label="Concentration"
            name="concentration"
            :old-value="{{ $pdp->concentration ?? 0 }}"
            is-disabled="{{ $hideInput }}"
          ></range-slider-pdp>
          
          @include('form.partials._textarea', [
              'field' => 'concentration_comment',
              'errors' => $errors,
              'value' => $pdp->concentration_comment,
              'placeholder' => 'Add Comment',
              'disabled' => $hideInput
          ])

          <range-slider-pdp
            label="Relaxation"
            name="relaxation"
            :old-value="{{ $pdp->relaxation ?? 0 }}"
            is-disabled="{{ $hideInput }}"
          ></range-slider-pdp>
          
          @include('form.partials._textarea', [
              'field' => 'relaxation_comment',
              'errors' => $errors,
              'value' => $pdp->relaxation_comment,
              'placeholder' => 'Add Comment',
              'disabled' => $hideInput
          ])

          <range-slider-pdp
            label="Social"
            name="social"
            :old-value="{{ $pdp->social ?? 0 }}"
            is-disabled="{{ $hideInput }}"
          ></range-slider-pdp>
          
          @include('form.partials._textarea', [
              'field' => 'social_comment',
              'errors' => $errors,
              'value' => $pdp->social_comment,
              'placeholder' => 'Add Comment',
              'disabled' => $hideInput
          ])

          <range-slider-pdp
            label="Community"
            name="community"
            :old-value="{{ $pdp->community ?? 0 }}"
            is-disabled="{{ $hideInput }}"
          ></range-slider-pdp>
          
          @include('form.partials._textarea', [
              'field' => 'community_comment',
              'errors' => $errors,
              'value' => $pdp->community_comment,
              'placeholder' => 'Add Comment',
              'disabled' => $hideInput
          ])

          @include('form.partials._textarea', [
              'label' => 'Notes/Actions',
              'field' => 'mental_notes',
              'errors' => $errors,
              'value' => $pdp->mental_notes,
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