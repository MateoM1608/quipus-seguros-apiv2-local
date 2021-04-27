<?php

namespace App\Http\Requests\Crm\CProcess;

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
            "description" => [
                "required"
            ],
		"start_date" => [
            "required",
            "date"
        ],
		"end_date" => [
            "required",
            "date"
        ],
		"sale_value" => [
            "nullable",
            "numeric"
        ],
		"open_close" => [
            "required"
        ],
		"c_process_stage_id" => [
            "required",
            "numeric"
        ],
		"s_client_id" => [
            "required",
            "numeric"
        ]
        ];
    }

    public function messages()
    {
        return [

            "description.required" => "La descripción del proceso es obligatoria.",
            "start_date.required" => "La fecha de inicio del proceso es obligatoria.",
            "end_date.required" => "La fecha de finalización del processo es obligatoria.",
            "start_date.date" => "El formato de La fecha de inicio del proceso es invalido.",
            "end_date.date" => "El formato de La fecha de finalización del proceso es invalido.",
            "sale_value.numeric" => "El valor de venta del proceso solo debe de contener valores numericos",
            "open_close.required" => "Se debe indicar si el proceso está abierto o cerrado.",
            "c_process_stage_id.required" => "La etapa del proceso es obligatorio.",
            "c_process_stage_id.numeric" => "La etapa del proceso solo debe de contener valores numericos",
            "s_client_id.required" => "El cliete del proceso es obligatorio.",
            "s_client_id.numeric" => "La etapa del proceso solo debe de contener valores numericos"
        ];
    }
}
