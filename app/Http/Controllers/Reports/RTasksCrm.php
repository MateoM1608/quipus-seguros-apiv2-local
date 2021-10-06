<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\Controller;
use App\Models\Crm\CCaseNote;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class RTasksCrm extends Controller
{
    public function index(Request $request)
    {
        $data = CCaseNote::join('c_cases', 'c_cases.id', 'c_case_notes.c_case_id')
            ->join('c_type_cases', 'c_type_cases.id',  'c_cases.c_type_case_id')
            ->leftJoin('c_case_areas', 'c_case_areas.id',  'c_cases.c_case_area_id')
            ->where(function ($query) {
                $query->where('c_case_notes.state', 'Pendiente');
                $query->where('c_cases.assigned_user_id', auth()->user()->id);
            });

        $response = [];

        $fields = [
            's_agencies.agency_name',
            's_insurance_carriers.insurance_carrier',
            's_branches.name as branche',
            's_policies.policy_number',
            's_agencies.agency_commission',
            \DB::raw('CONCAT(s_clients.first_name, " ", s_clients.last_name) AS client'),
            's_clients.identification',
            's_annexes.id AS s_annex_id',
            's_annexes.annex_number',
            's_annexes.annex_type',
            's_annexes.annex_start',
            's_annexes.annex_expedition',
            's_annexes.annex_paid',
            's_annexes.commission_paid',
            's_annexes.annex_premium',
            's_annexes.annex_commission',
            's_annexes.annex_total_value',
            's_annexes.annex_commission',
            's_branches.commission AS commission_branches_percentage',
            's_branches.tax',
            's_branches.loss_coverage',
            's_branches.cancellation_risk',
            's_branches.cancellation',
            's_policies.g_vendor_id'

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
