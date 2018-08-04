@extends('layouts.base')

@section('content')

@component('pdp.components._pdp', compact('pdp', 'jockey'))

<div class="panel">
    
    @if(!$hideInput)
	<form method="POST" action="{{ route('pdp.nutrition.store', $pdp) }}">
        @csrf
    @endif

    <div class="panel__inner">

      <div class="panel__header">
          <h2 class="panel__heading">
              Nutrition
              <div class="text--color-base text--size-base">Enter your nutrition information</div>
          </h2>
      </div>

      <div class="panel__main">
        <dl class="definition-list definition-list--stacked">

            <range-slider-pdp
                label="Balanced Healthy Diet"
                name="balanced_diet"
                :old-value="{{ $pdp->balanced_diet ?? 0 }}"
                is-disabled="{{ $hideInput }}"
            ></range-slider-pdp>
          
            @include('form.partials._textarea', [
                'field' => 'balanced_diet_comment',
                'errors' => $errors,
                'value' => $pdp->balanced_diet_comment,
                'placeholder' => 'Add Comment',
                'disabled' => $hideInput
            ])

            <range-slider-pdp
              label="Following A Diet Plan"
              name="diet_plan"
              :old-value="{{ $pdp->diet_plan ?? 0 }}"
              is-disabled="{{ $hideInput }}"
            ></range-slider-pdp>
            
            @include('form.partials._textarea', [
                'field' => 'diet_plan_comment',
                'errors' => $errors,
                'value' => $pdp->diet_plan_comment,
                'placeholder' => 'Add Comment',
                'disabled' => $hideInput
            ])

            <range-slider-pdp
              label="Making Weight"
              name="making_weight"
              :old-value="{{ $pdp->making_weight ?? 0 }}"
              is-disabled="{{ $hideInput }}"
            ></range-slider-pdp>
            
            @include('form.partials._textarea', [
                'field' => 'making_weight_comment',
                'errors' => $errors,
                'value' => $pdp->making_weight_comment,
                'placeholder' => 'Add Comment',
                'disabled' => $hideInput
            ])

            <range-slider-pdp
              label="Food Label Reading"
              name="food_label"
              :old-value="{{ $pdp->food_label ?? 0 }}"
              is-disabled="{{ $hideInput }}"
            ></range-slider-pdp>

            @include('form.partials._textarea', [
                'field' => 'food_label_comment',
                'errors' => $errors,
                'value' => $pdp->food_label_comment,
                'placeholder' => 'Add Comment',
                'disabled' => $hideInput
            ])
            
            @include('form.partials._textarea_with_checkbox', [
                'textareaField' => 'food_diary_comment',
                'errors' => $errors,
                'textareaValue' => $pdp->food_diary_comment,
                'textareaPlaceholder' => 'Add Comment',
                'disabled' => $hideInput,
                'checkLabel' => 'Food Diary',
                'checkName' => 'Completed',
                'checkField' => 'food_diary',
                'checkValue' => $pdp->food_diary,
            ])

            
            @include('form.partials._textarea_with_checkbox', [
                'textareaField' => 'cooking_classes_comment',
                'errors' => $errors,
                'textareaValue' => $pdp->cooking_classes_comment,
                'textareaPlaceholder' => 'Add Comment',
                'disabled' => $hideInput,
                'checkLabel' => 'Cooking Classes',
                'checkName' => 'Completed',
                'checkField' => 'cooking_classes',
                'checkValue' => $pdp->cooking_classes,
            ])

            @include('form.partials._textarea_with_checkbox', [
                'textareaField' => 'one_to_one_comment',
                'errors' => $errors,
                'textareaValue' => $pdp->one_to_one_comment,
                'textareaPlaceholder' => 'Add Comment',
                'disabled' => $hideInput,
                'checkLabel' => '1 to 1',
                'checkName' => 'Completed',
                'checkField' => 'one_to_one',
                'checkValue' => $pdp->one_to_one,
            ])
            
            @include('form.partials._textarea_with_checkbox', [
                'textareaField' => 'alcohol_education_comment',
                'errors' => $errors,
                'textareaValue' => $pdp->alcohol_education_comment,
                'textareaPlaceholder' => 'Add Comment',
                'disabled' => $hideInput,
                'checkLabel' => 'Alcohol Education',
                'checkName' => 'Completed',
                'checkField' => 'alcohol_education',
                'checkValue' => $pdp->alcohol_education,
            ])

            @include('form.partials._textarea', [
                'label' => 'Notes/Actions',
                'field' => 'nutrition_notes',
                'errors' => $errors,
                'value' => $pdp->nutrition_notes,
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

    {{--     
        <range-slider-pdp
          label="Balanced Healthy Diet"
          name="balanced_diet"
          :old-value="{{ $pdp->balanced_diet ?? 0 }}"
          is-disabled="{{ $hideInput }}"
        ></range-slider-pdp>
       
        @include('form.partials._textarea', [
            'field' => 'balanced_diet_comment',
            'errors' => $errors,
            'value' => $pdp->balanced_diet_comment,
            'placeholder' => 'Add Comment',
            'disabled' => $hideInput
        ])

        <range-slider-pdp
          label="Following A Diet Plan"
          name="diet_plan"
          :old-value="{{ $pdp->diet_plan ?? 0 }}"
          is-disabled="{{ $hideInput }}"
        ></range-slider-pdp>
       
        @include('form.partials._textarea', [
            'field' => 'diet_plan_comment',
            'errors' => $errors,
            'value' => $pdp->diet_plan_comment,
            'placeholder' => 'Add Comment',
            'disabled' => $hideInput
        ])

        <range-slider-pdp
          label="Making Weight"
          name="making_weight"
          :old-value="{{ $pdp->making_weight ?? 0 }}"
          is-disabled="{{ $hideInput }}"
        ></range-slider-pdp>
       
        @include('form.partials._textarea', [
            'field' => 'making_weight_comment',
            'errors' => $errors,
            'value' => $pdp->making_weight_comment,
            'placeholder' => 'Add Comment',
            'disabled' => $hideInput
        ])

        <range-slider-pdp
          label="Food Label Reading"
          name="food_label"
          :old-value="{{ $pdp->food_label ?? 0 }}"
          is-disabled="{{ $hideInput }}"
        ></range-slider-pdp>
       
        @include('form.partials._textarea', [
            'field' => 'food_label_comment',
            'errors' => $errors,
            'value' => $pdp->food_label_comment,
            'placeholder' => 'Add Comment',
            'disabled' => $hideInput
        ])

        @include('form.partials._checkbox', [
            'label' => 'Food Diary',
            'name' => 'Completed',
            'field' => 'food_diary',
            'value' => $pdp->food_diary,
            'placeholder' => 'Enter...',
            'disabled' => $hideInput
        ])
       
        @include('form.partials._textarea', [
            'field' => 'food_diary_comment',
            'errors' => $errors,
            'value' => $pdp->food_diary_comment,
            'placeholder' => 'Add Comment',
            'disabled' => $hideInput
        ])

        @include('form.partials._checkbox', [
            'label' => 'Cooking Classes',
            'name' => 'Completed',
            'field' => 'cooking_classes',
            'value' => $pdp->cooking_classes,
            'placeholder' => 'Enter...',
            'disabled' => $hideInput
        ])
       
        @include('form.partials._textarea', [
            'field' => 'cooking_classes_comment',
            'errors' => $errors,
            'value' => $pdp->cooking_classes_comment,
            'placeholder' => 'Add Comment',
            'disabled' => $hideInput
        ])

        @include('form.partials._checkbox', [
            'label' => '1 to 1',
            'name' => 'Completed',
            'field' => 'one_to_one',
            'value' => $pdp->one_to_one,
            'placeholder' => 'Enter...',
            'disabled' => $hideInput
        ])
       
        @include('form.partials._textarea', [
            'field' => 'one_to_one_comment',
            'errors' => $errors,
            'value' => $pdp->one_to_one_comment,
            'placeholder' => 'Add Comment',
            'disabled' => $hideInput
        ])

        @include('form.partials._checkbox', [
            'label' => 'Alcohol Education',
            'name' => 'Completed',
            'field' => 'alcohol_education',
            'value' => $pdp->alcohol_education,
            'placeholder' => 'Enter...',
            'disabled' => $hideInput
        ])
       
        @include('form.partials._textarea', [
            'field' => 'alcohol_education_comment',
            'errors' => $errors,
            'value' => $pdp->alcohol_education_comment,
            'placeholder' => 'Add Comment',
            'disabled' => $hideInput
        ])

        @include('form.partials._textarea', [
            'label' => 'Notes/Actions',
            'field' => 'nutrition_notes',
            'errors' => $errors,
            'value' => $pdp->nutrition_notes,
            'placeholder' => 'Enter...',
            'disabled' => $hideInput
        ])

    @if(!$hideInput)
        <div class="form-group row mb-0">
            <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-primary">
                    Next Section
                </button>
            </div>
        </div>
    </form>
    @endif
</div> --}}