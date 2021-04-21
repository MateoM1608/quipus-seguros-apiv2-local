<?php

namespace App\Http\Requests\Policy\SBranch;

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
            "name" => $this->has('name') ? [
                "required",
            ] : [],
            "commission" => $this->has('commission') ? [
                "required",
                "numeric"
            ] : [],
            "tax" => $this->has('tax') ? [
                "required",
                "numeric"
            ] : [],
            "s_insurance_carrier_id" => $this->has('s_insurance_carrier_id') ? [
                "required",
                "numeric"
            ] : [],
            "loss_coverage" => $this->has('loss_coverage') ? [
                "required",
                "integer"
            ] : [],
            "cancellation_risk" => $this->has('cancellation_risk') ? [
                "required",
                "integer"
            ] : [],
            "cancellation" => $this->has('cancellation') ? [
                "required",
                "integer"
            ] : [],
        ];
    }

    public function messages()
    {
        return [
            "name.required" => "El nombre de la rama es obligatorio.",
            "commission.required" => "El valor de la comisión es obligatorio.",
            "commission.numeric" => "El valor de la comisión debe ser en formato numérico.",
            "tax.required" => "El valor del impuesto es obligatorio.",
            "tax.numeric" => "El valor del impuesto debe ser en formato numérico.",
            "s_insurance_carrier_id.required" => "El identificador de la compañia aseguradora es obligatorio.",
            "s_insurance_carrier_id.numeric" => "El identificador de la compañia aseguradora debe ser en formato numérico.",
            "loss_coverage.required" => "Los días de perdida de cobertura son obligatorios.",
            "loss_coverage.numeric" => "Los días de perdida de cobertura debe ser un número entero",
            "cancellation_risk.required" => "Los días de riesgo de cancelación son obligatorios.",
            "cancellation_risk.numeric" => "Los días de riesgo de cancelación debe ser un número entero",
            "cancellation.required" => "Los días de cancelación son obligatorios.",
            "cancellation.numeric" => "Los días de cancelación debe ser un número entero",
        ];
    }
}
