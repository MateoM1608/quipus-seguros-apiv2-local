<?php

namespace App\Http\Requests\SClient;

use App\Http\Requests\BaseFormRequest;

// Models
use App\Models\GIdentificationType;
use App\Models\GCity;
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

        if (gettype($this->g_city_id) == "string") {
            $city = GCity::whereDescription($this->g_city_id)->first(['id']);
            $this->merge(['g_city_id' => $city->id]);
        }

        if (gettype($this->g_identification_type_id) == "string") {
            $identification = GIdentificationType::whereDescription($this->g_identification_type_id)->first(['id']);
            $this->merge(['g_identification_type_id' => $identification->id]);
        }

        $this->merge([
            "fix_phone" => trim($this->fix_phone)
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
            'identification' => [
                "required",
                "unique:s_clients,identification"
            ],
            "first_name" => [
                "required"
            ],
            'last_name' => [
                "required"
            ],
            'birthday' => [
                "date",
                "date_format:Y-m-d"
            ],
            'fix_phone' => $this->has('fix_phone') && $this->fix_phone ? [
                "numeric",
            ] : [],
            'cel_phone' => [
                "required",
                "numeric",
            ],
            'email' => [
                "required",
                "email"
            ],
            'g_city_id' => [
                "required",
                "numeric",
            ],
            'g_identification_type_id' => [
                "required",
                "numeric",
            ],
            'habeas_data_terms' => [
                "in:Si,No"
            ],
            'habeas_data_email' => [
                "in:Si,No"
            ],
            'habeas_data_sms' => [
                "in:Si,No"
            ],
            'habeas_data_phone' => [
                "in:Si,No"
            ],
        ];
    }

    public function messages()
    {
        return [
            "identification.required" => "El tipo de identificación del cliente es obligatorio",
            "identification.unique" => "El número de identificacion del cliente ya se encuentra registrado",
            "first_name.required" => "El nombre del cliente es obligatorio",
            "last_name.required" => "El apellido del cliente es obligatorio",
            "birthday.date" => "El valor ingresado con es una fecha correcta",
            "birthday.date_format" => "La fecha ingresada no cumple el formato aaaa-mm-dd",
            "fix_phone.numeric" => "El telèfono debe ser numèrico",
            "cel_phone.required" => "El número de celular es requerido",
            "cel_phone.numeric" => "El número de celular solo debe de contener valores numericos",
            "email.required" => "El email es requerido",
            "email.email" => "El email registrado no cumple con el estandar de nombre@nombre.com",
            "g_city_id.required" => "El id de la ciudad es requerido",
            "g_city_id.numeric" => "El id de la ciudad debe ser numérico",
            "g_identification_type_id.required" => "El tipo de identificación del cliente es requerido",
            "g_identification_type_id.numeric" => "El id asociado al tipo de identificación debe ser numérico",
            "habeas_data_terms.in" => "Los valores permitidos para este campo son Si/No",
            "habeas_data_email.in" => "Los valores permitidos para este campo son Si/No",
            "habeas_data_sms.in" => "Los valores permitidos para este campo son Si/No",
            "habeas_data_phone.in" => "Los valores permitidos para este campo son Si/No"
        ];
    }
}
