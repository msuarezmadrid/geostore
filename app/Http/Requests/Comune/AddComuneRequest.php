<?php

namespace App\Http\Requests\Comune;

use Illuminate\Foundation\Http\FormRequest;

class AddComuneRequest extends FormRequest
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
            'comune_detail'          =>  'required|string'
        ];
    }

    public function messages()
    {
        return [
            '.comune_detail.required'      => 'Es necesario indicar el nombre de la comuna',
            '.comune_detail.string'        => 'El nombre de la comuna debe ser un valor alfanumerico'
        ];
    }
}
