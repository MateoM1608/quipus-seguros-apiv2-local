<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//Models
use App\Models\Policy\SAnnex;

class RPortfolioController extends Controller
{
    public function index(Request $request)
    {
        $data = SAnnex::join('s_policies', 's_policies.id', 's_annexes.s_policy_id')
            ->join('s_clients', 's_clients.id',  's_policies.s_client_id')
            ->join('s_branches',  's_branches.id', 's_policies.s_branch_id')
            ->join('s_insurance_carriers', 's_insurance_carriers.id', 's_branches.s_insurance_carrier_id')
            ->leftJoin('s_payments', function ($query) {
                $query->whereNull('s_payments.deleted_at');
                $query->on('s_annexes.id', 's_payments.s_annex_id');
            })
            ->where(function ($query) use ($request) {
                $query->where('s_annexes.annex_paid', 'No');

                if (isset($request->s_client_id)) {
                    $query->where('s_clients.id', $request->s_client_id);
                }
            })
            ->orderBy('portfolio_days', 'DESC')
            ->groupBy('s_annexes.id')
            ->having('balance', '<>', 0);

        $response = [];

        $fields = [
            's_annexes.id',
            's_insurance_carriers.insurance_carrier',
            's_branches.id as s_branch_id',
            's_branches.name as branche',
            's_policies.policy_number',
            's_clients.id as s_client_id',
            \DB::raw('CONCAT(s_clients.first_name, " ", s_clients.last_name) AS client'),
            's_clients.identification',
            's_annexes.id as s_annexe_id',
            's_annexes.annex_total_value',
            's_annexes.annex_number',
            's_annexes.annex_type',
            's_annexes.annex_start',
            's_annexes.annex_expedition',
            's_branches.commission',
            's_branches.tax',
            's_branches.loss_coverage',
            's_branches.cancellation_risk',
            's_branches.cancellation',
            \DB::raw('IF(SUM(s_payments.total_value), s_annexes.annex_total_value - SUM(s_payments.total_value), s_annexes.annex_total_value) AS balance'),
            \DB::raw('IF(s_annexes.annex_start > s_annexes.annex_expedition, DATEDIFF(NOW(), s_annexes.annex_start), DATEDIFF(NOW(), s_annexes.annex_expedition)) AS portfolio_days'),
            \DB::raw('IF(s_annexes.annex_start > s_annexes.annex_expedition, s_branches.cancellation - DATEDIFF(NOW(), s_annexes.annex_start), s_branches.cancellation - DATEDIFF(NOW(), s_annexes.annex_expedition)) AS cancellation_days'),
            \DB::raw('IF(s_annexes.annex_start > s_annexes.annex_expedition, DATE_FORMAT(DATE_ADD(NOW(),INTERVAL s_branches.cancellation - DATEDIFF(NOW(), s_annexes.annex_start) DAY), "%Y-%m-%d"), DATE_FORMAT(DATE_ADD(NOW(),INTERVAL s_branches.cancellation - DATEDIFF(NOW(), s_annexes.annex_expedition) DAY), "%Y-%m-%d")) AS cancellation_date'),
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
