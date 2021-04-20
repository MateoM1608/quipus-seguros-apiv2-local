<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;

//Models
use App\Models\Permission;

class Authorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->route()->getName() !== 'login' && $request->route()->getName() !== 'logout' && Auth::user()) {
            $action = 'actions->see';
            switch ($request->method()) {
                case 'POST':
                    $action = 'actions->create';
                    break;
                case 'PUT':
                    $action = 'actions->edit';
                    break;
                case 'DELETE':
                    $action = 'actions->delete';
                    break;
            }

            $user_id = null;

            if (Auth::user()) {
                $user_id = Auth::user()->id;
            }

            $prefix = collect(explode('/', $request->route()->getPrefix()));

            $permiso = Permission::whereHas('module', function ($query) use ($prefix) {
                $query->where('description', $prefix->last());
            })
                ->where('user_id', $user_id)
                ->where($action, true)
                ->first();

            if (!$permiso) {
                return response()->json('Acceso no autorizado', 403);
            }
        }

        return $next($request);
    }
}
