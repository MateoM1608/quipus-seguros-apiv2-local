<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

// Moldels
use App\Models\Permission;
use App\Models\Module;

// Events
use App\Events\PermissionUserEvent;

class PermissionUserController extends Controller
{
    public function index(Request $request)
    {
        $modules = Module::with(['permission'])
            ->where(function ($query) use ($request) {
                $query->where('parent', $request->parent ?: null);
            })
            ->join('permissions', 'module_id', 'modules.id')
            ->where('permissions.user_id', auth()->user()->id)
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
                "permissions.actions",
                "permissions.user_id",
                "permissions.id AS permission_id",
            ]);

        return response()->json($modules);
    }

    public function store(Request $request)
    {
        $data = [];
        if (gettype($request->modules) == 'array') {
            foreach ($request->modules as $module) {
                $data[] = [
                    'user_id' => $request->user_id,
                    'module_id' => $module['id'],
                    'actions' =>json_encode($module['actions']),
                ];
            }
        } else {
            $data[] = [
                'user_id' => $request->user_id,
                'module_id' => $request->module_id,
                'actions' => json_encode($request->actions),
            ];
        }

        $request->replace($data);

        DB::beginTransaction();
        try {
            $permission = Permission::upsert(
                $request->all(),
                ['user_id', 'module_id'],
                ['actions']
            );

            $data = collect($request->all());

            $permission = Permission::where(function ($query) use($data) {
                $query->whereIn('user_id', $data->pluck('user_id')->unique());
                $query->whereIn('module_id', $data->pluck('module_id')->unique());
            })
            ->get();

            event(new PermissionUserEvent($permission));

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
            $permission = Permission::withTrashed()->findOrFail($id);
            if ($permission->trashed()) {
                $permission->restore();
            } elseif ($request->force) {
                $permission->forceDelete();
            } else {
                $permission->delete();
            }

            event(new PermissionUserEvent($permission));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        $permission->force = $request->force;

        return response()->json($permission);
    }

}
