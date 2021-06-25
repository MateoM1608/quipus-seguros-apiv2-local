<?php

namespace App\Http\Requests\Crm\CCaseArea;
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
            "description" => [
                "required",
                "unique:c_case_areas,description"
            ]
        ];
    }

    public function messages()
    {
        return [
            "description.required" => "El nombre del área es obligatorio.",
            "description.unique" => "El nombre del  ya se encuentra registrado."
        ];
    }

}
