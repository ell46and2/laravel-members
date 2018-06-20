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
            'presentation_points' => 'nullable|numeric|between:0,5',
            'professionalism_points' => 'nullable|numeric|between:0,5',
            'coursewalk_points' => 'nullable|numeric|between:0,5',
            'riding_points' => 'nullable|numeric|between:0,5',
        ];
    }
}
