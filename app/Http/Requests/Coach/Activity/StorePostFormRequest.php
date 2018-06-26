<?php

namespace App\Http\Requests\Coach\Activity;

use App\Models\Jockey;
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
            'activity_type_id' => 'required|exists:activity_types,id',
            'start_date' => 'required|date_format:"d/m/Y"',
            'start_time' => 'required|date_format:H:i',
            'duration' => 'nullable|integer',
            'jockeys' => [
                'required',
                function($attribute, $value, $fail) {
                    $ids = array_keys($value);

                    if (Jockey::find($ids)->count() !== count($ids)) {
                        return $fail('Please select Jockeys on the system');
                    }
                }
            ]
        ];
    }
}
