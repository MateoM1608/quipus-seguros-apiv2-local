<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

// FormRequest
use App\Http\Requests\Policy\SBranch\UpdateRequest;
use App\Http\Requests\Policy\SBranch\StoreRequest;

// Models
use App\Models\Policy\SBranch;

// Events
use App\Events\SBranchEvent;

class SBranchController extends Controller
{
    public function index(Request $request)
    {
        $data = SBranch::with(['sInsuranceCarrier' => function ($query) use ($request) {
            //
        }])
            ->where(function ($query) use ($request) {
                if (isset($request->branch)) {
                    $query->where('name', 'like', '%' . $request->branch . '%');
                }

                if (isset($request->s_insurance_carrier_id)) {
                    $query->where('s_insurance_carrier_id', $request->s_insurance_carrier_id);
                }
            })

            ->orderBy('s_insurance_carrier_id', 'ASC');

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

        return response()->json($response);
    }

    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $branch = SBranch::create($request->all());

            event(new SBranchEvent($branch));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        return response()->json($branch);
    }

    public function update(UpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $branch = SBranch::findOrFail($id);
            $branch->update($request->all());

            event(new SBranchEvent($branch));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        return response()->json($branch);
    }

    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $branch = SBranch::withTrashed()->with(['sInsuranceCarrier'])->findOrFail($id);

            if ($request->force) {
                $branch->forceDelete();
            } else if ($branch->trashed()) {
                $branch->restore();
            } else {
                $branch->delete();
            }

            event(new SBranchEvent($branch));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        return response()->json($branch);
    }
}
