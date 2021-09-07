<?php

namespace App\Http\Requests\Policy\SCommission;

use App\Http\Requests\BaseFormRequest;

use App\Models\Policy\SCommission;

class UpdateRequest extends BaseFormRequest
{
    public $errors = [];

    public function authorize()
    {
        return true;
    }

    public function prepareForValidation()
    {
        $commission = SCommission::find($this->id);

        if ($commission->status_payment === 'Pagado') {
            $this->errors['status_payment'][] = "La comisión ya fue pagada y no es posible realizar modificaciones.";
        }
    }

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
            'payment_day' => $this->has('payment_day') ? [
                "date"
            ] : [],
            'status_payment' => $this->has('status_payment') ? [
                "required",
                "in:En revision,Por pagar,Pagado"
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
            "status_payment.in" => "Este campo solo permite los valores de 'En revision','Por pagar','Pagado'",
            "payment_day.date" => "Este campo No cumple el formato aaaa-mm-dd"

        ];
    }

    public function withValidator($validation)
    {
        $validation->after(function ($validation) {
            foreach ($this->errors as $key => $error) {
                foreach ($error as $value) {
                    $validation->errors()->add($key, $value);
                }
            }
        });
    }
}
