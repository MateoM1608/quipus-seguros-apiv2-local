<?php

namespace App\Http\Requests\User;

use Faker\Factory as Faker;

// FormRequest
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

    public function prepareForValidation()
    {
        $faker = Faker::create();
        $password = $faker->password();

        $this->merge([
            "password" => bcrypt($password),
            "password_real" => $password,
            "connection" => $this->connection? $this->connection : auth()->user()->connection,
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
            "name" => [
                "required",
            ],
            "email" => [
                "required",
                "unique:users,email",
                "email"
            ],
            "password" => [
                "required"
            ],
            "connection" => [
                "required"
            ],
        ];
    }

     public function messages()
    {
        return [
            "name.required" => "El nombre de usuario es obligatorio.",
            "email.required" => "El email del usuario es obligatorio.",
            "email.unique" => "El email del usuario ingresado ya se encuentra registrado.",
            "email.email" => "El email ingresado no es valido.",
            "password.required" => "Hubo un problema en la asignación de la contraseña",
            "connection.required" => "Hubo un problema en la asignación de la conexión",
        ];
    }
}
