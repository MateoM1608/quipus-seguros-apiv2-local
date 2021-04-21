<?php

namespace App\Http\Requests\Crm\CCaseStage;
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
            'description' => $this->has('description') ? [
                "required"
            ]: [],
            'c_type_case_id' => $this->has('c_type_case_id') ? [
                "required",
                "numeric",
                "exists:c_type_cases,id"
            ]: []
        ];
    }
    public function messages()
    {
        return [
            "description.required" => "La descripción del estado del caso en CRM es obligatoria.",
            "c_type_case_id.required" => "El id de la clasificación del caso es obligatorio.",
            "c_type_case_id.numeric" => "El id de la clasificación del caso debe ser numérico.",
            "c_type_case_id.exists" => "El id de la clasificación del caso no existe."
        ];
    }
}
