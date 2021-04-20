<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//Models
use App\Models\Module;

class ModuleController extends Controller
{
    public function index(Request $request)
    {
        $modules = Module::with(['children'])
            ->where(function ($query) use ($request) {
                $query->where('parent', $request->parent ?: null);
                //$query->where('show', 1);
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
}
