<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use DB;

//FormRequest
use App\Http\Requests\Policy\SClaim\UpdateRequest;
use App\Http\Requests\Policy\SClaim\StoreRequest;

//Models
use App\Models\Policy\SClaim;

class SClaimController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = SClaim::with(['SPolicy' => function ($query) use ($request) {
        }])
            ->where(function ($query) use ($request) {

                if (isset($request->claim_description)) {
                    $query->where('claim_description', 'like', '%' . $request->claim_description . '%');
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
            $claim = SClaim::create($request->all());
            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        $redis = Redis::connection();
        $redis->publish('channel-vue-' . auth()->guard('api')->user()->id, json_encode([
            'evento' => 'CLAIM',
            'datos' => $claim
        ]));

        return response()->json($claim);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SClaim  $sClaim
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $claim = SClaim::findOrFail($id);
            $claim->update($request->all());
            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        $redis = Redis::connection();
        $redis->publish('channel-vue-' . auth()->guard('api')->user()->id, json_encode([
            'evento' => 'CLAIM',
            'datos' => $claim
        ]));

        return response()->json($claim);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Policy\SClaim  $sClaim
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $claim = SClaim::withTrashed()->findOrFail($id);

            if ($request->force) {
                $claim->forceDelete();
            } else if ($claim->trashed()) {
                $claim->restore();
            } else {
                $claim->delete();
            }

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        $redis = Redis::connection();
        $redis->publish('channel-vue-' . auth()->guard('api')->user()->id, json_encode([
            'evento' => 'CLAIM',
            'datos' => $claim
        ]));

        return response()->json($claim);
    }
}
