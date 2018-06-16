<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class IncrementsOfHalf implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return fmod($value, 0.5) == 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Must be in increments of 0.5.';
    }
}
