<?php

namespace App\Http\Requests\Policy\GVendor;

use App\Http\Requests\BaseFormRequest;

class StoreRequest extends BaseFormRequest
{

    public function authorize()
    {
        return true;
    }

    public function prepareForValidation()
    {
        if ($this->manager_upload) {
            $this->merge(['g_identification_type_id' => 1]);
        }
    }

    public function rules()
    {
        return [
            'identification' => [
                "required",
                "unique:g_vendors,identification"
            ],
            "first_name" => [
                "required"
            ],
            'last_name' => [
                "required"
            ],
            'birthday' => $this->birthday ? [
                'date',
                "date_format:Y-m-d",
            ] : [],
            'cellphone' => [
                "required",
                "numeric",
            ],
            'email' => [
                "required",
                "email",
                "unique:g_vendors,email"
            ],
            'commission' => [
                "required",
                "numeric",
            ],
            'g_identification_type_id' => [
                "required",
                "exists:g_identification_types,id"
            ],
        ];
    }

    public function messages()
    {
        return [
            "identification.required" => "El tipo de identificación del cliente es obligatorio.",
            "identification.unique" => "El número de identificacion del cliente ya se encuentra registrado.",
            "first_name.required" => "El nombre del cliente es obligatorio.",
            "last_name.required" => "El apellido del cliente es obligatorio.",
            "birthday.date" => "La fecha de cumpleaños no cumple el formato de fecha aaaa-mm-dd.",
            "birthday.date_format" => "La fecha de cumpleaños no cumple el formato de fecha aaaa-mm-dd.",
            "cellphone.required" => "El número de celular es requerido",
            "cellphone.numeric" => "El número de celular solo debe de contener valores numericos",
            "email.required" => "El email es requerido",
            "email.email" => "El email registrado no cumple con el estandar de nombre@nombre.com",
            "email.unique" => "El email ingresado ya se encuentra registrado",
            "commission.required" => "El valor de comisión es requerido",
            "commission.numeric" => "El campo de comisión debe ser numérico",
            "g_identification_type_id.required" => "El tipo de identificación es requerido",
            "g_identification_type_id.exists" => "El tipo de identificación envido no existe",

        ];
    }
}
