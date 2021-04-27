<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use DB;

//FormRequest
use App\Http\Requests\Policy\SAgency\DeleteRequest;
use App\Http\Requests\Policy\SAgency\UpdateRequest;
use App\Http\Requests\Policy\SAgency\StoreRequest;

//Models
use App\Models\Policy\SAgency;

class SAgencyController extends Controller
{
    public function index(Request $request)
    {
        $data = SAgency::where(function ($query) use ($request) {

            if (isset($request->name)) {
                $query->where('agency_name', $request->name);
            }

            if (isset($request->identification)) {
                $query->where('identification', $request->identification);
            }
        });

        if ($request->trashed) {
            $data->withTrashed();
        }

        $response = [];

        if (isset($request->paginate) && $request->paginate == 1) {
            $data = $data->paginate($request->rows ?: 25, $request->columns ? $request->columns : ['*']);
            $response = [
                'total' => $data->total(),
                'data'  => $data->toArray()['data']
            ];
        } else {
            $response = [
                'total' => $data->count(),
                'data'  => $data->get($request->columns ? $request->columns : ['*'])
            ];
        }

        return response()->json($response);
    }

    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $agency = SAgency::create($request->all());
            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        $redis = Redis::connection();
        $redis->publish('channel-vue-' . auth()->guard('api')->user()->id, json_encode([
            'evento' => 'AGENCY',
            'datos' => $agency
        ]));

        return response()->json($agency);
    }

    public function update(UpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $agency = SAgency::findOrFail($id);
            $agency->update($request->all());
            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        $redis = Redis::connection();
        $redis->publish('channel-vue-' . auth()->guard('api')->user()->id, json_encode([
            'evento' => 'AGENCY',
            'datos' => $agency
        ]));

        return response()->json($agency);
    }

    public function destroy(DeleteRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $agency = SAgency::withTrashed()->findOrFail($id);

            if ($agency->trashed()) {
                $agency->restore();
            } elseif ($request->force) {
                $agency->forceDelete();
            } else {
                $agency->delete();
            }

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        $agency->force = $request->force;

        $redis = Redis::connection();
        $redis->publish('channel-vue-' . auth()->guard('api')->user()->id, json_encode([
            'evento' => 'AGENCY',
            'datos' => $agency
        ]));

        return response()->json($agency);
    }
}
