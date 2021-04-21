<?php

namespace App\Http\Requests\Policy\SClaim;
use App\Http\Requests\BaseFormRequest;
//use Illuminate\Foundation\Http\FormRequest;

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
            'claim_number' => [
                "required"
            ],
            'claim_date' => [
                "required",
                "date"
            ],
            'notice_date' => [
                "required",
                "date"
            ],
            'claim_value'=> [
                "numeric",
                "nullable"
            ],
            'paid_value'=> [
                "numeric",
                "nullable"
            ],
            'payment_date'=> [
                "date",
                "nullable"
            ],
            'objection_date'=> [
                "date",
                "nullable"
            ],
            's_policy_id'=> [
                "required"
            ],
            'claim_status'=> [
                "required"
            ]
        ];

        
    }
    public function messages()
    {
        return [
            "claim_number.required" => "El número del reclamo es obligatorio.",
            "claim_date.required" => "La fecha del siniestro es obligatoria.",
            "claim_date.date" => "El formato de La fecha del siniestro es invalido.",
            "notice_date.required" => "La fecha de notificación del siniestro es requerida",
            "notice_date.date" => "El formato de La fecha de notificación del siniestro es invalido.",
            "claim_value.numeric" => "El valor reclamado solo debe de contener valores numericos",
            "paid_value.numeric" => "El valor del reclamo solo debe de contener valores numericos",
            "payment_date.date" => "El formato de La fecha de pago del siniestro es invalido.",
            "objection_date.date" => "El formato de La fecha de objeción del siniestro es invalido.",
            "s_policy_id.required" => "El identificador de la póliza del siniestro es requerido",
            "claim_status.required" => "El estado del siniestro es requerido",
        ];
    }
}
