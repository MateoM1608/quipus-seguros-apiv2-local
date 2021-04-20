<?php

namespace App\Http\Requests\Policy\SPolicy;

use App\Http\Requests\BaseFormRequest;

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
            'policy_number' => [
                "required",
                "unique:s_policies,policy_number"
            ],
            'expedition_date' => [
                "required",
                "date"
            ],
            's_branch_id' => [
                "required",
                "numeric",
                "exists:s_branches,id"
            ],
            's_client_id' => [
                "required",
                "numeric",
                "exists:s_clients,id"
            ],
            'g_vendor_id' => [
                "required",
                "numeric",
                "exists:g_vendors,id"
            ],
            'policy_state' => [
                "required",
                "in:Vigente,No Renovada,Cancelada"
            ],
            'payment_periodicity' => [
                "required",
                "in:Anual,Semestral,Trimestral,Mensual,Pago Unico"
            ],
            's_agency_id' => [
                "required",
                "numeric",
                "exists:s_agencies,id"
            ],
        ];
    }
    public function messages()
    {
        return [
            "policy_number.required" => "El identificador de la poliza es requerido.",
            "policy_number.unique" => "La poliza registrada ya esta en uso.",
            "expedition_date.required" => "La fecha de expedición es obligatoria.",
            "expedition_date.unique" => "No cumple el formato aaaa-mm-dd.",
            "s_branch_id.numeric" => "El identificador de la rama debe ser numérico.",
            "s_branch_id.required" => "El identificador de la rama es obligatorio",
            "s_branch_id.exists" => "La rama no se encuetra registrada.",
            "s_client_id.numeric" => "El identificador del cliente debe ser numérico.",
            "s_client_id.required" => "El identificador del cliente es obligatorio",
            "s_client_id.exists" => "El cliente no se encuetra registrado.",
            "g_vendor_id.numeric" => "El identificador del vendedor debe ser numérico.",
            "g_vendor_id.required" => "El identificador del vendedor es obligatorio",
            "g_vendor_id.exists" => "El vendedor no se encuetra registrado.",
            "policy_state.required" => "El estàdo de la poliza es obligatoria",
            "policy_state.in" => "Los valores permitidos para el estado de la poliza son: Vigente,No Renovada,Cancelada ",
            "payment_periodicity.required" => "El campo pericidad de pago es obligatorio",
            "payment_periodicity.in" => "Los valores permitidos para la periocidad de pago son: Anual,Semestral,Trimestral,Mensual,Pago Unico ",
            "s_agency_id.numeric" => "El identificador de la agencia debe ser numérico.",
            "s_agency_id.required" => "El identificador de la agencia es obligatorio",
            "s_agency_id.exists" => "El agencia no se encuetra registrada.",

        ];
    }
}
