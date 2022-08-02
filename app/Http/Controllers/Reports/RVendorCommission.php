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
        \DB::enableQueryLog();
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

                if (isset($request->commissionStatus) && $request->commissionStatus == 1) { //1 = Muestre las comisiones pagadas del asesor
                    $query->whereNull('s_commissions.deleted_at');
                    $query->where('s_annexes.commission_paid', 'Si');
                    $query->where('s_commissions.vendor_commission_paid', 'Si');
                    //$query->where('s_commissions.commission_value', '>', 0);
                } else{
                    $query->whereNull('s_commissions.deleted_at'); //Muestreme las comisiones sin pagar para el asesor
                    $query->where('s_annexes.commission_paid', 'Si');
                    $query->where('s_commissions.vendor_commission_paid', 'No');
                    //$query->where('s_commissions.commission_value', '>', 0);
                }

                $query->where('s_commissions.id', 1);
            });

            $response = [];

        $fields = [
            's_annexes.id',
            's_policies.s_client_id AS client_id',
            's_policies.g_vendor_id AS vendor_id',
            's_policies.s_branch_id AS branch_id',
            's_commissions.id AS comission_id',
            's_annexes.id AS annex_id',
            's_annexes.s_policy_id AS policy_id',
            's_agencies.id AS agency_id',
            's_policies.policy_number',
            's_policies.policy_state',
            's_branches.name AS branchName',
            's_branches.commission as branchCommission',
            's_branches.tax',
            's_clients.identification AS clientIdentification',
            's_clients.first_name AS clientName',
            's_clients.last_name AS clientLastName',
            'g_vendors.identification AS vendorIdentification',
            'g_vendors.first_name AS vendorName',
            'g_vendors.last_name AS vendorLastName',
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
            's_commissions.payment_day',
            's_commissions.status_payment',
            \DB::raw('ROUND((s_commissions.commission_value),0) AS commission_value'),
            's_commissions.vendor_commission_paid',
            'g_vendors.commission AS percVendorCommission',
            \DB::raw('ROUND((s_commissions.commission_value * (g_vendors.commission/100)),0) AS vendorCommissionValue')

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

        //dd(\DB::getQueryLog());

        return response()->json($response);
    }

}
