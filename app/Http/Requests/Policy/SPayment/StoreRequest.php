<?php

namespace App\Http\Requests\Policy\SPayment;

use App\Http\Requests\BaseFormRequest;

use App\Models\Policy\SPayment;
use App\Models\Policy\SAnnex;

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
        $this->merge([
            "total" => SAnnex::find($this->s_annex_id)->annex_total_value - SPayment::where('s_annex_id', $this->s_annex_id)->sum('total_value')
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
            "payment_number" => [
                "required"
            ],
            "payment_date" => [
                "required",
                "date"
            ],
            "premium_value" => [
                "required",
                "numeric"
            ],
            "tax_value" => [
                "required",
                "numeric"
            ],
            "total_value" => [
                "gt:0",
                "required",
                "numeric",
                "validate_total:" . $this->total
            ],
            "s_annex_id" => [
                "required",
                "numeric"
            ],
            "payment_form" => [
                "required",
                "in:Contado,Financiacion"
            ]
        ];
    }

    public function messages()
    {
        return [
            "payment_form.required" => "La forma de pago es requerida. valores permitidos:'Contado','Financiacion'",
            "payment_number.required" => "El identificador del pago es obligatorio.",
            "payment_date.required" => "La fecha del pago es obligatoria.",
            "premium_value.required" => "El valor del pago es obligatorio.",
            "tax_value.required" => "El valor del impuesto es obligatorio.",
            "total_value.required" => "El valor total es obligatorio.",
            "s_annex_id.required" => "El número del anexo es obligatorio.",
            "payment_date.date" => "El formato de La fecha de pago es invalido.",
            "premium_value.numeric" => "La prima del pago solo debe de contener valores numericos",
            "tax_value.numeric" => "El impuesto del pago solo debe de contener valores numericos",
            "total_value.gt" => "El valor total del pago debe ser mayor a cero",
            "total_value.numeric" => "El valor total del pago solo debe de contener valores numericos",
            "total_value.validate_total" => "El valor total del pago no debe de ser superior a $" . $this->total,
            "s_annex_id.numeric" => "El número del anexo solo debe de contener valores numericos",
        ];
    }
}
