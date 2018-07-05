<?php

namespace App\Http\Requests\RacingExcellence;

use Illuminate\Foundation\Http\FormRequest;

class RacingExcellenceResultsFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'place' => function($attribute, $value, $fail) {

                if (!is_numeric($value) && $value !== 'dnf' ) {
                    return $fail('Please select a registered Coach');
                }
            },
            'presentation_points' => 'nullable|numeric|between:0,2',
            'professionalism_points' => 'nullable|numeric|between:0,2',
            'coursewalk_points' => 'nullable|numeric|between:0,2',
            'riding_points' => 'nullable|numeric|between:0,2',
        ];
    }
}
