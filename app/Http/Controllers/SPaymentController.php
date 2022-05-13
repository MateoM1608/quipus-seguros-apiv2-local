<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

// FormRequest
use App\Http\Requests\Policy\SPayment\UpdateRequest;
use App\Http\Requests\Policy\SPayment\StoreRequest;

// Models
use App\Models\Policy\SPayment;
use App\Models\Policy\SAnnex;

// Events
use App\Events\SPaymentEvent;

class SPaymentController extends Controller
{

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

        return response()->json($response);
    }

   public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $payment = SPayment::create($request->all());

            $total_payment = SPayment::where('s_annex_id', $request->s_annex_id)
            ->first([DB::raw('ROUND(SUM(total_value)) as total_value')])->total_value;

            $total_annex = SAnnex::where('id', $request->s_annex_id)
            ->first([DB::raw('ROUND(annex_total_value) AS annex_total_value')])->annex_total_value;

            if ($total_payment >= $total_annex) {
                SAnnex::where('id', $request->s_annex_id)
                ->update([
                    'annex_paid' => 'Si'
                ]);
            }

            event(new SPaymentEvent($payment));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        return response()->json($payment);
    }

    public function update(UpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $payment = SPayment::findOrFail($id);
            $payment->update($request->all());

            $total_payment = SPayment::where('s_annex_id', $payment->s_annex_id)
            ->first([DB::raw('ROUND(SUM(total_value)) as total_value')])->total_value;

            $total_annex = SAnnex::where('id', $payment->s_annex_id)
            ->first([DB::raw('ROUND(annex_total_value) AS annex_total_value')])->annex_total_value;

            $annex_paid = null;

            if ($total_payment >= $total_annex) {
                $annex_paid = 'Si';
            }

            if ($total_payment < $total_annex) {
                $annex_paid = 'No';
            }

            SAnnex::where('id', $payment->s_annex_id)
            ->update([
                'annex_paid' => $annex_paid
            ]);

            event(new SPaymentEvent($payment));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        return response()->json($payment);
    }

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

            event(new SPaymentEvent($payment));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        $total_payment = SPayment::where('s_annex_id', $payment->s_annex_id)
        ->first([DB::raw('ROUND(SUM(total_value)) as total_value')])->total_value;

        $total_annex = SAnnex::where('id', $payment->s_annex_id)
        ->first([DB::raw('ROUND(annex_total_value) AS annex_total_value')])->annex_total_value;

        if ($total_payment >= $total_annex) {
            $annex_paid = 'Si';
        }

        if ($total_payment < $total_annex) {
            $annex_paid = 'No';
        }

        SAnnex::where('id', $payment->s_annex_id)
        ->update([
            'annex_paid' => $annex_paid
        ]);

        return response()->json($payment);
    }
}
