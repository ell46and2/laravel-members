<?php

namespace App\Http\Requests\SkillProfile;

use App\Rules\IncrementsOfHalf;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePostFormRequest extends FormRequest
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
            'start_date' => 'required|date_format:"d/m/Y"',
            'start_time' => 'required|date_format:H:i',
            'riding_rating' => [
                'required',
                'numeric',
                'between:0,5',
                'bail',
                new IncrementsOfHalf
            ],
            'fitness_rating' => [
                'required',
                'numeric',
                'between:0,5',
                'bail',
                new IncrementsOfHalf
            ],
            'simulator_rating' => [
                'required',
                'numeric',
                'between:0,5',
                'bail',
                new IncrementsOfHalf
            ],
            'race_riding_skills_rating' => [
                'required',
                'numeric',
                'between:0,5',
                'bail',
                new IncrementsOfHalf
            ],
            'weight_rating' => [
                'required',
                'numeric',
                'between:0,5',
                'bail',
                new IncrementsOfHalf
            ],
            'communication_rating' => [
                'required',
                'numeric',
                'between:0,5',
                'bail',
                new IncrementsOfHalf
            ],
            'whip_rating' => [
                'required',
                'numeric',
                'between:0,5',
                'bail',
                new IncrementsOfHalf
            ],
            'professionalism_rating' => [
                'required',
                'numeric',
                'between:0,5',
                'bail',
                new IncrementsOfHalf
            ],
        ];
    }
}
