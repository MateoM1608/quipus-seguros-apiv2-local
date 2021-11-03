<?php

namespace App\Http\Requests\Policy\SClaim;

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
            'claim_number' => $this->has('claim_number') ? [
                "required"
            ] : [],
            'claim_date' => $this->has('claim_date') ? [
                "required",
                "date"
            ] : [],
            'notice_date' => $this->has('notice_date') ? [
                "required",
                "date"
            ] : [],
            'claim_value' => $this->has('claim_value') ? [
                "numeric"
            ] : [],
            'paid_value' =>$this->has('paid_value') && $this->paid_value ? [
                "numeric"
            ] : [],
            'payment_date' => $this->has('payment_date') && $this->payment_date ? [
                "date"
            ] : [],
            'objection_date' => $this->has('objection_date') && $this->objection_date ? [
                "date"
            ] : [],
            's_policy_id' => $this->has('s_policy_id') ? [
                "required"
            ] : [],
            'claim_status' => $this->has('claim_status') ? [
                "required"
            ] : [],
            'claim_description' => $this->has('claim_description') ? [
                "required"
            ] : [],

        ];
    }
    public function messages()
    {
        return [
            "claim_number.required" => "El número del reclamo es obligatorio.",
            "claim_date.required" => "la fecha del siniestro es obligatoria.",
            "claim_date.date" => "El formato de la fecha del siniestro es invalido.",
            "notice_date.required" => "la fecha de notificación del siniestro es requerida",
            "notice_date.date" => "El formato de la fecha de notificación del siniestro es invalido.",
            "claim_value.numeric" => "El valor reclamado solo debe de contener valores numericos",
            "paid_value.numeric" => "El valor del reclamo solo debe de contener valores numericos",
            "payment_date.date" => "El formato de la fecha de pago del siniestro es invalido.",
            "objection_date.date" => "El formato de la fecha de objeción del siniestro es invalido.",
            "s_policy_id.required" => "El identificador de la póliza del siniestro es requerido",
            "claim_status.required" => "El estado del siniestro es requerido",
            "claim_description.required" => "La descripción del siniestro es requerida",
        ];
    }
}
