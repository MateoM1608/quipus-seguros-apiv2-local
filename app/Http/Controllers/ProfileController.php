<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

// Models
use App\Models\Profile;

// FormRequest
use App\Http\Requests\Profile\UpdateRequest;
use App\Http\Requests\Profile\StoreRequest;

// Events
use App\Events\ProfileEvent;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $data = Profile::where(function ($query) use ($request) {
            if (isset($request->description)) {
                $query->where('description', $request->description);
            }
        });

        if ($request->trashed) {
            $data->withTrashed();
        }

        $response = [];

        if (isset($request->paginate) && $request->paginate == 1) {
            $data = $data->paginate($request->rows ?: 25, $request->columns ? $request->columns : ['*']);
            $response = [
                'total' => $data->total(),
                'data'  => $data->toArray()['data']
            ];
        } else {
            $response = [
                'total' => $data->count(),
                'data'  => $data->get($request->columns ? $request->columns : ['*'])
            ];
        }

        return response()->json($response);

    }

    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $profile = Profile::create($request->all());

            event(new ProfileEvent($profile));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        return response()->json($profile);
    }

    public function update(UpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $profile = Profile::findOrFail($id);
            $profile->update($request->all());

            event(new ProfileEvent($profile));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        return response()->json($profile);
    }

    public function destroy(Request $request, $id)
    {
         DB::beginTransaction();
        try {
            $profile = Profile::withTrashed()->findOrFail($id);

            if ($request->force) {
                $profile->forceDelete();
            } else if ($profile->trashed()) {
                $profile->restore();
            } else {
                $profile->delete();
            }

            event(new ProfileEvent($profile));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        return response()->json($profile);
    }
}
