<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordFormRequest extends FormRequest
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
            'old_password' =>[
                'required',
                function($attribute, $value, $fail) {
                    $user = auth()->user();
                    if(!Hash::check($value, $user->password)) {
                        return $fail('Current password entered is incorrect');
                    }
                }
            ],
            'password' => 'required|confirmed|min:6',
        ];
    }
}
