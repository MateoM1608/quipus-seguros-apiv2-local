<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

// FormRequest
use App\Http\Requests\Crm\CCase\UpdateRequest;
use App\Http\Requests\Crm\CCase\StoreRequest;

// Models
use App\Models\Crm\CCaseStage;
use App\Models\Crm\CCaseNote;
use App\Models\Crm\CCase;
use App\Models\User;
// Event
use App\Events\CCaseEvent;

class CCaseController extends Controller
{

    public function index(Request $request)
    {
        $data = CCase::with(['cTypeCase', 'cCaseStages', 'cCaseArea','sClient', 'sPolicy'])
        ->with('sClient', function ($query) use($request) {
            if (isset($request->name)) {
                $query->where('first_name', 'like', '%' . $request->name . '%');
                $query->orWhere('last_name', 'like', '%' . $request->name . '%');
            }

            if (isset($request->identification)) {
                $query->where('identification', 'like', '%' . $request->identification . '%');
            }
        })
        ->with('cCaseStages', function ($query) use($request) {
            if (isset($request->status)) {
                $query->where('description',$request->status);
            }
        })
        ->where(function ($query) use ($request) {


            if (isset($request->case)) {
                $query->where('id', 'like', '%' . $request->case . '%');
            }

            if (isset($request->type_case)) {
                $query->where('c_type_case_id',$request->type_case);
            }

            if (isset($request->status)) {
                $query->where('status_case',$request->status);
            }


        })
        ->orderBy('status_case','ASC');

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
            $case = CCase::create($request->all());

            event(new CCaseEvent($case));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }
        $user = User::find($case->assigned_user_id, ['email']); //Traemos el email del usuario responsable

        //Creamos estructura del correo a enviar como notificacion
        $newCase = [
            'email' => $user->email,
            'case' => $case->id,
            'url' => 'https://beta.amauttasystems.com',
            'note' => $case->description,
            'creator_case' => $case->creator_name
        ];

            $idCRM = $newCase['case'];
            \Mail::send('emails.crm.responsible', $newCase, function ($message) use($user, $idCRM) {
            $message->from('noreply@amauttasystems.com', 'Quipus seguros');
            $message->to($user->email)->subject('Nuevo caso CRM, Id: '.$idCRM);
        });
        //Agregamos una nota automatica al caso con la descripcion del caso
        CCaseNote::create([
            "c_case_id" =>  $case->id,
            "user_id" =>  auth()->user()->id,
            "user_name" =>  auth()->user()->name,
            "user_email" =>  auth()->user()->email,
            "note" =>  "Requerimiento: " . $case->description,
            "type_note" =>  "Comentario",
            "end_date" =>  Carbon::now()->format('Y-m-d'),
            "state" =>  "Finalizada"
        ]);



        return response()->json($case);
    }

    public function update(UpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $case = CCase::findOrFail($id);
            if ($case) {
                $oldCase = $case->toArray();
            }

            $case->update($request->only([
                'risk',
                'expiration_date',
                'c_type_case_stage_id',
                'calification',
                'c_case_area_id',
                'assigned_user_id',
                'assigned_name',
                'status_case',
                'real_value',
                'closing_note'
            ]));

            event(new CCaseEvent($case));

            //Validamos si en el update del caso, cambiaron de responsable. De ser asi notifique
            if ($oldCase['assigned_user_id'] != $case->assigned_user_id) {

                $user = User::find($case->assigned_user_id, ['email']); //Traemos el email del usuario responsable

                $updateCase = [
                    'email' => $user->email,
                    'case' => $case->id,
                    'url' => 'https://beta.amauttasystems.com',
                    'note' => $case->description,
                    'creator_case' => $case->creator_name
                ];
                $idCRMUpdate = $updateCase['case'];
                \Mail::send('emails.crm.responsible', $updateCase, function ($message) use($user, $idCRMUpdate) {
                    $message->from('noreply@amauttasystems.com', 'Quipus seguros');
                    $message->to($user->email)->subject('Actualización caso CRM, Id: '.$idCRMUpdate);
                });
            }

            //En caso de cambiar la etapa del caso, crear una nota automatica.
            if ($oldCase['c_type_case_stage_id'] != $case->c_type_case_stage_id) {
                $stage = CCaseStage::find($case->c_type_case_stage_id);

                CCaseNote::create([
                    "c_case_id" =>  $case->id,
                    "user_id" =>  auth()->user()->id,
                    "user_name" =>  auth()->user()->name,
                    "user_email" =>  auth()->user()->email,
                    "note" =>  "Nueva etapa asignada: " . $stage->description,
                    "type_note" =>  "Comentario",
                    "end_date" =>  Carbon::now()->format('Y-m-d'),
                    "state" =>  "Finalizada"
                ]);
            }

            //En caso de insertar la nota de cierre crear un comentario.
            if ($oldCase['closing_note'] != $case->closing_note) {
                CCaseNote::create([
                    "c_case_id" =>  $case->id,
                    "user_id" =>  auth()->user()->id,
                    "user_name" =>  auth()->user()->name,
                    "user_email" =>  auth()->user()->email,
                    "note" =>  "Nota de Cierre: " . $case->closing_note,
                    "type_note" =>  "Comentario",
                    "end_date" =>  Carbon::now()->format('Y-m-d'),
                    "state" =>  "Finalizada"
                ]);
            }

            //En caso de cierre del caso, se finalizan las tareas.
            if ($case->status_case == 'Cerrado') {
                CCaseNote::where('c_case_id', $case->id)
                ->update(['state' => 'Finalizada']);
            }

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        return response()->json($case);
    }

    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $case = CCase::withTrashed()->findOrFail($id);

            if ($request->force) {
                $case->forceDelete();
            } else if ($case->trashed()) {
                $case->restore();
            } else {
                $case->delete();
            }

            event(new CCaseEvent($case));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        return response()->json($case);
    }
}
