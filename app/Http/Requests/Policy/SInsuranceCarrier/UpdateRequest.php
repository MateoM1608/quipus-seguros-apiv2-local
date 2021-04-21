<?php

namespace App\Http\Requests\Policy\SInsuranceCarrier;

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
            "insurance_carrier" => $this->has('insurance_carrier') ? [
                "required",
                "unique:s_insurance_carriers,insurance_carrier," . $this->id
            ] : [],
            "identification" => $this->has('identification') ? [
                "unique:s_insurance_carriers,identification," . $this->id
            ] : [],
        ];
    }

    public function messages()
    {
        return [
            "insurance_carrier.required" => "El nombre de la aseguradora es obligatorio.",
            "insurance_carrier.unique" => "El nombre de la aseguradora ya se encuentra registrado.",
            "identification.unique" => "El número identificador de la aseguradora ya se encuentra registrado.",
        ];
    }
}
