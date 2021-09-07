<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\Controller;
use App\Models\Policy\SPolicy;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;


class RVendorCommission extends Controller
{

    public function index(Request $request)
    {
        #\DB::enableQueryLog();
        $data = SPolicy::join('s_branches', 's_branches.id', 's_policies.s_branch_id')
            ->join('s_clients', 's_clients.id',  's_policies.s_client_id')
            ->join('g_vendors', 'g_vendors.id',  's_policies.g_vendor_id')
            ->join('s_agencies',  's_agencies.id', 's_policies.s_agency_id')
            ->join('s_annexes', 's_annexes.s_policy_id', 's_policies.id')
            ->join('s_commissions', function ($query) {
                $query->on('s_commissions.s_annex_id', 's_annexes.id')
                ->on('s_commissions.g_vendor_id', 'g_vendors.id');
            })

            ->where(function ($query) use ($request) {

                if (isset($request->commissionStatus) && $request->commissionStatus == 1) {

                    $query->whereNull('s_commissions.deleted_at');
                    $query->where('s_annexes.commission_paid', 'Si');
                    $query->where('s_commissions.vendor_commission_paid', 'Si');
                    $query->where('s_commissions.commission_value', '>', 0);
                }
                elseif(isset($request->commissionStatus) && $request->commissionStatus == 2){

                    $query->whereNull('s_commissions.deleted_at');
                    $query->where('s_annexes.commission_paid', 'Si');
                    $query->where('s_commissions.vendor_commission_paid', 'No');
                    $query->where('s_commissions.commission_value', '>', 0);
                }
                else{

                    $query->whereNull('s_commissions.deleted_at');
                    $query->where('s_annexes.commission_paid', 'Si');
                    $query->where('s_commissions.vendor_commission_paid', 'No');
                    $query->where('s_commissions.commission_value', '>', 0);
                }
            });

            $response = [];

        $fields = [
            's_policies.s_client_id AS client_id',
            's_policies.g_vendor_id AS vendor_id',
            's_policies.s_branch_id AS branch_id',
            's_commissions.id AS comission_id',
            's_annexes.id AS annex_id',
            's_annexes.s_policy_id AS policy_id',
            's_agencies.id AS agency_id',
            's_policies.policy_number',
            's_policies.policy_state',
            's_branches.name',
            's_branches.commission',
            's_branches.tax',
            's_clients.identification AS identificacion_cliente',
            's_clients.first_name AS nombre_cliente',
            's_clients.last_name AS apellido_cliente',
            'g_vendors.identification AS identificacion_asesor',
            'g_vendors.first_name AS nombre_asesor',
            'g_vendors.last_name AS apellido_asesor',
            'g_vendors.commission AS porc_asesor_comision',
            's_agencies.agency_name',
            's_annexes.annex_number',
            's_annexes.annex_start',
            's_annexes.annex_end',
            's_annexes.annex_premium',
            's_annexes.annex_tax',
            's_annexes.annex_total_value',
            's_annexes.annex_description',
            's_annexes.annex_commission',
            's_annexes.annex_paid',
            's_annexes.commission_paid',
            's_annexes.annex_type',
            's_commissions.commission_number',
            's_commissions.commission_date',
            's_commissions.commission_value',
            's_commissions.vendor_commission_paid'

        ];

        if (isset($request->paginate) && $request->paginate == 1) {
            $data = $data->paginate($request->rows ?: 25, $fields);
            $response = [
                'total' => $data->total(),
                'data'  => $data->toArray()['data']
            ];
        } else {
            $data = $data->get($fields);
            $response = [
                'total' => $data->count(),
                'data'  => $data,
            ];
        }

        #dd(\DB::getQueryLog());

        return response()->json($response);
    }

}
