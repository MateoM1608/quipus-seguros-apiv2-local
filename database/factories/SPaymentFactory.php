<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Policy\SPayment;
use Faker\Generator as Faker;

$factory->define(SPayment::class, function (Faker $faker) {
    $faker->addProvider(new \Faker\Provider\en_US\Text($faker));
    $faker->addProvider(new \Faker\Provider\Base($faker));
    return [
			'payment_number' => rand(1000, 10000000),
			'payment_date' => $faker->date($format = 'Y-m-d', $min = 'now'),
			'premium_value' => $faker->randomFloat($nbMaxDecimals = NULL, $min = 1000000, $max = NULL),
			'tax_value' => $faker->randomFloat($nbMaxDecimals = NULL, $min = 1000000, $max = NULL),
			'total_value' => $faker->randomFloat($nbMaxDecimals = NULL, $min = 1000000, $max = NULL),
			's_annex_id' => rand(1, 20),
			'payment_form' => $faker->randomElement(['Contado', 'Financiacion'])
    ];
});
