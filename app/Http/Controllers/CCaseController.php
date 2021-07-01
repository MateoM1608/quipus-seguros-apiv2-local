<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

// FormRequest
use App\Http\Requests\Crm\CCase\UpdateRequest;
use App\Http\Requests\Crm\CCase\StoreRequest;

// Models
use App\Models\Crm\CCase;

// Event
use App\Events\CCaseEvent;

class CCaseController extends Controller
{

    public function index(Request $request)
    {
        $data = CCase::with(['cTypeCase', 'cCaseStages', 'sClient', 'sPolicy'])
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
            $case = CCase::create($request->all());

            event(new CCaseEvent($case));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        return response()->json($case);
    }

    public function update(UpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $case = CCase::findOrFail($id);
            $case->update($request->only(['risk','description','expiration_date', 'c_type_case_stage_id', 'calification']));

            event(new CCaseEvent($case));

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
