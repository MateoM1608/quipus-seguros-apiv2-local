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
        ->where(function ($query) use($request) {
            $query->where('c_case_notes.state', 'Pendiente');
            if ($request->user_id) {
                $query->where('c_cases.assigned_user_id', $request->user_id);
            }
        });

        $response = [];

        $fields = [
            'c_cases.c_case_area_id',
            \DB::raw('c_case_areas.description AS area'),
            'c_type_cases.description AS type_case',
            'c_case_notes.*'

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
