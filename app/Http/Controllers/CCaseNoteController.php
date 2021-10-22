<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

// FormRequest
use App\Http\Requests\Crm\CCaseNote\UpdateRequest;
use App\Http\Requests\Crm\CCaseNote\StoreRequest;

// Models
use App\Models\Crm\CCaseNote;
use App\Models\Crm\CCase;
use App\Models\User;

// Events
use App\Events\CCaseNoteEvent;

class CCaseNoteController extends Controller
{
    public function index(Request $request)
    {
        $data = CCaseNote::where(function ($query) use ($request) {

            if (isset($request->case)) {
                $query->where('c_case_id', $request->case);
            }
        });

        if ($request->trashed) {
            $data->withTrashed();
        }

        $response = [];

        if (isset($request->paginate) && $request->paginate == 1) {
            $data = $data->paginate($request->rows ?: 25, $request->colums ? $request->colums : ['*']);
            $response = [
                'total' => $data->total(),
                'data'  => $data->toArray()['data']
            ];
        } else {
            $response = [
                'total' => $data->count(),
                'data'  => $data->get($request->colums ? $request->colums : ['*'])
            ];
        }

        return response()->json($response);
    }

    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $note = CCaseNote::create($request->all());
            DB::commit();
            event(new CCaseNoteEvent($note));

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        $userResponsible = CCase::find($note['c_case_id'],['assigned_user_id']); //Traemos el id del usuario responsable
        $user = User::find($userResponsible->assigned_user_id, ['email']); //Traemos el email del usuario responsable
        //dd($user);
        //Validamos que si el tipo de nota es una tarea, envie un correo al responsable del caso.
        if($note['type_note'] == 'Tarea'){

            $newTask = [
                'email' => $user->email,
                'case' => $note->c_case_id,
                'url' => 'https://quipus-1806d.web.app',
                'note' => $note->note,
                'creator_case' => $note->user_name
            ];

            \Mail::send('emails.crm.newTask', $newTask, function ($message) use($user) {
                $message->from('noreply@amauttasystems.com', 'Quipus seguros');
                $message->to($user->email)->subject('Nueva tarea CRM');
            });

        }

        return response()->json($note);

    }

    public function update(UpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $note = CCaseNote::find($id);
            $note->update($request->only(['state','type_note', 'end_date']));

            event(new CCaseNoteEvent($note));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        return response()->json($note);
    }

    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $note = CCaseNote::withTrashed()->findOrFail($id);

            if ($request->force) {
                $note->forceDelete();
            } else if ($note->trashed()) {
                $note->restore();
            } else {
                $note->delete();
            }

            event(new CCaseNoteEvent($note));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        return response()->json($note);
    }
}
