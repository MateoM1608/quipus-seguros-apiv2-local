<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Illuminate\Support\Str;
use Faker\Generator as Faker;

use App\Models\Policy\GVendor;

$factory->define(GVendor::class, function (Faker $faker) {
    $faker->addProvider(new \Faker\Provider\en_ZA\Person($faker));
    $faker->addProvider(new \Faker\Provider\en_US\Address($faker));
    $faker->addProvider(new \Faker\Provider\en_US\PhoneNumber($faker));
    $faker->addProvider(new \Faker\Provider\Internet($faker));
    return [
        'identification' => rand(100000, 10000000),
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'birthday' => $faker->date($format = 'Y-m-d', $min = 'now'),
        'cellphone' => $faker->phoneNumber,
        'email' => $faker->email,
        'commission' => rand(1, 100),
        'g_identification_type_id' => rand(1, 6)
    ];
});
