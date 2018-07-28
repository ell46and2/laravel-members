<?php

namespace App\Http\Requests\Coach;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCoachFormRequest extends FormRequest
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
        $coachId = $this->coach ? $this->coach->id : auth()->user()->id;

        return [
            'middle_name' => 'nullable|string|max:255',
            'alias' => 'nullable|string|max:255',
            'address_1' => 'required|string|max:255',
            'address_2' => 'nullable|string|max:255',
            'county_id' => 'required|exists:counties,id',
            'country_id' => 'required|exists:countries,id',
            'postcode' => 'required|string|max:255',
            'telephone' => 'required|string|max:255',
            'twitter_handle' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $coachId,
            'vat_number' => 'nullable',
            'bio' => 'nullable',
        ];
    }
}
