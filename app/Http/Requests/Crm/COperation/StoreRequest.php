<?php

namespace App\Http\Requests\Crm\COperation;

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
            'operation_name' => [
                "required" 
            ],
		'operation_description' => [
            "required" 
        ],
		'start_date' => [
            "required",
            "date" 
        ],
		'end_date' => [
            "required",
            "date" 
        ],
		'user_id' => [
            "required" ,
            "numeric"
        ],
		'c_operation_type_id' => [
            "required" ,
            "numeric"
        ]
        ];
    }

    public function messages()
    {
        return [

            "operation_name.required" => "El nombre de la operación es obligatorio.",
            "operation_description.required" => "La descripción de la operación es obligatoria.",
            "start_date.required" => "La fecha de inicio de la operación es obligatoria.",
            "start_date.date" => "El formato de la fecha de inicio de la operación es invalido.",
            "end_date.required" => "La fecha de finalización de la operación es obligatoria.",
            "end_date.date" => "El formato de la fecha de finalización de la operación es invalido.",
            "user_id.numeric" => "El numero de usuario solo debe de contener valores numericos",
            "user_id.required" => "El numero de usuario es obligatorio",
            "c_operation_type_id.required" => "el tipo de operación es obligatorio.",
            "c_operation_type_id.numeric" => "el tipo de operación solo debe de contener valores numericos"
        ];
    }
}
