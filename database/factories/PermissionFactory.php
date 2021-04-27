<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Permission::class, function (Faker $faker) {
    $user = \App\Models\User::find($faker->numberBetween($min = 1, $max = \App\Models\User::count()));
    #$module = \App\Models\Module::find($faker->numberBetween($min = 1, $max = \App\Models\Module::count()));
    return [
        'user_id' => 1,
        'module_id' => 1, //$module->id,
        'actions' => json_encode(['see' => true, 'create' => true, 'edit' => true, 'delete' => true]),
    ];
});
