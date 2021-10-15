<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

// Models
use App\Models\ProfileUser;

// Events
use App\Events\ProfileUserEvent;

class ProfileUserController extends Controller
{
    public function index(Request $request)
    {
        $data = ProfileUser::where(function ($query) use ($request) {
            if (isset($request->user_id)) {
                $query->where('user_id', $request->user_id);
            }

            if (isset($request->profile_id)) {
                $query->where('profile_id', $request->profile_id);
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
        $user_id = $request->user_id;
        $data = [];
        if (gettype($request->profile_id) == 'array') {
            foreach ($request->profile_id as $profile_id) {
                $data[] = [
                    'user_id' => $request->user_id,
                    'profile_id' => $profile_id,
                ];
            }
        } else {
            $data[] = [
                'user_id' => $request->user_id,
                'profile_id' => $request->profile_id,
            ];
        }

        $request->replace($data);

        DB::beginTransaction();
        try {

            ProfileUser::where('user_id', $user_id)->forceDelete();

            $profileUser = ProfileUser::insert($request->all());

            $data = collect($request->all());

            $profileUser = ProfileUser::where(function ($query) use($data) {
                $query->whereIn('user_id', $data->pluck('user_id')->unique());
                $query->whereIn('profile_id', $data->pluck('profile_id')->unique());
            })
            ->get();

            event(new ProfileUserEvent($profileUser));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        return response()->json($profileUser);
    }

    public function destroy(Request $request, $id)
    {
         DB::beginTransaction();
        try {
            $profileUser = ProfileUser::withTrashed()->findOrFail($id);
            if ($profileUser->trashed()) {
                $profileUser->restore();
            } elseif ($request->force) {
                $profileUser->forceDelete();
            } else {
                $profileUser->delete();
            }

            event(new ProfileUserEvent($profileUser));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        $profileUser->force = $request->force;

        return response()->json($profileUser);
    }
}
