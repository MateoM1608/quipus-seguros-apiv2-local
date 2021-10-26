<?php

namespace App\Http\Requests\ResetPassword;

use App\Http\Requests\BaseFormRequest;

// Models
use App\Models\PasswordReset;

class UpdateRequest extends BaseFormRequest
{
    public $errors = [];

    public function authorize()
    {
        return true;
    }

    public function prepareForValidation()
    {
        if(!$this->token) {
            $this->errors['password'][] = "No se a enviado un token.";
        }

        $exists = PasswordReset::where('token', $this->token)->count();

        if ($this->token && !$exists) {
            $this->errors['password'][] = "El token a expirado.";
        }
    }

    public function rules()
    {
        return [
            'password' => [
                'required'
            ],
            'password_confirmation' => [
                'required',
                'same:password'
            ],
        ];
    }

    public function messages()
    {
        return [
            "password.required" => "El nuevo password es requerido",
            "password_confirmation.required" => "La confirmación del nuevo password es requerida",
            "password_confirmation.same" => "Los password no coinciden.",
        ];
    }

    public function withValidator($validation)
    {
        $validation->after(function ($validation) {
            foreach ($this->errors as $key => $error) {
                foreach ($error as $value) {
                    $validation->errors()->add($key, $value);
                }
            }
        });
    }
}
