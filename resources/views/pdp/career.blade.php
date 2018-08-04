@extends('layouts.base')

@section('content')

@component('pdp.components._pdp', compact('pdp', 'jockey'))

<div class="panel">
 
  @if(!$hideInput)
	<form method="POST" action="{{ route('pdp.career.store', $pdp) }}">
        @csrf
  @endif

    <div class="panel__inner">

      <div class="panel__header">
          <h2 class="panel__heading">
              Careers
              <div class="text--color-base text--size-base">Enter your career information</div>
          </h2>
      </div>

      <div class="panel__main">
        <dl class="definition-list definition-list--stacked">

          <dt>
              <label class="text--color-blue">Immediate priorities</label>

              @include('form.partials._checkbox', [
                'field' => 'weight',
                'value' => $pdp->weight,
                'name' => 'Weight',
                'disabled' => $hideInput
              ])

              @include('form.partials._checkbox', [
                'field' => 'confidence',
                'value' => $pdp->confidence,
                'name' => 'Confidence',
                'disabled' => $hideInput
              ])

              @include('form.partials._checkbox', [
                'field' => 'strength_fitness',
                'value' => $pdp->strength_fitness,
                'name' => 'Strength Fitness',
                'disabled' => $hideInput
              ])

              @include('form.partials._checkbox', [
                'field' => 'racing_preparation',
                'value' => $pdp->racing_preparation,
                'name' => 'Racing Preparation',
                'disabled' => $hideInput
              ])

              @include('form.partials._checkbox', [
                'field' => 'diet',
                'value' => $pdp->diet,
                'name' => 'Diet and Nutrition',
                'disabled' => $hideInput
              ])



          </dt>
          <dd>
              @include('form.partials._input', [
                  'label' => 'Other',
                  'field' => 'career_other',
                  'type' => 'text',
                  'errors' => $errors,
                  'value' => $pdp->career_other,
                  'disabled' => $hideInput
              ])
          </dd> 

          @include('form.partials._textarea', [
              'label' => 'Long Term Goal',
              'field' => 'long_term_goal',
              'errors' => $errors,
              'value' => $pdp->long_term_goal,
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