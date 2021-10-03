<?php

namespace App\Http\Requests\Crm\CCase;

use App\Http\Requests\BaseFormRequest;

class UpdateRequest extends BaseFormRequest
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
            's_client_id' => $this->has('s_client_id') && $this->s_client_id? [
                "numeric",
                "exists:s_clients,id"
            ] : [],
            's_policy_id' => $this->has('s_policy_id') && $this->s_policy_id? [
                "numeric",
                "exists:s_policies,id"
            ]: [],
            'c_type_case_stage_id' => [
                "required",
                "numeric",
                "exists:c_case_stages,id"
            ],
            'c_case_area_id' => [
                "required",
                "numeric",
                "exists:c_case_areas,id"
            ],
            'creator_user_id' => [
                "required",
                "numeric"
            ],
            'creator_name' => [
                "required",
            ],
            'assigned_user_id' => [
                "required",
                "numeric"
            ],
            'assigned_name' => [
                "required",
            ],
            'status_case' => [
                "required",
                "in:Abierto,Cerrado"
            ],
            'projected_value' => $this->has('projected_value') && $this->projected_value? [
                "numeric"
            ]: [],
            'real_value' => $this->has('real_value') && $this->real_value? [
                "numeric"
            ]: [],
            'calification' => $this->has('calification') && $this->calification? [
                "numeric",
                "min:1",
                "max:5"
            ]: [],
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

            "creator_user_id.required" => "El identificador del usuario que crea el caso es requerido.",
            "creator_user_id.numeric" => "El identificador del usuario que crea el caso debe ser numérico.",

            "assigned_user_id.required" => "El identificador del usuario responsable del caso es requerido.",
            "assigned_user_id.numeric" => "El identificador del usuario responsable del caso debe ser numérico.",

            "expiration_date.date" => "El campo de fecha fin es obligatorio.",

            "creator_name.required" => "El campo de nombre del creador del caso es obligatorio.",
            "assigned_name.required" => "El campo de nombre del usuario responsable del caso es obligatorio.",

            "status_case.in" => "El estado del caso solo admite los valores Abierto o Cerrado.",
            "status_case.required" => "El estado del caso es obligatorio.",

            "projected_value.numeric" => "El valor proyectado del caso debe ser numérico.",
            "real_value.numeric" => "El valor real obtenido del caso  debe ser numérico.",
        ];
    }
}
