<?php

use Illuminate\Support\Str;
use Faker\Generator as Faker;

use App\Models\GCountry;

$factory->define(GCountry::class, function (Faker $faker) {
    $faker->addProvider(new \Faker\Provider\en_US\Address($faker));
    return [
        'description' => $faker->country,
        'initials' => 'ABC'
    ];
});
