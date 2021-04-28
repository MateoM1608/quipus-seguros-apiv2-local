<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\PermissionProfile;
use Faker\Generator as Faker;

$factory->define(PermissionProfile::class, function (Faker $faker) {
    return [
        'module_id' => null,
        'profile_id' => null,
    ];
});
