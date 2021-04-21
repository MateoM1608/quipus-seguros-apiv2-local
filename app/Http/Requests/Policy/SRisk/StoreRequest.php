<?php

namespace App\Http\Requests\Policy\SRisk;

use App\Http\Requests\BaseFormRequest;
//use Illuminate\Foundation\Http\FormRequest;

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
            'risk_number' => [
                "required",
                "numeric"
            ],
            'risk_description' => [
                "required"
            ],
            'risk_premium' => [
                "required",
                "numeric"
            ],
        ];
    }

    public function messages()
    {
        return [
            "risk_number.required" => "El número del riesgo es obligatorio.",
            "risk_number.numeric" => "El número del riesgo debe de ser numerico.",
            "risk_description.required" => "La descripción del riesgo es obligatoria.",
            "risk_premium.required" => "El valor de la prima del riesgo es requerido",
            "risk_premium.numeric" => "El valor de la prima del riesgo solo debe de contener valores numericos",
        ];
    }
}
