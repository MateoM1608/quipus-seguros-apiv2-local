<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Models
use App\Models\User;

class UserValidationProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        \Validator::extend('unique_user_with', function ($field, $value, $params, $validate) {
            $intermediario = User::withTrashed()
            ->where(function ($query) use($params, $value) {
                $query->where($params[0], $value);

                if (isset($params[1])) {
                    $query->where('id', '<>', $params[1]);
                }
            })
            ->where('connection', auth()->user()->connection)
            ->count();

            return !$intermediario;
        });
    }
}
