<?php

namespace App\Http\Requests\User;

use Faker\Factory as Faker;

// FormRequest
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

    public function prepareForValidation()
    {
        //
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "name" => $this->has('name')? [
                "required",
            ] : [],
            "identification" => $this->has('identification')? [
                "required",
                "unique_user_with:identification," . $this->id,
            ] : [],
            "email" => $this->has('email')? [
                "required",
                "unique_user_with:email," . $this->id,
                "email"
            ] : [],
        ];
    }

     public function messages()
    {
        return [
            "name.required" => "El nombre de usuario es obligatorio.",
            "identification.required" => "La identificación del usuario es obligatoria.",
            "identification.unique_user_with" => "La identificación ingresada ya se encuentra registrada.",
            "email.required" => "El email del usuario es obligatorio.",
            "email.unique_user_with" => "El email del usuario ingresado ya se encuentra registrado.",
            "email.email" => "El email ingresado no es valido.",
        ];
    }
}
