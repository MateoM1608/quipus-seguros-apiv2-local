<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use DB;

//FormRequest
use App\Http\Requests\Policy\SPolicy\UpdateRequest;
use App\Http\Requests\Policy\SPolicy\StoreRequest;

//Models
use App\Models\Policy\SPolicy;


class SPolicyController extends Controller
{
    public function index(Request $request)
    {
        $data = SPolicy::withTrashed()
            ->with(['sClient.gIdentificationType' => function ($query) use ($request) {
                //
            }])
            ->with(['sClient.gCity' => function ($query) use ($request) {
                //
            }])

            ->with(['sAgency' => function ($query) use ($request) {
                //
            }])

            ->with(['gVendor' => function ($query) use ($request) {
                //
            }])
            ->with(['gVendor.gIdentificationType' => function ($query) use ($request) {
                //
            }])

            ->with(['sBranch' => function ($query) use ($request) {
                //
            }])
            ->with(['sBranch.sInsuranceCarrier' => function ($query) use ($request) {
                //
            }])

            ->with(['sRisk' => function ($query) use ($request) {
                //
            }])

            ->with(['sClaims' => function ($query) use ($request) {
                //
            }])

            ->with(['sAnnex' => function ($query) use ($request) {
                //
            }])

            ->where(function ($query) use ($request) {

                if (isset($request->policyNumber)) {
                    $query->where('policy_number', 'like', '%' . $request->policyNumber . '%');
                }

                if (isset($request->s_client_id)) {
                    $query->where('s_client_id', $request->s_client_id);
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

    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $policy = SPolicy::create($request->all());
            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        $redis = Redis::connection();
        $redis->publish('channel-vue-' . auth()->guard('api')->user()->id, json_encode([
            'evento' => 'POLICY',
            'datos' => $policy
        ]));

        return response()->json($policy);
    }

    public function update(UpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $policy = SPolicy::findOrFail($id);
            $policy->update($request->all());
            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        $redis = Redis::connection();
        $redis->publish('channel-vue-' . auth()->guard('api')->user()->id, json_encode([
            'evento' => 'POLICY',
            'datos' => $policy
        ]));

        return response()->json($policy);
    }

    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $policy = SPolicy::withTrashed()->findOrFail($id);

            if ($policy->trashed()) {
                $policy->restore();
            } elseif ($request->force) {
                $policy->forceDelete();
            } else {
                $policy->delete();
            }

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        $redis = Redis::connection();
        $redis->publish('channel-vue-' . auth()->guard('api')->user()->id, json_encode([
            'evento' => 'POLICY',
            'datos' => $policy
        ]));

        return response()->json($policy);
    }
}
