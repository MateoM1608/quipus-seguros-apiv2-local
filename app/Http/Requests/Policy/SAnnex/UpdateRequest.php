<?php

namespace App\Http\Requests\Policy\SAnnex;

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
            "annex_number" => $this->has('annex_number') ? [
                "required"
            ] : [],
            "annex_expedition" => $this->has('annex_expedition') ? [
                "required",
                "date"
            ] : [],
            "annex_start" => $this->has('annex_start') ? [
                "required",
                "date"
            ] : [],
            "annex_end" => $this->has('annex_end') ? [
                "required",
                "date"
            ] : [],
            "annex_premium" => $this->has('annex_premium') ? [
                "required",
                "numeric"
            ] : [],
            "annex_tax" => $this->has('annex_tax') ? [
                "required",
                "numeric"
            ] : [],
            "annex_total_value" => $this->has('annex_total_value') ? [
                "required",
                "numeric"
            ] : [],
            "annex_description" => $this->has('annex_description') ? [
                "nullable"
            ] : [],
            "annex_commission" => $this->has('annex_commission') ? [
                "required",
                "numeric"
            ] : [],
            "s_policy_id" => $this->has('s_policy_id') ? [
                "required",
                "numeric"
            ] : [],
            "annex_expedition_cost" => $this->has('annex_expedition_cost') ? [
                "numeric"
            ] : [],
            "annex_other_cost" => $this->has('annex_other_cost') ? [
                "numeric"
            ] : [],
        ];
    }

    public function messages()
    {
        return [
            "annex_number.required" => "El número del anexo es obligatorio.",
            "annex_expedition.required" => "La fecha de expedición del anexo es obligatoria.",
            "annex_expedition.date" => "El formato de La fecha de expedición del anexo es invalido.",
            "annex_start.required" => "La fecha de inicio del anexo es obligatoria.",
            "annex_start.date" => "El formato de La fecha de inicio del anexo es invalido.",
            "annex_end.required" => "La fecha de finalización del anexo es obligatoria.",
            "annex_end.date" => "El formato de La fecha de finalización del anexo es invalido.",
            "annex_premium.required" => "El valor de la prima del anexo es obligatorio.",
            "annex_premium.numeric" => "La prima del anexo solo debe de contener valores numericos",
            "annex_tax.required" => "El valor del impuesto del anexo es obligatorio.",
            "annex_tax.numeric" => "El impuesto del anexo solo debe de contener valores numericos",
            "annex_total_value.required" => "El valor total del anexo es obligatorio.",
            "annex_total_value.numeric" => "El total del anexo solo debe de contener valores numericos",
            "annex_commission.required" => "El valor de la comisión del anexo es obligatorio.",
            "annex_commission.numeric" => "La comisión del anexo solo debe de contener valores numericos",
            "s_policy_id.required" => "El número de póliza es obligatorio.",
            "s_policy_id.numeric" => "El número de póliza solo debe de contener valores numericos",
            "annex_expedition_cost" => "El costo de expedición solo debe de contener valores numericos",
            "annex_other_cost" => "El campo de otros conceptos solo debe de contener valores numericos",
        ];
    }
}
