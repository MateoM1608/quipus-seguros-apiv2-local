<?php

namespace App\Http\Requests\Policy\GVendor;

use App\Http\Requests\BaseFormRequest;

class UpdateRequest extends BaseFormRequest
{
    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'identification' => $this->has('identification') ? [
                "required",
                "unique:g_vendors,identification," . $this->id
            ] : [],
            "first_name" => $this->has('first_name') ? [
                "required"
            ] : [],
            'last_name' => $this->has('last_name') ? [
                "required"
            ] : [],
            'cellphone' => $this->has('cellphone') ? [
                "required",
                "numeric",
            ] : [],
            'email' => $this->has('email') ? [
                "required",
                "email",
                "unique:g_vendors,email," . $this->id
            ] : [],
            'commission' => $this->has('commission') ? [
                "required",
                "numeric",
            ] : [],
            'g_identification_type_id' => $this->has('g_identification_type_id') ? [
                "required"
            ] : [],
        ];
    }

    public function messages()
    {
        return [
            "identification.required" => "El tipo de identificación del cliente es obligatorio.",
            "identification.unique" => "El número de identificacion del cliente ya se encuentra registrado.",
            "first_name.required" => "El nombre del cliente es obligatorio.",
            "last_name.required" => "El apellido del cliente es obligatorio.",
            "cellphone.required" => "El número de celular es requerido",
            "cellphone.numeric" => "El número de celular solo debe de contener valores numericos",
            "email.required" => "El email es requerido",
            "email.email" => "El email registrado no cumple con el estandar de nombre@nombre.com",
            "email.unique" => "El email ingresado ya se encuentra registrado",
            "commission.required" => "El valor de comisión es requerido",
            "commission.numeric" => "El campo de comisión debe ser numérico",
            "g_identification_type_id.required" => "El tipo de identificación es requerido",

        ];
    }
}
