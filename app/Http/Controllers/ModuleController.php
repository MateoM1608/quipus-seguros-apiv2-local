<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

//Models
use App\Models\Module;

// Events
use App\Events\ModuleEvent;

class ModuleController extends Controller
{
    public function index(Request $request)
    {
        $modules = Module::with(['children'])
        ->where(function ($query) use ($request) {
            $query->where('parent', $request->parent ?: null);
        })
        ->join('permissions', function ($query) use ($request) {
            $query->on('module_id', 'modules.id')
            ->where('permissions.user_id', auth()->user()->id)
            ->where("permissions.actions->see", true);
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
        ]);

        return response()->json($modules);
    }

     public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $module = Module::create($request->all());

            event(new ModuleEvent($module));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        return response()->json($module);
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $module = Module::findOrFail($id);
            $module->update($request->all());

            event(new ModuleEvent($module));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }


        return response()->json($module);
    }

    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $module = Module::withTrashed()->find($id);

            if ($request->force) {
                $module->forceDelete();
            } else if ($module->trashed()) {
                $module->restore();
            } else {
                $module->delete();
            }

            event(new ModuleEvent($module));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) {
            DB::rollBack();
            return response()->json($th->getMessage(), 422);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json($th->getMessage(), 422);
        }

        return response()->json($module);
    }
}
