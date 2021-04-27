<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use DB;

use App\Http\Requests\Policy\SPayment\UpdateRequest;
use App\Http\Requests\Policy\SPayment\StoreRequest;

use App\Models\Policy\SPayment;

class SPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = SPayment::with(['SAnnex' => function ($query) use ($request) {
        }])
            ->where(function ($query) use ($request) {

                if (isset($request->s_annex_id)) {
                    $query->where('s_annex_id', $request->s_annex_id);
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

        if ($request->trashed) {
            $data->withTrashed();
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
            $payment = SPayment::create($request->all());
            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        $redis = Redis::connection();
        $redis->publish('channel-vue-' . auth()->guard('api')->user()->id, json_encode([
            'evento' => 'PAYMENT',
            'datos' => $payment
        ]));

        return response()->json($payment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Policy\SPayment  $sPayment
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $payment = SPayment::findOrFail($id);
            $payment->update($request->all());
            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        $redis = Redis::connection();
        $redis->publish('channel-vue-' . auth()->guard('api')->user()->id, json_encode([
            'evento' => 'PAYMENT',
            'datos' => $payment
        ]));

        return response()->json($payment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Policy\SPayment  $sPayment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $payment = SPayment::withTrashed()->findOrFail($id);

            if ($request->force) {
                $payment->forceDelete();
            } else if ($payment->trashed()) {
                $payment->restore();
            } else {
                $payment->delete();
            }

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        $redis = Redis::connection();
        $redis->publish('channel-vue-' . auth()->guard('api')->user()->id, json_encode([
            'evento' => 'PAYMENT',
            'datos' => $payment
        ]));

        return response()->json($payment);
    }
}
