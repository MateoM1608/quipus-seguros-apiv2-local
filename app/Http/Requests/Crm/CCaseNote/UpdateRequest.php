<?php

namespace App\Http\Requests\Crm\CCaseNote;
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
            'type_note' => $this->has('type_note') ? [
                "required",
                "in:Comentario,Tarea"
            ]:[],
            'end_date' => $this->has('end_date') && $this->end_date? [
                "date"
            ] : [],
            'state' => $this->has('state') && $this->state? [
                "in:Finalizada,Pendiente"
            ]: [],
        ];
    }
    public function messages()
    {
        return [

            "end_date.date" => "El formato de fecha no es correcto.",
            "state.in" => "El estado de la nota solo permite los registros (Finalizada, Pendiente, o dejando vacio el valor).",
            "type_note.in" => "El estado de la nota solo permite los registros (Comentario, Tarea).",
            "type_note.required" => "El tipo de nota es obligatorio."

        ];
    }
}
