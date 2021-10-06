<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

// Moldels
use App\Models\PermissionProfile;
use App\Models\Module;

// Events
use App\Events\PermissionProfileEvent;

class PermissionProfileController extends Controller
{
    public function index(Request $request)
    {
        $modules = Module::with(['permissionProfile'])
            ->where(function ($query) use ($request) {
                $query->where('parent', $request->parent ?: null);
            })
            ->leftJoin('permission_profiles', function ($query) use ($request) {
                $query->on('module_id', 'modules.id')
                ->where('permission_profiles.profile_id', $request->profile_id);
            })
            ->orderBy('order', 'asc')
            ->get([
                "modules.id",
                "modules.description",
                "modules.name",
                "modules.parent",
                "modules.url",
                "modules.icon",
                "modules.image",
                "modules.class",
                "modules.badge",
                "modules.wrapper",
                "modules.variant",
                "modules.attributes",
                "modules.divider",
                "modules.method",
                "modules.show",
                DB::raw("IF(permission_profiles.actions is not null ,permission_profiles.actions, '{\"see\": false, \"edit\": false, \"create\": false, \"delete\": false}') as actions"),
                "permission_profiles.profile_id",
                "permission_profiles.id AS permission_profile_id",
            ]);

        return response()->json($modules);
    }

    public function store(Request $request)
    {
        $data = [];
        if (gettype($request->modules) == 'array') {
            foreach ($request->modules as $module) {
                $data[] = [
                    'profile_id' => $request->profile_id,
                    'module_id' => $module['id'],
                    'actions' =>json_encode($module['actions']),
                ];
            }
        } else {
            $data[] = [
                'profile_id' => $request->user_id,
                'module_id' => $request->module_id,
                'actions' => json_encode($request->actions),
            ];
        }

        $request->replace($data);

        DB::beginTransaction();
        try {
            $permission = PermissionProfile::upsert(
                $request->all(),
                ['profile_id', 'module_id'],
                ['actions']
            );

            $data = collect($request->all());

            $permission = PermissionProfile::where(function ($query) use($data) {
                $query->whereIn('profile_id', $data->pluck('profile_id')->unique());
                $query->whereIn('module_id', $data->pluck('module_id')->unique());
            })
            ->get();

            event(new PermissionProfileEvent($permission));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        return response()->json($permission);
    }

    public function destroy(Request $request, $id)
    {
         DB::beginTransaction();
        try {
            $permission = PermissionProfile::withTrashed()->findOrFail($id);
            if ($permission->trashed()) {
                $permission->restore();
            } elseif ($request->force) {
                $permission->forceDelete();
            } else {
                $permission->delete();
            }

            event(new PermissionProfileEvent($permission));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        $permission->force = $request->force;

        return response()->json($permission);
    }

}
