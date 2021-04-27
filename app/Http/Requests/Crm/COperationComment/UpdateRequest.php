<?php

namespace App\Http\Requests\Crm\COperationComment;

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
            'comment_description' => $this->has('comment_description') ? [
                "required" 
            ] : [],
		    'comment_date' => $this->has('comment_date') ? [
                "required",
                "date"                 
            ] : [],
            'user_id' => $this->has('user_id') ? [
                "required",
                "numeric"
            ] : [],
            'c_operation_id' => $this->has('c_operation_id') ? [
                "required",
                "numeric" 
            ] : []
        ];
    }
    public function messages()
    {
        return [

            "comment_description.required" => "La descripción del comentario es obligatoria.",
            "comment_date.required" => "La fecha del comentario es obligatoria.",
            "comment_date.date" => "El formato de la fecha del comentario es invalido.",
            "user_id.numeric" => "El numero de usuario solo debe de contener valores numericos",
            "user_id.required" => "El numero de usuario es obligatorio",
            "c_operation_id.required" => "La operación es obligatoria.",
            "c_operation_id.numeric" => "El identificador de la operación solo debe de contener valores numericos"
        ];
    }
}
