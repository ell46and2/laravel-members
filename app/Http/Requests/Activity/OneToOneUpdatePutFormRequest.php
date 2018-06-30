<?php

namespace App\Http\Requests\Activity;

use Illuminate\Foundation\Http\FormRequest;

class OneToOneUpdatePutFormRequest extends FormRequest
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
            'duration' => 'nullable|integer'
        ];
    }
}
