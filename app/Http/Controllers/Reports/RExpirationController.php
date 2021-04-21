<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

//Models
use App\Models\Policy\SAnnex;
use App\Models\Policy\SPolicy;

class RExpirationController extends Controller
{
    /**
     * Informe de Vencimientos*
     */
    public function index(Request $request)
    {
        //\DB::enableQueryLog();
        $data = SPolicy::join('s_annexes', 's_annexes.s_policy_id','s_policies.id')
            ->join('s_clients', 's_clients.id',  's_policies.s_client_id')
            ->join('s_branches',  's_branches.id', 's_policies.s_branch_id')
            ->join('s_insurance_carriers', 's_insurance_carriers.id', 's_branches.s_insurance_carrier_id')
            ->where(function ($query) use ($request) {
                $query->where('policy_state', '=', 'Vigente');
                $query->whereNotIn('s_annexes.annex_type', ['Cobro','Cancelación','Devolución']);

                if (isset($request->end_date)) {
                    $query->whereRaw('DATE_FORMAT(annex_end, "%m") = "' . explode('-', $request->end_date)[1] . '"');
                    #$query->orWhereRaw('DATE_FORMAT(annex_end, "%Y-%m") = "' . Carbon::parse($request->end_date)->addYear(1)->format('Y-m') . '"  ');
                    #$query->orWhereRaw('DATE_FORMAT(annex_end, "%Y-%m") = "' . Carbon::parse($request->end_date)->addYear(1)->format('Y-m') . '"  ');
                }
            })
            ->groupBy('policy_number')
            ->orderByRaw('annex_type,s_client_id DESC');

        $response = [];

        $fields = [
            's_policies.id',
            's_branches.s_insurance_carrier_id',
            's_insurance_carriers.insurance_carrier',
            's_branches.name',
            's_branches.tax as tax',
            's_branches.commission as commission',
            's_annexes.s_policy_id',
            's_policies.policy_number',
            's_policies.policy_state',
            's_annexes.annex_number',
            DB::raw("(SELECT s_annexes.annex_type FROM s_annexes WHERE s_annexes.s_policy_id = s_policies.id AND s_annexes.annex_type NOT IN('Cobro','Cancelación','Devolución') ORDER BY annex_end DESC LIMIT 1) AS annex_type"),
            's_policies.s_client_id',
            's_clients.identification',
            DB::raw('CONCAT(s_clients.first_name, " ", s_clients.last_name) AS client'),
            's_annexes.id as s_annexe_id',
            's_annexes.annex_start',
            DB::raw('(SELECT s_annexes.annex_end FROM s_annexes WHERE s_annexes.s_policy_id = s_policies.id ORDER BY annex_end DESC LIMIT 1) AS annex_end'),
            's_annexes.annex_print',
            's_annexes.annex_printed',
            's_annexes.annex_email',
            's_annexes.annex_delivered'
        ];

        if (isset($request->paginate) && $request->paginate == 1) {
            $data = $data->paginate($request->rows ?: 25, $fields);
            $response = [
                'total' => $data->total(),
                'data'  => $data->toArray()['data']
            ];
        } else {
            $response = [
                'total' => $data->count(),
                'data'  => $data->get($fields)
            ];
        }

        //dd(\DB::getQueryLog());

        return response()->json($response);
    }
}
