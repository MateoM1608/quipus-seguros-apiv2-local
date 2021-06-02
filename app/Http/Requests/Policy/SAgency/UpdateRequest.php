<?php

namespace App\Http\Requests\Policy\SAgency;

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

    public function rules()
    {
        return [
            "agency_name" => $this->has('agency_name') ? [
                "required",
                "unique:s_agencies,agency_name," . $this->id
            ] : [],
            "identification" => $this->has('identification') && $this->identification? [
                "numeric",
                "unique:s_agencies,identification," . $this->id
            ] : [],
            "agency_commission" => $this->has('agency_commission') ? [
                "required",
                "numeric",
            ] : []
        ];
    }

    public function messages()
    {
        return [
            "agency_name.required" => "El nombre de la agencia es obligatorio.",
            "agency_name.unique" => "El nombre de la agencia ya se encuentra registrado.",
            "identification.numeric" => "El nit de la agencia debe ser un valor numérico.",
            "agency_commission.required" => "El porcentaje de comisión de la agencia es obligatorio.",
            "agency_commission.numeric" => "El porcentaje de comisión debe ser numérico",
        ];
    }
}
