<?php

namespace App\Http\Requests\Crm\CCaseNote;
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
        if ($this->type_note == 'Tarea') {
           /*Logica de poner la fecha obligaria en caso de que sea tarea*/
        }
    }

    public function rules()
    {
        return [
            'c_case_id' => [
                "required",
                "numeric",
                "exists:c_cases,id"
            ],
            'user_id' => [
                "required",
                "numeric"
            ],
            'user_name' => [
                "required"
            ],
            'user_email' => [
                "required"
            ],
            'note' => [
                "required"
            ],
            'type_note' => [
                "required",
                "in:Comentario,Tarea"
            ],
            'end_date' => $this->has('end_date') && $this->end_date? [
                "date"
            ] : [],
            'state' => [
                "required",
                "in:Finalizada,Pendiente"
            ],
        ];
    }
    public function messages()
    {
        return [
            "c_case_id.required" => "El id del caso es obligatorio.",
            "c_case_id.numeric" => "El id del caso debe ser numerico.",
            "c_case_id.exists" => "El id del caso no existe.",
            "user_id.required" => "El id de usuario es obligatorio.",
            "user_id.numeric" => "El id del usuario debe ser numerico.",
            "user_name.required" => "El nombre del usuario que inserta la nota es obligatorio.",
            "user_email.required" => "El email del usuario que inserta la nota es obligatorio.",
            "note.required" => "El nota de seguimiento es obligatoria.",
            "end_date.date" => "El formato de fecha no es correcto.",
            "state.in" => "El estado de la nota solo permite los registros (Finalizada, Pendiente).",
            "type_note.in" => "El estado de la nota solo permite los registros (Comentario, Tarea).",
            "type_note.required" => "El tipo de nota es obligatorio (Comentario, Tarea)."

        ];
    }
}
