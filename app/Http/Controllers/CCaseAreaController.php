<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

//FormRequest
use App\Http\Requests\Crm\CCaseArea\UpdateRequest;
use App\Http\Requests\Crm\CCaseArea\StoreRequest;

//Models
use App\Models\Crm\CCaseArea;

// Events
use App\Events\CCaseAreaEvent;

class CCaseAreaController extends Controller
{
    public function index(Request $request)
    {
        $data = CCaseArea::all();
        return response()->json($data);
    }

    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $areas = CCaseArea::create($request->all());

            event(new CCaseAreaEvent($areas));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        return response()->json($areas);
    }

    public function update(UpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $areas = CCaseArea::findOrFail($id);
            $areas->update($request->all());

            event(new CCaseAreaEvent($areas));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        return response()->json($areas);
    }

    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $areas = CCaseArea::withTrashed()->findOrFail($id);

            if ($request->force) {
                $areas->forceDelete();
            } else if ($areas->trashed()) {
                $areas->restore();
            } else {
                $areas->delete();
            }

            event(new CCaseAreaEvent($areas));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        return response()->json($areas);
    }
}
