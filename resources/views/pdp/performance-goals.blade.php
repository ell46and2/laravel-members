@extends('layouts.base')

@section('content')

@component('pdp.components._pdp', compact('pdp', 'jockey'))

<div class="panel">

	<form method="POST" action="{{ route('pdp.performance-goals.store', $pdp) }}">
        @csrf

        <div class="panel__inner">

            <div class="panel__header">
                <h2 class="panel__heading">
                    Performance Goals
                    <div class="text--color-base text--size-base">Enter your performance goals</div>
                </h2>
            </div>

            <div class="panel__main">
                <dl class="definition-list definition-list--stacked">
              
                    <dt>
                        <label class="text--color-blue">Next 3 Months</label>
                    </dt>
                    <dd>
                    @include('form.partials._input', [
                        'field' => 'three_months_1',
                        'type' => 'text',
                        'errors' => $errors,
                        'value' => $pdp->three_months_1,
                        'disabled' => $hideInput
                    ])

                    @include('form.partials._checkbox', [
                        'name' => 'Achieved',
                        'field' => 'three_months_1_achieved',
                        'value' => $pdp->three_months_1_achieved,
                    ])

                    @include('form.partials._input', [
                        'field' => 'three_months_2',
                        'type' => 'text',
                        'errors' => $errors,
                        'value' => $pdp->three_months_2,
                        'disabled' => $hideInput
                    ])

                    @include('form.partials._checkbox', [
                        'name' => 'Achieved',
                        'field' => 'three_months_2_achieved',
                        'value' => $pdp->three_months_2_achieved,
                    ])

                    @include('form.partials._input', [
                        'field' => 'three_months_3',
                        'type' => 'text',
                        'errors' => $errors,
                        'value' => $pdp->three_months_3,
                        'disabled' => $hideInput
                    ])

                    @include('form.partials._checkbox', [
                        'name' => 'Achieved',
                        'field' => 'three_months_3_achieved',
                        'value' => $pdp->three_months_3_achieved,
                    ])
                    </dd>
                    <br>

                    <dt>
                        <label class="text--color-blue">Next 6 Months</label>
                    </dt>
                    <dd>
                    @include('form.partials._input', [
                        'field' => 'six_months_1',
                        'type' => 'text',
                        'errors' => $errors,
                        'value' => $pdp->six_months_1,
                        'disabled' => $hideInput
                    ])

                    @include('form.partials._checkbox', [
                        'name' => 'Achieved',
                        'field' => 'six_months_1_achieved',
                        'value' => $pdp->six_months_1_achieved,
                    ])

                    @include('form.partials._input', [
                        'field' => 'six_months_2',
                        'type' => 'text',
                        'errors' => $errors,
                        'value' => $pdp->six_months_2,
                        'disabled' => $hideInput
                    ])

                    @include('form.partials._checkbox', [
                        'name' => 'Achieved',
                        'field' => 'six_months_2_achieved',
                        'value' => $pdp->six_months_2_achieved,
                    ])

                    @include('form.partials._input', [
                        'field' => 'six_months_3',
                        'type' => 'text',
                        'errors' => $errors,
                        'value' => $pdp->six_months_3,
                        'disabled' => $hideInput
                    ])

                    @include('form.partials._checkbox', [
                        'name' => 'Achieved',
                        'field' => 'six_months_3_achieved',
                        'value' => $pdp->six_months_3_achieved,
                    ])
                    </dd>
                    <br>
                    
                    <dt>
                        <label class="text--color-blue">Next 12 Months</label>
                    </dt>
                    <dd>
                    @include('form.partials._input', [
                        'field' => 'twelve_months_1',
                        'type' => 'text',
                        'errors' => $errors,
                        'value' => $pdp->twelve_months_1,
                        'disabled' => $hideInput
                    ])

                    @include('form.partials._checkbox', [
                        'name' => 'Achieved',
                        'field' => 'twelve_months_1_achieved',
                        'value' => $pdp->twelve_months_1_achieved,
                    ])

                    @include('form.partials._input', [
                        'field' => 'twelve_months_2',
                        'type' => 'text',
                        'errors' => $errors,
                        'value' => $pdp->twelve_months_2,
                        'disabled' => $hideInput
                    ])

                    @include('form.partials._checkbox', [
                        'name' => 'Achieved',
                        'field' => 'twelve_months_2_achieved',
                        'value' => $pdp->twelve_months_2_achieved,
                    ])

                    @include('form.partials._input', [
                        'field' => 'twelve_months_3',
                        'type' => 'text',
                        'errors' => $errors,
                        'value' => $pdp->twelve_months_3,
                        'disabled' => $hideInput
                    ])

                    @include('form.partials._checkbox', [
                        'name' => 'Achieved',
                        'field' => 'twelve_months_3_achieved',
                        'value' => $pdp->twelve_months_3_achieved,
                    ])
                    </dd>
                    <br>
                    
                    <dt>
                        <label class="text--color-blue">Next 2 Years</label>
                    </dt>
                    <dd>
                    @include('form.partials._input', [
                        'field' => 'two_years_1',
                        'type' => 'text',
                        'errors' => $errors,
                        'value' => $pdp->two_years_1,
                        'disabled' => $hideInput
                    ])

                    @include('form.partials._checkbox', [
                        'name' => 'Achieved',
                        'field' => 'two_years_1_achieved',
                        'value' => $pdp->two_years_1_achieved,
                    ])

                    @include('form.partials._input', [
                        'field' => 'two_years_2',
                        'type' => 'text',
                        'errors' => $errors,
                        'value' => $pdp->two_years_2,
                        'disabled' => $hideInput
                    ])

                    @include('form.partials._checkbox', [
                        'name' => 'Achieved',
                        'field' => 'two_years_2_achieved',
                        'value' => $pdp->two_years_2_achieved,
                    ])

                    @include('form.partials._input', [
                        'field' => 'two_years_3',
                        'type' => 'text',
                        'errors' => $errors,
                        'value' => $pdp->two_years_3,
                        'disabled' => $hideInput
                    ])

                    @include('form.partials._checkbox', [
                        'name' => 'Achieved',
                        'field' => 'two_years_3_achieved',
                        'value' => $pdp->two_years_3_achieved,
                    ])
                    </dd>
                    <br>
                           
                </dl>
            </div>
        </div>
              
        
          <button  type="submit" class="panel__call-to-action button--block">Save & Next</button>
        </form>
       

    </div>

@endcomponent

@endsection