<?php

namespace App\Http\Requests\Cargues;

use App\Http\Requests\BaseFormRequest;

class StoreRequest extends BaseFormRequest
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
            'file' => [
                'required',
                'max:25971520',
                'mimes:xlsx',
                //'clamav',
            ],
            'type' => [
                'required',
            ]
        ];
    }
    public function messages()
    {
        return [
            'file.required' => 'El archivo es requerido',
            'file.max' => 'El archivo no puede exceder los 25M',
            'file.mimes' => 'El archivo debe ser formato excel con extención xlsx',
            'type.required' => 'El tipo de cargue es requerido',
        ];
    }
}
