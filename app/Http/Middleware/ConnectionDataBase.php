<?php

namespace App\Http\Middleware;

use Closure;
use Config;

class ConnectionDataBase
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
        if (auth()->user() && auth()->user()->connection) {
            Config::set('database.default', auth()->user()->connection);
        }

        return $next($request);
    }
}
