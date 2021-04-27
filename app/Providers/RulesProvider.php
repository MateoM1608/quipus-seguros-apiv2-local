<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Models
use App\Models\User;
class RulesProvider extends ServiceProvider
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
        \Validator::extend('validate_total', function ($field, $value, $params, $validate) {
            return !($value > $params[0]);
        });

        \Validator::extend('validate_email', function ($field, $value, $params, $validate) {
            $user = User::where('email', $value)->count();
            return $user? true : false;
        });
    }
}
