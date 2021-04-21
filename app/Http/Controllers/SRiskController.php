<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use DB;

//FormRequest
use App\Http\Requests\Policy\SRisk\UpdateRequest;
use App\Http\Requests\Policy\SRisk\StoreRequest;

//Models
use App\Models\Policy\SClaim;
use App\Models\Policy\SRisk;

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

        //dd(\DB::getQueryLog());

        return response()->json($response);
    }

    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $risk = SRisk::create($request->all());
            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        $redis = Redis::connection();
        $redis->publish('channel-vue-' . auth()->guard('api')->user()->id, json_encode([
            'evento' => 'RISK',
            'datos' => $risk
        ]));

        return response()->json($risk);
    }

    public function update(UpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $risk = SRisk::findOrFail($id);
            $risk->update($request->all());
            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        $redis = Redis::connection();
        $redis->publish('channel-vue-' . auth()->guard('api')->user()->id, json_encode([
            'evento' => 'RISK',
            'datos' => $risk
        ]));

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

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        $redis = Redis::connection();
        $redis->publish('channel-vue-' . auth()->guard('api')->user()->id, json_encode([
            'evento' => 'RISK',
            'datos' => $risk
        ]));

        return response()->json($risk);
    }
}
