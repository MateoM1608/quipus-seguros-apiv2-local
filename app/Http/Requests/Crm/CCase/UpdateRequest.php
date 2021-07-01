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
            'c_type_case_stage_id' => $this->has('c_type_case_stage_id') ? [
                "required",
                "numeric",
                "exists:c_case_stages,id"
            ]:[],
            'expiration_date' => $this->has('expiration_date') ? [
                "date"
            ]: [],
            'calification' => $this->has('calification') ? [
                "numeric",
                "min:1",
                "max:5"
            ]:[]
        ];
    }

    public function messages()
    {
        return [

            "c_type_case_stage_id.required" => "El estado del caso es requerido.",
            "c_type_case_stage_id.numeric" => "El estado del caso debe ser numérico.",
            "c_type_case_stage_id.exists" => "El estado para el caso no existe.",

            "calification.min" => "La calificación del caso debe ser numérico con valores entre 1 y 5.",
            "calification.max" => "La calificación del caso debe ser numérico con valores entre 1 y 5.",

            "expiration_date.date" => "El campo de fecha fin debe tener un formato  yyyy-mm-dd."
        ];
    }
}
