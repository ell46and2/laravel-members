<?php

namespace App\Http\Requests\Coach;

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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'alias' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|min:10|date_format:"d/m/Y"',
            'gender' => 'required|in:male,female',
            'address_1' => 'required|string|max:255',
            'address_2' => 'nullable|string|max:255',
            'county_id' => 'required|exists:counties,id',
            'country_id' => 'required|exists:countries,id',
            'nationality_id' => 'nullable|exists:nationalities,id',
            'postcode' => 'required|string|max:255',
            'telephone' => 'required|string|max:255',
            'twitter_handle' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
        ];
    }
}
