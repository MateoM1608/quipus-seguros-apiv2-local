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

// Events
use App\Events\CCaseNoteEvent;

class CCaseNoteController extends Controller
{
    public function index(Request $request)
    {
        $data = CCase::with(['cCaseNote'])
            ->whereHas('cCaseNote', function ($query) use($request) {

                if (isset($request->case_note)) {
                    $query->where('c_case_id',$request->case_note);
                }
            })
            ->where(function ($query) use ($request) {

                if (isset($request->case)) {
                    $query->where('id', 'like', '%' . $request->case . '%');
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

        return response()->json($note);
    }

    public function update(UpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $note = CCaseNote::find($id);
            $note->update($request->all());

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
