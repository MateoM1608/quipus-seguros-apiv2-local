<?php

namespace App\Http\Controllers;

use App\Models\GCity;
use Illuminate\Http\Request;

class GCityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = GCity::withTrashed()
        ->with(['GCountry' => function ($query) use ($request) {  }])
        ->where(function ($query) use ($request) {

            if (isset($request->g_country_id)) {
                $query->where('g_country_id', 'like', '%' . $request->g_country_id . '%');
            }
        });
        $response = [];

        if (isset($request->paginate) && $request->paginate == 1) {
            $data = $data->paginate($request->rows ?: 25, $request->colums ? $request->colums : ['*']);
            $response = [
                'total' => $data->total(),
                'data'  => $data->toArray()['data']
            ];
        } else {
            $response = [
                'total' => $data->count(),
                'data'  => $data->get($request->colums ? $request->colums : ['*'])
            ];
        }

        return response()->json($response);
    }
}
