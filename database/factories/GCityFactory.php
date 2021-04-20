<?php

use Illuminate\Support\Str;
use Faker\Generator as Faker;


use App\Models\GCity;

$factory->define(GCity::class, function (Faker $faker) {
    $faker->addProvider(new \Faker\Provider\en_US\Address($faker));
    return [
        'description' => $faker->city,
        'initials' => 'DEF',
        'g_country_id' => rand(1, 20)

    ];
});
