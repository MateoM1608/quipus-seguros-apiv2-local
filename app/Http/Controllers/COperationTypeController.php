<?php

namespace App\Http\Controllers;

use App\Models\Crm\COperationType;
use Illuminate\Http\Request;

class COperationTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = COperationType::withTrashed();
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

        //dd(\DB::getQueryLog());

        return response()->json($response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Crm\COperationType  $cOperationType
     * @return \Illuminate\Http\Response
     */
    public function show(COperationType $cOperationType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Crm\COperationType  $cOperationType
     * @return \Illuminate\Http\Response
     */
    public function edit(COperationType $cOperationType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Crm\COperationType  $cOperationType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, COperationType $cOperationType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Crm\COperationType  $cOperationType
     * @return \Illuminate\Http\Response
     */
    public function destroy(COperationType $cOperationType)
    {
        //
    }
}
