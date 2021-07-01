<?php

namespace App\Http\Requests\Policy\SAgency;

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
            "agency_name" => [
                "required",
                "unique:s_agencies,agency_name"
            ],
            "identification" => [
                "required",
                "unique:s_agencies,identification",
                "numeric"
            ],
            "agency_commission" => [
                "required",
                "numeric",
            ]
        ];
    }

    public function messages()
    {
        return [
            "agency_name.required" => "El nombre de la agencia es obligatorio.",
            "agency_name.unique" => "El nombre de la agencia ya se encuentra registrado.",
            "identification.required" => "El nit de la agencia es obligatorio.",
            "identification.unique" => "El Nit de la agencia ya se encuentra registrado.",
            "identification.numeric" => "El nit de la agencia debe ser un valor numérico.",
            "agency_commission.required" => "El porcentaje de comisión de la agencia es obligatorio.",
            "agency_commission.numeric" => "El porcentaje de comisión debe ser numérico",
        ];
    }

}
