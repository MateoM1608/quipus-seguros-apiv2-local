<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use DB;

//FormRequest
use App\Http\Requests\Policy\GVendor\UpdateRequest;
use App\Http\Requests\Policy\GVendor\StoreRequest;

//Models
use App\Models\Policy\GVendor;

class GVendorController extends Controller
{

    public function index(Request $request)
    {
        //\DB::enableQueryLog();
        $data = GVendor::with(['gIdentificationType' => function ($query) use ($request) {
            //
        }])
            ->where(function ($query) use ($request) {

                if (isset($request->name)) {
                    $query->where('first_name', 'like', '%' . $request->name . '%');
                    $query->orWhere('last_name', 'like', '%' . $request->name . '%');
                }

                if (isset($request->identification)) {
                    $query->where('identification', 'like', '%' . $request->identification . '%');
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
            $vendor = GVendor::create($request->all());
            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        /*$redis = Redis::connection();
        $redis->publish('channel-vue-' . auth()->guard('api')->user()->id, json_encode([
            'evento' => 'VENDOR',
            'datos' => $vendor
        ]));*/

        return response()->json($vendor);
    }

    public function update(UpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $vendor = GVendor::findOrFail($id);
            $vendor->update($request->all());
            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        /*$redis = Redis::connection();
        $redis->publish('channel-vue-' . auth()->guard('api')->user()->id, json_encode([
            'evento' => 'VENDOR',
            'datos' => $vendor
        ]));*/

        return response()->json($vendor);
    }

    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $vendor = GVendor::withTrashed()->findOrFail($id);

            if ($request->force) {
                $vendor->forceDelete();
            } else if ($vendor->trashed()) {
                $vendor->restore();
            } else {
                $vendor->delete();
            }

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        /*$redis = Redis::connection();
        $redis->publish('channel-vue-' . auth()->guard('api')->user()->id, json_encode([
            'evento' => 'VENDOR',
            'datos' => $vendor
        ]));*/

        return response()->json($vendor);
    }
}
