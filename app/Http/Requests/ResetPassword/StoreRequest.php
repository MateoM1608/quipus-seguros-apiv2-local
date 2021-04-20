<?php

namespace App\Http\Requests\ResetPassword;

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
            'email' => [
                "required",
                "validate_email"
            ],
        ];
    }

    public function messages()
    {
        return [
            "email.required" => "Favor ingrese su email.",
            "email.validate_email" => "El email ingresado no se encuentra registrado.",
        ];
    }
}
