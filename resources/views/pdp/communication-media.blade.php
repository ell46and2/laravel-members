@extends('layouts.base')

@section('content')

@component('pdp.components._pdp', compact('pdp', 'jockey'))

<div class="panel">

    @if(!$hideInput)
	<form method="POST" action="{{ route('pdp.communication-media.store', $pdp) }}">
        @csrf
    @endif

        <div class="panel__inner">

          <div class="panel__header">
              <h2 class="panel__heading">
                  Communication and Media
                  <div class="text--color-base text--size-base">Enter your communication and media information</div>
              </h2>
          </div>

          <div class="panel__main">
            <dl class="definition-list definition-list--stacked">

                <range-slider-pdp
                  label="Interview Skills"
                  name="interview_skills"
                  :old-value="{{ $pdp->interview_skills ?? 0 }}"
                  is-disabled="{{ $hideInput }}"
                ></range-slider-pdp>
                
                @include('form.partials._textarea', [
                    'field' => 'interview_skills_comment',
                    'errors' => $errors,
                    'value' => $pdp->interview_skills_comment,
                    'placeholder' => 'Add Comment',
                    'disabled' => $hideInput
                ])

                <range-slider-pdp
                  label="Communication Skills"
                  name="communication_skills"
                  :old-value="{{ $pdp->communication_skills ?? 0 }}"
                  is-disabled="{{ $hideInput }}"
                ></range-slider-pdp>
                
                @include('form.partials._textarea', [
                    'field' => 'communication_skills_comment',
                    'errors' => $errors,
                    'value' => $pdp->communication_skills_comment,
                    'placeholder' => 'Add Comment',
                    'disabled' => $hideInput
                ])

                <range-slider-pdp
                  label="Social Media"
                  name="social_media"
                  :old-value="{{ $pdp->social_media ?? 0 }}"
                  is-disabled="{{ $hideInput }}"
                ></range-slider-pdp>
                
                @include('form.partials._textarea', [
                    'field' => 'social_media_comment',
                    'errors' => $errors,
                    'value' => $pdp->social_media_comment,
                    'placeholder' => 'Add Comment',
                    'disabled' => $hideInput
                ])

                @include('form.partials._textarea_with_checkbox', [
                    'textareaField' => 'media_training_comment',
                    'errors' => $errors,
                    'textareaValue' => $pdp->media_training_comment,
                    'textareaPlaceholder' => 'Add Comment',
                    'disabled' => $hideInput,
                    'checkLabel' => 'Media Training',
                    'checkName' => 'Completed',
                    'checkField' => 'media_training',
                    'checkValue' => $pdp->media_training,
                ])

                @include('form.partials._textarea_with_checkbox', [
                    'textareaField' => 'social_media_training_comment',
                    'errors' => $errors,
                    'textareaValue' => $pdp->social_media_training_comment,
                    'textareaPlaceholder' => 'Add Comment',
                    'disabled' => $hideInput,
                    'checkLabel' => 'Social Media Training',
                    'checkName' => 'Completed',
                    'checkField' => 'social_media_training',
                    'checkValue' => $pdp->social_media_training,
                ])

                @include('form.partials._textarea', [
                    'label' => 'Notes/Actions',
                    'field' => 'communication_notes',
                    'errors' => $errors,
                    'value' => $pdp->communication_notes,
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