<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

//FormRequest
use App\Http\Requests\Policy\SInsuranceCarrier\UpdateRequest;
use App\Http\Requests\Policy\SInsuranceCarrier\StoreRequest;

//Models
use App\Models\Policy\SInsuranceCarrier;

// Events
use App\Events\SInsuranceCarrierEvent;


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
            $insuranceCarrier = SInsuranceCarrier::create($request->all());

            event(new SInsuranceCarrierEvent($insuranceCarrier));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        return response()->json($insuranceCarrier);
    }

    public function update(UpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $insuranceCarrier = SInsuranceCarrier::findOrFail($id);
            $insuranceCarrier->update($request->all());

            event(new SInsuranceCarrierEvent($insuranceCarrier));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

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

            event(new SInsuranceCarrierEvent($insuranceCarrier));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        return response()->json($insuranceCarrier);
    }
}
