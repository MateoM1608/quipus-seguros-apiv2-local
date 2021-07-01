<?php

namespace App\Http\Requests\Policy\SAnnex;

use App\Http\Requests\BaseFormRequest;
use Carbon\Carbon;

// Models
use App\Models\Policy\SPolicy;

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

    public function prepareForValidation()
    {
        if ($this->manager_upload) {
            $policy = SPolicy::with('sBranch')
            ->wherePolicyNumber($this->policy_number)->first([
                'id',
                'expedition_date',
                's_branch_id',
            ]);

            $annex_tax = $policy && $policy->sBranch && $policy->sBranch->tax ?  $this->annex_premium * ($policy->sBranch->tax / 100) : 0;

            $this->merge([
                'annex_number' => '1',
                'annex_expedition' => $policy? $policy->expedition_date : null,
                'annex_start' => $policy? $policy->expedition_date : null,
                'annex_end' => $policy && $policy->expedition_date ? Carbon::parse($policy->expedition_date)->addYear()->format('Y-m-d') : null,
                'annualized_premium' => $this->annex_premium,
                'annex_tax' => $annex_tax,
                'annex_expedition_cost' => 0,
                'annex_other_cost' => 0,
                'annex_total_value' => $this->annex_premium + $annex_tax,
                'annex_commission' => $policy && $policy->sBranch && $policy->sBranch->commission ?  $this->annex_premium * ($policy->sBranch->commission / 100) : 0,
                'annex_paid' => 'No',
                'commission_paid' => 'No',
                'annex_type' => 'Expedición',
                's_policy_id' => $policy? $policy->id : 0,
                'annex_print' => 'N/A',
                'annex_printed' => 'N/A',
                'annex_email' => 'N/A',
                'annex_delivered' => 'N/A',
                'annex_description' => 'Anexo creado por migración',
            ]);
        }
    }

    public function rules()
    {
        return [
            "annex_number" => [
                "required"
            ],
            "annex_expedition" => [
                "required",
                "date"
            ],
            "annex_start" => [
                "required",
                "date"
            ],
            "annex_end" => [
                "required",
                "date"
            ],
            "annex_premium" => [
                "required",
                "numeric"
            ],
            "annex_tax" => [
                "required",
                "numeric"
            ],
            "annex_total_value" => [
                "required",
                "numeric"
            ],
            "annex_description" => [
                "nullable"
            ],
            "annex_commission" => [
                "required",
                "numeric"
            ],
            "s_policy_id" => [
                "required",
                "numeric"
            ],
            "annex_expedition_cost" => [
                "numeric"
            ],
            "annex_other_cost" => [
                "numeric"
            ]

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
