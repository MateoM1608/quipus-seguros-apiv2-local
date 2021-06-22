<?php

namespace App\Http\Requests\Crm\CCase;

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
            'c_type_case_id' => [
                "required",
                "numeric",
                "exists:c_type_cases,id"
            ],
            's_client_id' => $this->has('s_client_id') ? [
                "numeric",
                "exists:s_clients,id"
            ]: [],
            's_policy_id' => [
                "numeric",
                "exists:s_policies,id"
            ],
            'c_type_case_stage_id' => [
                "required",
                "numeric",
                "exists:c_case_stages,id"
            ],
            'calification' => [
                "numeric",
                "min:1",
                "max:5"
            ]
        ];
    }
    public function messages()
    {
        return [
            "c_type_case_id.required" => "El identificador del tipo de caso es requerido.",
            "c_type_case_id.numeric" => "El identificador del tipo de caso debe ser numérico.",
            "c_type_case_id.exists" => "El identificador del tipo de caso no existe.",

            "s_client_id.numeric" => "El identificador del cliente debe ser numérico.",
            "s_client_id.exists" => "El identificador del cliente no existe.",

            "s_policy_id.numeric" => "El identificador de la poliza debe ser numérico.",
            "s_policy_id.exists" => "El identificador de la poliza no existe.",

            "c_type_case_stage_id.required" => "El estado del caso es requerido.",
            "c_type_case_stage_id.numeric" => "El estado del caso debe ser numérico.",
            "c_type_case_stage_id.exists" => "El estado para el caso no existe.",

            "calification.min" => "La calificación del caso debe ser numérico con valores entre 1 y 5.",
            "calification.max" => "La calificación del caso debe ser numérico con valores entre 1 y 5.",

        ];
    }
}
