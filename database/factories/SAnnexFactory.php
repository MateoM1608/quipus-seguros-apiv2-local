<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Policy\SAnnex;
use Faker\Generator as Faker;

$factory->define(SAnnex::class, function (Faker $faker) {
    $faker->addProvider(new \Faker\Provider\en_US\Text($faker));
    $faker->addProvider(new \Faker\Provider\Base($faker));
    return [
        'annex_number' => rand(1000, 10000000),
        'annex_expedition'  => $faker->date($format = 'Y-m-d', $min = 'now'),
        'annex_start'  => $faker->date($format = 'Y-m-d', $min = 'now'),
        'annex_end'  => $faker->date($format = 'Y-m-d', $min = 'now'),
        'annex_premium' => $faker->randomFloat($nbMaxDecimals = NULL, $min = 1000000, $max = NULL),
        'annex_tax' => $faker->randomFloat($nbMaxDecimals = NULL, $min = 1000000, $max = NULL),
        'annex_total_value' => $faker->randomFloat($nbMaxDecimals = NULL, $min = 1000000, $max = NULL),
        'annex_description' => $faker->realText($maxNbChars = 50, $indexSize = 2),
        'annex_commission' => $faker->randomFloat($nbMaxDecimals = NULL, $min = 1000000, $max = NULL),
        'annex_paid' => $faker->randomElement(['Si', 'No']),
        'commission_paid' => $faker->randomElement(['Si', 'No']),
        'annex_type' => $faker->randomElement(['Expedición', 'Modificación', 'Cobro', 'Cancelación', 'Renovación', 'Devolución']),
        's_policy_id' => rand(1, 20),
        'annex_print' => $faker->randomElement(['Si', 'No', 'N/A']),
        'annex_printed' => $faker->randomElement(['Si', 'No', 'N/A']),
        'annex_email' => $faker->randomElement(['Si', 'No', 'N/A']),
        'annex_delivered' => $faker->randomElement(['Si', 'No', 'N/A']),
    ];
});
