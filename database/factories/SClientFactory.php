<?php

use Illuminate\Support\Str;
use Faker\Generator as Faker;

use App\Models\SClient;



$factory->define(SClient::class, function (Faker $faker) {
    $faker->addProvider(new \Faker\Provider\en_ZA\Person($faker));
    $faker->addProvider(new \Faker\Provider\en_US\Address($faker));
    $faker->addProvider(new \Faker\Provider\en_US\PhoneNumber($faker));
    $faker->addProvider(new \Faker\Provider\Internet($faker));
    return [
        'identification' => rand(100000, 10000000),
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'birthday' => $faker->date($format = 'Y-m-d', $min = 'now'),
        'adress' => $faker->address,
        'fix_phone' => $faker->tollFreePhoneNumber,
        'cel_phone' => $faker->phoneNumber,
        'email' => $faker->email,
        'g_city_id' => rand(1, 20),
        'g_identification_type_id' => rand(1, 6)
    ];
});
