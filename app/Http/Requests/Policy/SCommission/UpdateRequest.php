<?php

namespace App\Http\Requests\Policy\SCommission;

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
            'commission_number' => $this->has('commission_number') ? [
                "numeric"
            ] : [],
            'commission_date' => $this->has('commission_date') ? [
                "required",
                "date"
            ] : [],
            'commission_value' => $this->has('commission_value') ? [
                "required",
                "numeric"
            ] : [],
            's_annex_id' => $this->has('s_annex_id') ? [
                "required",
                "numeric"
            ] : [],
            's_payroll_id' => $this->has('s_payroll_id') ? [
                "required",
                "numeric"
            ] : [],
            'g_vendor_id' => $this->has('g_vendor_id') ? [
                "required",
                "numeric"
            ] : [],
            'vendor_commission_paid' => $this->has('vendor_commission_paid') ? [
                "required",
                "in:Si,No"
            ] : [],
            'agency_commission' => $this->has('agency_commission') ? [
                "required",
                "numeric"
            ] : [],
        ];
    }

    public function messages()
    {
        return [
            "commission_number.required" => "El identificador de la comisión es requerido.",
            "commission_date.required" => "La fecha de comisión es obligatoria.",
            "commission_date.date" => "No cumple el formato aaaa-mm-dd.",
            "commission_value.numeric" => "El valor de la comisión debe ser numérico.",
            "commission_value.required" => "El valor de la comisión es obligatorio",
            "s_annex_id.numeric" => "El identificador del anexo debe ser numérico.",
            "s_annex_id.required" => "El identificador del anexo es obligatorio",
            "s_payroll_id.numeric" => "El identificador del pago debe ser numérico.",
            "s_payroll_id.required" => "El identificador del pago es obligatorio",
            "g_vendor_id.required" => "El identificador del vendedor es obligatoria",
            "g_vendor_id.numeric" => "El identificador del vendedor debe ser numérico. ",
            "vendor_commission_paid.required" => "El campo de pago a vendedor es obligatorio",
            "vendor_commission_paid.in" => "Los valores permitidos para la pago vendedor son: Si,No ",
            "agency_commission.numeric" => "El identificador de la agencia debe ser numérico.",
            "agency_commission.required" => "El identificador de la agencia es obligatorio",

        ];
    }
}
