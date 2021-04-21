<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Illuminate\Support\Str;
use Faker\Generator as Faker;
use App\Models\Policy\SCommission;

$factory->define(SCommission::class, function (Faker $faker) {
    $faker->addProvider(new \Faker\Provider\en_ZA\Person($faker));
    $faker->addProvider(new \Faker\Provider\en_US\Address($faker));
    $faker->addProvider(new \Faker\Provider\en_US\PhoneNumber($faker));
    $faker->addProvider(new \Faker\Provider\Internet($faker));
    return [
        'commission_number' => rand(1, 9000),
        'commission_date' => $faker->date($format = 'Y-m-d', $min = 'now'),
        'commission_value' => rand(1, 30),
        's_annex_id' => rand(1, 20),
        's_payroll_id' => rand(1, 20),
        'g_vendor_id' => rand(1, 20),
        'vendor_commission_paid' => $faker->randomElement($array = array('Si', 'No')),
        'agency_commission' => $faker->email,
        'agency_commission' => rand(10, 40)
    ];
});
