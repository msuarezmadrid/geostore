<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Rut implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if(strpos($value, '.') === false) {
            $separator = substr($value, strlen($value)-2, 1);
            if($separator == '-') {
                $split = explode('-', $value);
                if(count($split) == 2) {
                    if(!preg_match('/[^0-9]/', $split[0])
                     && !preg_match('/[^0-9Kk]/', $split[1])) {
                        return true;
                    }
                }
            } 
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.Rut');
    }
}
