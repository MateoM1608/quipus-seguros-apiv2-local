<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

// Models
use App\Models\Policy\SAnnex;

class IProduction extends Controller
{
    public function index(Request $request)
    {
        $current = SAnnex::where(function ($query) use ($request) {
            if ($request->date) {
                $query->whereRaw("DATE_FORMAT(s_annexes.annex_start, '%Y-%m') >= ?", Carbon::parse($request->date)->format('Y-01'))
                ->whereRaw("DATE_FORMAT(s_annexes.annex_start, '%Y-%m') <= ?", Carbon::parse($request->date)->format('Y-m'));
            }

            if ($request->annex_type) {
                $query->where("s_annexes.annex_type", $request->annex_type);
            }
        })
        ->groupBy("month")
        ->orderByRaw("DATE_FORMAT(s_annexes.annex_start, '%m') ASC");

        $last = SAnnex::where(function ($query) use ($request) {
            if ($request->date) {
                $query->whereRaw("DATE_FORMAT(s_annexes.annex_start, '%Y-%m') >= ?", Carbon::parse($request->date)->subYear()->format('Y-01'))
                ->whereRaw("DATE_FORMAT(s_annexes.annex_start, '%Y-%m') <= ?", Carbon::parse($request->date)->subYear()->format('Y-m'));
            }

            if ($request->annex_type) {
                $query->where("s_annexes.annex_type", $request->annex_type);
            }
        })
        ->groupBy("month")
        ->orderByRaw("DATE_FORMAT(s_annexes.annex_start, '%m') ASC");


        $fields = [
            DB::raw("MONTHNAME(s_annexes.annex_start) as month"),
            DB::raw("COALESCE(ROUND(SUM(s_annexes.annualized_premium)), 0) AS value"),
        ];

        $datos = [
            "currentYear" => $current->get($fields),
            "lastYear" => $last->get($fields),
        ];

        return response()->json($datos);
    }
}
