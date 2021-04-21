<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use DB;

//FormRequest
use App\Http\Requests\Crm\CCase\UpdateRequest;
use App\Http\Requests\Crm\CCase\StoreRequest;

//Models
use App\Models\Crm\CCase;

class CCaseController extends Controller
{

    public function index(Request $request)
    {
        //\DB::enableQueryLog();
        $data = CCase::with(['cTypeCase', 'cCaseStages', 'sClient', 'sPolicy'])
            ->whereHas('sClient', function ($query) use($request) {
                if (isset($request->name)) {
                    $query->where('first_name', 'like', '%' . $request->name . '%');
                    $query->orWhere('last_name', 'like', '%' . $request->name . '%');
                }

                if (isset($request->identification)) {
                    $query->where('identification', 'like', '%' . $request->identification . '%');
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

        //dd(\DB::getQueryLog());

        return response()->json($response);
    }

    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $case = CCase::create($request->all());
            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        $redis = Redis::connection();
        $redis->publish('channel-vue-' . auth()->guard('api')->user()->id, json_encode([
            'evento' => 'CASE',
            'datos' => $case
        ]));

        return response()->json($case);
    }

    public function update(UpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $case = CCase::findOrFail($id);
            $case->update($request->all());
            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        $redis = Redis::connection();
        $redis->publish('channel-vue-' . auth()->guard('api')->user()->id, json_encode([
            'evento' => 'CASE',
            'datos' => $case
        ]));

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

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        $redis = Redis::connection();
        $redis->publish('channel-vue-' . auth()->guard('api')->user()->id, json_encode([
            'evento' => 'CASE',
            'datos' => $case
        ]));

        return response()->json($case);
    }
}
