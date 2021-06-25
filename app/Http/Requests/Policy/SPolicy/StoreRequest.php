<?php

namespace App\Http\Requests\Policy\SPolicy;

use App\Http\Requests\BaseFormRequest;

// Models
use App\Models\Policy\GVendor;
use App\Models\Policy\SAgency;
use App\Models\Policy\SBranch;
use App\Models\SClient;

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
            $branch = SBranch::withTrashed()
            ->join('s_insurance_carriers', 's_branches.s_insurance_carrier_id', 's_insurance_carriers.id')
            ->where('s_branches.name', $this->s_branch_id)
            ->where('s_insurance_carriers.identification', $this->insurance_carrier)
            ->first(['s_branches.id']);

            $this->merge(['s_branch_id' => $branch? $branch->id : 0]);

            $client = SClient::withTrashed()->whereIdentification($this->s_client_id)->first(['id']);
            $this->merge(['s_client_id' => $client ? $client->id : 0]);

            $vendor = GVendor::withTrashed()->whereIdentification($this->g_vendor_id)->first(['id']);
            $this->merge(['g_vendor_id' => $vendor ? $vendor->id : 0]);

            $agency = SAgency::withTrashed()->whereIdentification($this->s_agency_id)->first(['id']);
            $this->merge(['s_agency_id' => $agency ? $agency->id : 0]);

            $this->merge(['policy_state' => 'Vigente']);
        }
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
                'date',
                "date_format:Y-m-d",
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
            "policy_number.unique" => "El numero de poliza ya se encuentra registrada.",
            "expedition_date.required" => "La fecha de expedición es obligatoria.",
            "expedition_date.date" => "No cumple el formato de fecha aaaa-mm-dd.",
            "expedition_date.date_format" => "No cumple el formato de fecha aaaa-mm-dd.",
            "s_branch_id.numeric" => "El identificador del ramo debe ser numérico.",
            "s_branch_id.required" => "El identificador del ramo es obligatorio",
            "s_branch_id.exists" => "El ramo no se encuetra registrado.",
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
            "s_agency_id.exists" => "La agencia no se encuetra registrada.",
        ];
    }
}
