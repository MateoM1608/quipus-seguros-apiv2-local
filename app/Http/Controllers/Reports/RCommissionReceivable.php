<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

//Models
use App\Models\Policy\SAnnex;

class RCommissionReceivable extends Controller
{
    public function index(Request $request)
    {
        $data = SAnnex::join('s_policies', 's_policies.id', 's_annexes.s_policy_id')
            ->join('s_clients', 's_clients.id',  's_policies.s_client_id')
            ->join('s_agencies', 's_policies.s_agency_id',  's_agencies.id')
            ->join('s_branches',  's_branches.id', 's_policies.s_branch_id')
            ->join('s_insurance_carriers', 's_insurance_carriers.id', 's_branches.s_insurance_carrier_id')
            ->leftJoin('s_payments', function ($query) {
                $query->whereNull('s_payments.deleted_at');
                $query->on('s_annexes.id', 's_payments.s_annex_id');
            })
            ->where(function ($query) use ($request) {
                $query->where('s_annexes.annex_paid', 'Si');
                $query->where('s_annexes.commission_paid', 'No');
            })
            ->groupBy('s_annexes.id');

        $response = [];

        $fields = [
            's_annexes.id',
            's_policies.id as s_policy_id',
            's_branches.s_insurance_carrier_id',
            's_branches.id as s_branch_id',
            's_policies.s_client_id',
            's_policies.g_vendor_id',
            's_annexes.id AS s_annex_id',
            's_agencies.agency_name',
            's_insurance_carriers.insurance_carrier',
            's_branches.name as branche',
            's_policies.policy_number',
            's_agencies.agency_commission',
            \DB::raw('CONCAT(s_clients.first_name, " ", s_clients.last_name) AS client'),
            's_clients.identification',
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
