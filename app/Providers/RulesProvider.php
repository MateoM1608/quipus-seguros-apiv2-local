<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;

// Models
use App\Models\Profile;
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
        \Validator::extend('distinct_to_zero', function ($field, $value, $params, $validate) {
            return (int) $value !== 0;
        });

        \Validator::extend('validate_total', function ($field, $value, $params, $validate) {
            return !(round($value) > round($params[0]));
        });

        \Validator::extend('validate_email', function ($field, $value, $params, $validate) {
            $user = User::where('email', $value)->count();
            return $user? true : false;
        });

        \Validator::extend('unique_notsensitive', function ($field, $value, $params, $validate) {
            $user = DB::table($params[0])->where(function($query) use ($value, $params) {
                $query->whereRaw('LOWER(' . $params[1] . ') = ?', strtolower($value));

                if (isset($params[2])) {
                    $query->where('id', '<>', $params[2]);
                }
            })
            ->count();
            return !$user? true : false;
        });
    }
}
