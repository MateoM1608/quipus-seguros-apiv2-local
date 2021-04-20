<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use DB;

// Models
use App\Models\Profile;

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

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $profile = Profile::create($request->all());
            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        $redis = Redis::connection();
        $redis->publish('channel-vue-' . auth()->guard('api')->user()->id, json_encode([
            'evento' => 'PROFILE',
            'datos' => $profile
        ]));

        return response()->json($profile);
    }

    public function update(Request $request, Profile $profile)
    {
        DB::beginTransaction();
        try {
            $profile->update($request->all());
            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        $redis = Redis::connection();
        $redis->publish('channel-vue-' . auth()->guard('api')->user()->id, json_encode([
            'evento' => 'PROFILE',
            'datos' => $profile
        ]));

        return response()->json($profile);
    }

    public function destroy(Request $request, $id)
    {
         DB::beginTransaction();
        try {
            $profile = Profile::withTrashed()->findOrFail($id);

            if ($profile->trashed()) {
                $profile->restore();
            } elseif ($request->force) {
                $profile->forceDelete();
            } else {
                $profile->delete();
            }

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        $profile->force = $request->force;

        $redis = Redis::connection();
        $redis->publish('channel-vue-' . auth()->guard('api')->user()->id, json_encode([
            'evento' => 'PROFILE',
            'datos' => $profile
        ]));

        return response()->json($profile);
    }
}
