<?php

namespace App\Http\Requests\AppPaymentType;

use Illuminate\Foundation\Http\FormRequest;

class AddAppPaymentTypeRequest extends FormRequest
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
            'name'          =>  'required|string'
        ];
    }

    public function messages()
    {
        return [
            '.name.required'      => 'Es necesario indicar el nombre de la aplicación',
            '.name.string'        => 'El nombre de la aplicación debe ser un valor alfanumerico'
        ];
    }
}
