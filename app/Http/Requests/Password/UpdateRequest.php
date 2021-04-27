<?php

namespace App\Http\Requests\Password;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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

    public function withValidator($validation) {
        $this->merge([
            'password' => bcrypt($this->password)
        ]);
    }
}
