<?php

namespace App\Http\Requests\ResetPassword;

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
            'token' => [
                'required',
                'exists:password_resets,token'
            ],
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
            "token.required" => "Envie un token.",
            "token.exists" => "El token a expirado",
            "password.required" => "El nuevo password es requerido",
            "password_confirmation.required" => "La confirmación del nuevo password es requerida",
            "password_confirmation.same" => "Los password no coinciden.",
        ];
    }
}
