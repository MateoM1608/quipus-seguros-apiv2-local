<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Config;
use DB;

//FormRequest
use App\Http\Requests\Policy\SPolicy\UpdateRequest;
use App\Http\Requests\Policy\SPolicy\StoreRequest;

//Models
use App\Models\Policy\SPolicy;

// Events
use App\Events\SPolicyEvent;



class SPolicyController extends Controller
{
    public function index(Request $request)
    {
        $data = SPolicy::withTrashed()->with([
            'sClient.gIdentificationType',
            'sClient.gCity','sAgency',
            'gVendor',
            'gVendor.gIdentificationType',
            'sBranch',
            'sBranch.sInsuranceCarrier',
            'sRisk',
            'sClaims',
            'sAnnex'
        ])
        ->leftJoin('s_risks','s_policies.id','s_risks.s_policy_id')
        ->where(function ($query) use ($request) {

            if (isset($request->policyNumber)) {
                $query->where('policy_number', 'like', '%' . $request->policyNumber . '%');
            }

            if (isset($request->s_client_id)) {
                $query->where('s_client_id', $request->s_client_id);
            }

            $query->whereNull('s_risks.deleted_at');
        })
        ->groupBy('s_policies.id');


        $response = [];

        $colums = [
            's_policies.*',
            DB::raw('IF(COUNT(s_risks.id) > 1, "MULTIRIESGO", s_risks.risk_description) AS type_risk'),
            DB::raw('(SELECT MAX(annex_end) FROM s_annexes WHERE s_annexes.s_policy_id = s_policies.id AND s_annexes.deleted_at IS NULL) AS end_term'),
        ];

        if (isset($request->paginate) && $request->paginate == 1) {
            $data = $data->paginate($request->rows ?: 25, $colums);
            $response = [
                'total' => $data->total(),
                'data'  => $data->toArray()['data']
            ];
        } else {
            $response = [
                'total' => $data->count(),
                'data'  => $data->get($colums)
            ];
        }

        return response()->json($response);
    }

    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $policy = SPolicy::create($request->all());

            event(new SPolicyEvent($policy));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }


        return response()->json($policy);
    }

    public function update(UpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $policy = SPolicy::findOrFail($id);
            $policy->update($request->all());

            event(new SPolicyEvent($policy));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

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

            event(new SPolicyEvent($policy));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        return response()->json($policy);
    }
}
