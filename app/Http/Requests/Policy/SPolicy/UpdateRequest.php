<?php

namespace App\Http\Requests\Policy\SPolicy;

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
            'policy_number' => $this->has('policy_number') ? [
                "required",
                "unique:s_policies,policy_number," . $this->id
            ] : [],
            'expedition_date' => $this->has('expedition_date') ? [
                "required",
                "date"
            ] : [],
            's_branch_id' => $this->has('s_branch_id') ? [
                "required",
                "numeric"
            ] : [],
            's_client_id' => $this->has('s_client_id') ? [
                "required",
                "numeric"
            ] : [],
            'g_vendor_id' => $this->has('g_vendor_id') ? [
                "required",
                "numeric"
            ] : [],
            'policy_state' => $this->has('policy_state') ? [
                "required",
                "in:Vigente,No Renovada,Cancelada"
            ] : [],
            'payment_periodicity' => $this->has('payment_periodicity') ? [
                "required",
                "in:Anual,Semestral,Trimestral,Mensual,Pago Unico"
            ] : [],
            's_agency_id' => $this->has('s_agency_id') ? [
                "required",
                "numeric"
            ] : [],
        ];
    }
    public function messages()
    {
        return [
            "policy_number.required" => "El identificador de la poliza es requerido.",
            "policy_number.unique" => "La poliza registrada ya esta en uso.",
            "expedition_date.unique" => "La fecha de expedición es obligatoria.",
            "expedition_date.unique" => "No cumple el formato aaaa-mm-dd.",
            "s_branch_id.numeric" => "El identificador de la rama debe ser numérico.",
            "s_branch_id.required" => "El identificador de la rama es obligatorio",
            "s_client_id.numeric" => "El identificador del cliente debe ser numérico.",
            "s_client_id.required" => "El identificador del cliente es obligatorio",
            "g_vendor_id.numeric" => "El identificador del vendedor debe ser numérico.",
            "g_vendor_id.required" => "El identificador del vendedor es obligatorio",
            "policy_state.required" => "El estàdo de la poliza es obligatoria",
            "policy_state.in" => "Los valores permitidos para el estado de la poliza son: Vigente,No Renovada,Cancelada ",
            "payment_periodicity.required" => "El campo pericidad de pago es obligatorio",
            "payment_periodicity.in" => "Los valores permitidos para la periocidad de pago son: Anual,Semestral,Trimestral,Mensual,Pago Unico ",
            "s_agency_id.numeric" => "El identificador de la agencia debe ser numérico.",
            "s_agency_id.required" => "El identificador de la agencia es obligatorio",

        ];
    }
}
