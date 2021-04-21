<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use DB;

//FormRequest
use App\Http\Requests\Policy\SInsuranceCarrier\UpdateRequest;
use App\Http\Requests\Policy\SInsuranceCarrier\StoreRequest;

//Models
use App\Models\Policy\SInsuranceCarrier;


class SInsuranceCarrierController extends Controller
{
    public function index(Request $request)
    {
        $data = SInsuranceCarrier::where(function ($query) use ($request) {

            if (isset($request->insurance_carrier)) {
                $query->where('insurance_carrier', $request->insurance_carrier);
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
            $insurance = SInsuranceCarrier::create($request->all());
            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        $redis = Redis::connection();
        $redis->publish('channel-vue-' . auth()->guard('api')->user()->id, json_encode([
            'evento' => 'INSURANCE',
            'datos' => $insurance
        ]));

        return response()->json($insurance);
    }

    public function update(UpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $insuranceCarrier = SInsuranceCarrier::findOrFail($id);
            $insuranceCarrier->update($request->all());
            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        $redis = Redis::connection();
        $redis->publish('channel-vue-' . auth()->guard('api')->user()->id, json_encode([
            'evento' => 'INSURANCE',
            'datos' => $insuranceCarrier
        ]));

        return response()->json($insuranceCarrier);
    }

    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $insuranceCarrier = SInsuranceCarrier::withTrashed()->findOrFail($id);

            if ($request->force) {
                $insuranceCarrier->forceDelete();
            } else if ($insuranceCarrier->trashed()) {
                $insuranceCarrier->restore();
            } else {
                $insuranceCarrier->delete();
            }

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        $redis = Redis::connection();
        $redis->publish('channel-vue-' . auth()->guard('api')->user()->id, json_encode([
            'evento' => 'INSURANCE',
            'datos' => $insuranceCarrier
        ]));

        return response()->json($insuranceCarrier);
    }
}
