<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

//FormRequest
use App\Http\Requests\Crm\CCaseStage\UpdateRequest;
use App\Http\Requests\Crm\CCaseStage\StoreRequest;

//Models
use App\Models\Crm\CCaseStage;

// Events
use App\Events\CCaseStageEvent;

class CCaseStageController extends Controller
{
    public function index(Request $request)
    {
        $data = CCaseStage::with(['cTypeCase'])
            ->where(function ($query) use ($request) {

                if (isset($request->type)) {
                    $query->where('c_type_case_id', $request->type);
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
            $stage = CCaseStage::create($request->all());
            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();

            event(new CCaseStageEvent($stage));

            return response()->json($e->getMessage(), 422);
        }

        return response()->json($stage);
    }

    public function update(UpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $stage = CCaseStage::findOrFail($id);
            $stage->update($request->all());

            event(new CCaseStageEvent($stage));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        return response()->json($stage);
    }

    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $stage = CCaseStage::withTrashed()->findOrFail($id);

            if ($request->force) {
                $stage->forceDelete();
            } else if ($stage->trashed()) {
                $stage->restore();
            } else {
                $stage->delete();
            }

            event(new CCaseStageEvent($stage));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        return response()->json($stage);
    }
}
