<?php

namespace App\Http\Requests\Crm\CCase;

use App\Http\Requests\BaseFormRequest;

use App\Models\Crm\CTypeCase;

class StoreRequest extends BaseFormRequest
{
    private $errors = [];


    public function authorize()
    {
        return true;
    }

    public function prepareForValidation()
    {
        $typeCase = CTypeCase::find($this->c_type_case_id, ['description']);

        if ($typeCase && in_array($typeCase->description, ['Servicio al cliente', 'Oportunidades de negocio']) && !$this->s_client_id) {
            $this->errors['s_client_id'][] = "El cliente es requerido.";
        }

        if ($this->status_case == "Cerrado" && $this->closing_note == "")  {
            $this->errors['closing_note'][] = "Se requiere una nota de cierre para el caso CRM.";
        }
    }

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
            'expiration_date' => [
                "date"
            ],
            'status_case' => [
                "required",
                "in:Abierto,Cerrado"
            ],
            'projected_value' => $this->has('projected_value') && $this->projected_value? [
                "numeric"
            ]:[],
            'real_value' => $this->has('real_value') && $this->real_value? [
                "numeric"
            ]: [],
            'calification' => $this->has('calification') && $this->calification? [
                "numeric",
                "min:1",
                "max:5"
            ]: [],
            'description' => [
                "required",
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

            "c_type_case_stage_id.required" => "La etapa para el tipo de caso seleccionado es requerida.",
            "c_type_case_stage_id.numeric" => "El Id de la etapa para este tipo de caso debe ser numérico.",
            "c_type_case_stage_id.exists" => "La etapa registrada, no existe.",

            "calification.min" => "La calificación del caso debe ser numérico con valores entre 1 y 5.",
            "calification.max" => "La calificación del caso debe ser numérico con valores entre 1 y 5.",

            "creator_user_id.required" => "El identificador del usuario que crea el caso es requerido.",
            "creator_user_id.numeric" => "El identificador del usuario que crea el caso debe ser numérico.",

            "assigned_user_id.required" => "El identificador del usuario responsable del caso es requerido.",
            "assigned_user_id.numeric" => "El identificador del usuario responsable del caso debe ser numérico.",

            "status_case.in" => "El estado del caso solo admite los valores Abierto o Cerrado.",
            "status_case.required" => "El estado del caso es obligatorio.",

            "projected_value.numeric" => "El valor proyectado del caso debe ser numérico.",
            "real_value.numeric" => "El valor real obtenido del caso  debe ser numérico.",

            "expiration_date.date" => "El campo de fecha fin es obligatorio.",

            "creator_name.required" => "El campo de nombre del creador del caso es obligatorio.",
            "assigned_name.required" => "El campo de nombre del usuario responsable del caso es obligatorio.",
            "c_case_area_id.required" => "El identificador del Area es obligatorio.",

            "description.required" => "La descripción del caso es obligatoria.",
        ];
    }

    public function withValidator($validation)
    {
        $validation->after(function ($validation) {
            foreach ($this->errors as $key => $error) {
                foreach ($error as $value) {
                    $validation->errors()->add($key, $value);
                }
            }
        });
    }
}
