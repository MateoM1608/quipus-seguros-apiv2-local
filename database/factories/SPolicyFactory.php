<?php

use Illuminate\Support\Str;
use Faker\Generator as Faker;

use App\Models\Policy\SPolicy;

$factory->define(SPolicy::class, function (Faker $faker) {
    $faker->addProvider(new \Faker\Provider\en_ZA\Person($faker));
    $faker->addProvider(new \Faker\Provider\en_US\Address($faker));
    $faker->addProvider(new \Faker\Provider\en_US\PhoneNumber($faker));
    $faker->addProvider(new \Faker\Provider\Internet($faker));
    return [
        'policy_number' => rand(1000, 10000000),
        'expedition_date' => $faker->date($format = 'Y-m-d', $min = 'now'),
        's_branch_id' => rand(1, 5),
        's_client_id' => rand(1, 20),
        'g_vendor_id' => rand(1, 5),
        'policy_state' => $faker->randomElement(['Vigente', 'No Renovada', 'Cancelada']),
        'payment_periodicity' => $faker->randomElement(['Anual', 'Semestral', 'Trimestral', 'Mensual', 'Pago Unico']),
        's_agency_id' => rand(1, 4)
    ];
});
