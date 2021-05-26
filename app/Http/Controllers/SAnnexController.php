<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

// FormRequest
use App\Http\Requests\Policy\SAnnex\UpdateRequest;
use App\Http\Requests\Policy\SAnnex\StoreRequest;

// Models
use App\Models\Policy\SAnnex;

// Events
use App\Events\SAnnexEvent;

class SAnnexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = SAnnex::with(['SPolicy.sBranch', 'SPolicy.sAgency'])
            ->where(function ($query) use ($request) {

                if (isset($request->annex_description)) {
                    $query->where('annex_description', 'like', '%' . $request->annex_description . '%');
                }

                if (isset($request->s_policy_id)) {
                    $query->where('s_policy_id', $request->s_policy_id);
                }
            });

        if ($request->trashed) {
            $data->withTrashed();
        }
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


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $annex = SAnnex::create($request->all());

            event(new SAnnexEvent($annex));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        return response()->json($annex);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Policy\SAnnex  $sAnnex
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $annex = SAnnex::findOrFail($id);
            $annex->update($request->all());

            event(new SAnnexEvent($annex));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        return response()->json($annex);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Policy\SAnnex  $sAnnex
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $annex = SAnnex::withTrashed()->findOrFail($id);

            if ($request->force) {
                $annex->forceDelete();
            } else if ($annex->trashed()) {
                $annex->restore();
            } else {
                $annex->delete();
            }

            event(new SAnnexEvent($annex));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        return response()->json($annex);
    }
}
