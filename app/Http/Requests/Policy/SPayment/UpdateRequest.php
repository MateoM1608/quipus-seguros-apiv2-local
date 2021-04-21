<?php

namespace App\Http\Requests\Policy\SPayment;

use App\Http\Requests\BaseFormRequest;

use App\Models\Policy\SPayment;
use App\Models\Policy\SAnnex;

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

    public function prepareForValidation()
    {
        $total = 0;

        if ($this->s_annex_id ) {
            $total = SAnnex::find($this->s_annex_id)->annex_total_value - SPayment::where('s_annex_id', $this->s_annex_id)->where('id', '<>', $this->id)->sum('total_value');
        }

        $this->merge([
            "total" => $total
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "payment_number" => $this->has('payment_number') ? [
                "nullable"
            ] : [],
            "payment_date" => $this->has('payment_date') ? [
                "required",
                "date"
            ] : [],
            "premium_value" => $this->has('premium_value') ? [
                "required",
                "numeric"
            ] : [],
            "tax_value" => $this->has('tax_value') ? [
                "required",
                "numeric"
            ] : [],
            "total_value" => $this->has('total_value') ? [
                "required",
                "numeric",
                "validate_total:" . $this->total
            ] : [],
            "s_annex_id" => $this->has('s_annex_id') ? [
                "required",
                "numeric"
            ] : []
        ];
    }

    public function messages()
    {
        return [
            "payment_date.required" => "La fecha del pago es obligatoria.",
            "premium_value.required" => "El valor del pago es obligatorio.",
            "tax_value.required" => "El valor del impuesto es obligatorio.",
            "total_value.required" => "El valor total es obligatorio.",
            "s_annex_id.required" => "El número del anexo es obligatorio.",
            "payment_date.date" => "El formato de La fecha de pago es invalido.",
            "premium_value.numeric" => "La prima del pago solo debe de contener valores numericos",
            "tax_value.numeric" => "El impuesto del pago solo debe de contener valores numericos",
            "total_value.numeric" => "El valor total del pago solo debe de contener valores numericos",
            "total_value.validate_total" => "El valor total del pago no debe de ser superios a " . $this->total,
            "s_annex_id.numeric" => "El número del anexo solo debe de contener valores numericos",
        ];
    }
}
