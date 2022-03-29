<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

// Models
use App\Models\Policy\SAnnex;

class RProduction extends Controller
{
    public function index(Request $request)
    {
        $data = SAnnex::join('s_policies', 's_annexes.s_policy_id', 's_policies.id')
            ->join('s_branches', 's_policies.s_branch_id', 's_branches.id')
            ->join('s_insurance_carriers', 's_branches.s_insurance_carrier_id', 's_insurance_carriers.id')
            ->join('g_vendors', 's_policies.g_vendor_id', 'g_vendors.id')
            ->join('s_agencies', 's_policies.s_agency_id', 's_agencies.id')
            ->where(function ($query) use ($request) {

                if ($request->date_start) {
                    $query->where('s_annexes.annex_start', '>=', $request->date_start);
                }

                if ($request->date_end) {
                    $query->whereRaw('DATE_FORMAT(s_annexes.annex_start, "%Y-%m-%d") <= ? ', $request->date_end);
                }

                if ($request->annex_type) {
                    $query->where('s_annexes.annex_type', $request->annex_type);
                }
            });

        if ($request->group) {
            $data = $data->groupBy($request->group);
        }

        $fields = [
            DB::raw('CONCAT(g_vendors.first_name," ",g_vendors.last_name) AS seller'),
            's_agencies.agency_name AS agencie',
            's_insurance_carriers.insurance_carrier',
            's_insurance_carriers.insurance_carrier',
            's_branches.name as branche',
            DB::raw('COALESCE(sum(s_annexes.annualized_premium),0) AS total'),
        ];

        if (isset($request->paginate) && $request->paginate == 1) {
            $data = $data->paginate($request->rows ?: 25, $fields);
            $response = [
                'total' => $data->total(),
                'data'  => $data->toArray()['data']
            ];
        } else {
            $datos = $data->get($fields);
            $response = [
                'total' => $datos->count(),
                'data'  => $datos,
            ];
        }

        return response()->json($response);
    }
}
