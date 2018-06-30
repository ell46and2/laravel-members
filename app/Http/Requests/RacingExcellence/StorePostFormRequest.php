<?php

namespace App\Http\Requests\RacingExcellence;

use App\Models\Coach;
use Illuminate\Foundation\Http\FormRequest;

class StorePostFormRequest extends FormRequest
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
            'coach_id' => [
                'required',
                function($attribute, $value, $fail) {

                    if (!Coach::find($value)) {
                        return $fail('Please select a Coach that is on the system');
                    }
                }
            ],
            'location_id' => 'required|exists:racing_locations,id',
            'series_id' => 'required|exists:series_types,id',
            'start_date' => 'required|date_format:"d/m/Y"',
            'start_time' => 'required|date_format:H:i',
            'divisions' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'coach_id.required' => 'Please select a coach.',
            'location_id.required' => 'Please select a location.',
            'series_id.required' => 'Please select a series.',
            'start_date.required' => 'Please select a date.',
            'start_date.date_format' => 'Please input the date in the format dd/mm/yyyy.',
            'start_time.required' => 'Please select a start time.',
            'start_time.date_format' => 'Please input the start time in the format HH:mm.',
            'divisions.required' => 'Please choose some jockeys to be in the race.'
        ];
    }
}
