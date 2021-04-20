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
            "email" => $this->has('email')? [
                "required",
                "unique:users,email," . $this->id,
                "email"
            ] : []
        ];
    }

     public function messages()
    {
        return [
            "name.required" => "El nombre de usuario es obligatorio.",
            "email.required" => "El email del usuario es obligatorio.",
            "email.unique" => "El email del usuario ingresado ya se encuentra registrado.",
            "email.email" => "El email ingresado no es valido.",
        ];
    }
}
