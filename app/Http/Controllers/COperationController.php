<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

// FormRequest
use App\Http\Requests\Crm\COperation\UpdateRequest;
use App\Http\Requests\Crm\COperation\StoreRequest;

// Models
use App\Models\Crm\COperation;

// Events
use App\Events\COperationEvent;

class COperationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = COperation::withTrashed()
        ->with(['COperationType' => function ($query) use ($request) {  }])
        ->where(function ($query) use ($request) {

            if (isset($request->operation_description)) {
                $query->where('operation_description', 'like', '%' . $request->operation_description . '%');
            }
        });
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $operation = COperation::create($request->all());

            event(new COperationEvent($operation));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        return response()->json($operation);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Crm\COperation  $cOperation
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $operation = COperation::findOrFail($id);
            $operation->update($request->all());

            event(new COperationEvent($operation));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        return response()->json($operation);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Crm\COperation  $cOperation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $operation = COperation::withTrashed()->findOrFail($id);

            if ($request->force) {
                $operation->forceDelete();
            } else if ($operation->trashed()) {
                $operation->restore();
            } else {
                $operation->delete();
            }

            event(new COperationEvent($operation));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        return response()->json($operation);
    }
}
