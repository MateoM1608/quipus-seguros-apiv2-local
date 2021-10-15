<?php

namespace App\Http\Requests\Profile;

use App\Http\Requests\BaseFormRequest;

class StoreRequest extends BaseFormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'description' => [
                'required',
                'unique_notsensitive:profiles,description',
            ]
        ];
    }

    public function messages()
    {
        return [
            "description.required" => "La descripción del perfil es obligatoria.",
            "description.unique_notsensitive" => "La descripción del perfil ya se encuentra registrada.",
        ];
    }
}
