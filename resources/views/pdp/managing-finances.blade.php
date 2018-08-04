@extends('layouts.base')

@section('content')

@component('pdp.components._pdp', compact('pdp', 'jockey'))

<div class="panel">

  @if(!$hideInput)
	<form method="POST" action="{{ route('pdp.managing-finance.store', $pdp) }}">
    @csrf
  @endif

    <div class="panel__inner">

      <div class="panel__header">
          <h2 class="panel__heading">
              Managing Finances
              <div class="text--color-base text--size-base">Enter your managing finances information</div>
          </h2>
      </div>

      <div class="panel__main">
        <dl class="definition-list definition-list--stacked">
          
          <range-slider-pdp
            label="Financial"
            name="financial"
            :old-value="{{ $pdp->financial ?? 0 }}"
            is-disabled="{{ $hideInput }}"
          ></range-slider-pdp>
          
          @include('form.partials._textarea', [
              'field' => 'financial_comment',
              'errors' => $errors,
              'value' => $pdp->financial_comment,
              'placeholder' => 'Add Comment',
              'disabled' => $hideInput
          ])

          <range-slider-pdp
            label="Budgeting"
            name="budgeting"
            :old-value="{{ $pdp->budgeting ?? 0 }}"
            is-disabled="{{ $hideInput }}"
          ></range-slider-pdp>
          
          @include('form.partials._textarea', [
              'field' => 'budgeting_comment',
              'errors' => $errors,
              'value' => $pdp->budgeting_comment,
              'placeholder' => 'Add Comment',
              'disabled' => $hideInput
          ])

          <range-slider-pdp
            label="Sponsorship"
            name="sponsorship"
            :old-value="{{ $pdp->sponsorship ?? 0 }}"
            is-disabled="{{ $hideInput }}"
          ></range-slider-pdp>
          
          @include('form.partials._textarea', [
              'field' => 'sponsorship_comment',
              'errors' => $errors,
              'value' => $pdp->sponsorship_comment,
              'placeholder' => 'Add Comment',
              'disabled' => $hideInput
          ])

          <range-slider-pdp
            label="Accountant and Tax"
            name="accountant_tax"
            :old-value="{{ $pdp->accountant_tax ?? 0 }}"
            is-disabled="{{ $hideInput }}"
          ></range-slider-pdp>
          
          @include('form.partials._textarea', [
              'field' => 'accountant_tax_comment',
              'errors' => $errors,
              'value' => $pdp->accountant_tax_comment,
              'placeholder' => 'Add Comment',
              'disabled' => $hideInput
          ])

          <range-slider-pdp
            label="Savings"
            name="savings"
            :old-value="{{ $pdp->savings ?? 0 }}"
            is-disabled="{{ $hideInput }}"
          ></range-slider-pdp>
          
          @include('form.partials._textarea', [
              'field' => 'savings_comment',
              'errors' => $errors,
              'value' => $pdp->savings_comment,
              'placeholder' => 'Add Comment',
              'disabled' => $hideInput
          ])

          <dt>
            <label class="text--color-blue" for="pja_saving_plan">PJA Saving Plan</label>  
          </dt>
          <dd>
            <select
              @if($hideInput)
                disabled
              @endif
              class="form-control custom-select" 
              name="pja_saving_plan" 
              id="pja_saving_plan"
            >
                <option value="" disabled {{ !old('pja_pension', $pdp->pja_saving_plan) ? 'selected' : '' }}>Select...</option>
               @foreach(["I have one","I’m in the process of organising one","I don’t have one"] as $enum)
                    <option value="{{ $enum }}" {{ old('pja_saving_plan', $pdp->pja_saving_plan) === $enum ? 'selected' : '' }}>
                        {{ $enum }}
                    </option>
               @endforeach
            </select>

            @if ($errors->has('pja_saving_plan'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('pja_saving_plan') }}</strong>
                </span>
            @endif
          </dd>
            
          <dt>
            <label class="text--color-blue" for="pja_pension">PJA Pension</label>    
          </dt>
          <dd>
            <select
              @if($hideInput)
                disabled
              @endif 
              class="form-control custom-select" 
              name="pja_pension" 
              id="pja_pension"
            >
                <option value="" disabled {{ !old('pja_pension', $pdp->pja_pension) ? 'selected' : '' }}>Select...</option>
                @foreach(["I have one","I’m in the process of organising one","I don’t have one"] as $enum)
                    <option value="{{ $enum }}" {{ old('pja_pension', $pdp->pja_pension) === $enum ? 'selected' : '' }}>
                        {{ $enum }}
                    </option>
                @endforeach
            </select>


            @if ($errors->has('pja_pension'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('pja_pension') }}</strong>
                </span>
            @endif
          </dd>

          @include('form.partials._textarea', [
              'label' => 'Notes/Actions',
              'field' => 'finances_notes',
              'errors' => $errors,
              'value' => $pdp->finances_notes,
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