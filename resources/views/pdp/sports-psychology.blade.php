@extends('layouts.base')

@section('content')

@component('pdp.components._pdp', compact('pdp', 'jockey'))

<div class="panel">

  @if(!$hideInput)
	<form method="POST" action="{{ route('pdp.sports-psychology.store', $pdp) }}">
    @csrf
  @endif

    <div class="panel__inner">

      <div class="panel__header">
          <h2 class="panel__heading">
              Sports Psychology
              <div class="text--color-base text--size-base">Enter your sports psychology information</div>
          </h2>
      </div>

      <div class="panel__main">
        <dl class="definition-list definition-list--stacked">
          
          <range-slider-pdp
            label="Confidence"
            name="psychology_confidence"
            :old-value="{{ $pdp->psychology_confidence ?? 0 }}"
            is-disabled="{{ $hideInput }}"
          ></range-slider-pdp>
          
          @include('form.partials._textarea', [
              'field' => 'psychology_confidence_comment',
              'errors' => $errors,
              'value' => $pdp->psychology_confidence_comment,
              'placeholder' => 'Add Comment',
              'disabled' => $hideInput
          ])

          <range-slider-pdp
            label="Positive Attitude"
            name="positive_attitude"
            :old-value="{{ $pdp->positive_attitude ?? 0 }}"
            is-disabled="{{ $hideInput }}"
          ></range-slider-pdp>
          
          @include('form.partials._textarea', [
              'field' => 'positive_attitude_comment',
              'errors' => $errors,
              'value' => $pdp->positive_attitude_comment,
              'placeholder' => 'Add Comment',
              'disabled' => $hideInput
          ])

          <range-slider-pdp
            label="Goal Setting"
            name="goal_setting"
            :old-value="{{ $pdp->goal_setting ?? 0 }}"
            is-disabled="{{ $hideInput }}"
          ></range-slider-pdp>
          
          @include('form.partials._textarea', [
              'field' => 'goal_setting_comment',
              'errors' => $errors,
              'value' => $pdp->goal_setting_comment,
              'placeholder' => 'Add Comment',
              'disabled' => $hideInput
          ])

          <range-slider-pdp
            label="Resilience"
            name="resilience"
            :old-value="{{ $pdp->resilience ?? 0 }}"
            is-disabled="{{ $hideInput }}"
          ></range-slider-pdp>
          
          @include('form.partials._textarea', [
              'field' => 'resilience_comment',
              'errors' => $errors,
              'value' => $pdp->resilience_comment,
              'placeholder' => 'Add Comment',
              'disabled' => $hideInput
          ])

          @include('form.partials._textarea', [
              'label' => 'Notes/Actions',
              'field' => 'sports_psychology_notes',
              'errors' => $errors,
              'value' => $pdp->sports_psychology_notes,
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