<?php

namespace App\Http\Requests\Policy\SInsuranceCarrier;

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
            "insurance_carrier" => [
                "required",
                "unique:s_insurance_carriers,insurance_carrier"
            ],
            "identification" => $this->has('identification') && $this->identification? [
                "unique:s_insurance_carriers,identification",
                "numeric"
            ] : [],
        ];
    }

    public function messages()
    {
        return [
            "insurance_carrier.required" => "El nombre de la aseguradora es obligatorio.",
            "insurance_carrier.unique" => "El nombre de la aseguradora ya se encuentra registrado.",
            "identification.unique" => "El Nit de la aseguradora ya se encuentra registrado.",
            "identification.numeric" => "El nit de la aseguradora debe ser un valor numérico.",
        ];
    }
}
