<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

// FormRequest
use App\Http\Requests\Policy\SRisk\UpdateRequest;
use App\Http\Requests\Policy\SRisk\StoreRequest;

// Models
use App\Models\Policy\SClaim;
use App\Models\Policy\SRisk;

// Events
use App\Events\SRiskEvent;

class SRiskController extends Controller
{
    public function index(Request $request)
    {
        $data = SRisk::with(['SPolicy' => function ($query) use ($request) {
        }])
            ->where(function ($query) use ($request) {

                if (isset($request->risk_description)) {
                    $query->where('risk_description', 'like', '%' . $request->risk_description . '%');
                }

                if (isset($request->s_policy_id)) {
                    $query->where('s_policy_id', $request->s_policy_id);
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
            $risk = SRisk::create($request->all());

            event(new SRiskEvent($risk));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        return response()->json($risk);
    }

    public function update(UpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $risk = SRisk::findOrFail($id);
            $risk->update($request->all());

            event(new SRiskEvent($risk));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        return response()->json($risk);
    }

    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $risk = SRisk::withTrashed()->findOrFail($id);

            if ($request->force) {
                $risk->forceDelete();
            } else if ($risk->trashed()) {
                $risk->restore();
            } else {
                $risk->delete();
            }

            event(new SRiskEvent($risk));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        return response()->json($risk);
    }
}
