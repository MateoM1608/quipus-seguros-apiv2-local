<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Illuminate\Support\Str;
use App\Models\Policy\SClaim;
use Faker\Generator as Faker;

$factory->define(SClaim::class, function (Faker $faker) {
    $faker->addProvider(new \Faker\Provider\en_US\Text($faker));
    $faker->addProvider(new \Faker\Provider\Base($faker));
    return [
        'claim_number' => rand(100000, 10000000),
        'claim_date' => $faker->date($format = 'Y-m-d', $min = 'now'),
        'notice_date' => $faker->date($format = 'Y-m-d', $min = 'now'),
        'claim_value' => $faker->randomFloat($nbMaxDecimals = NULL, $min = 1000000, $max = NULL),
        'paid_value' => $faker->randomFloat($nbMaxDecimals = NULL, $min = 1000000, $max = NULL),
        'payment_date' => $faker->date($format = 'Y-m-d', $min = 'now'),
        'objection_date' => NULL,
        'claim_description' => $faker->realText($maxNbChars = 50, $indexSize = 2),
        's_policy_id' => rand(1, 20),
        'claim_status' => $faker->randomElement($array = array('Recibido del Cliente', 'Presentado a Aseguradora', 'Documentación Completa', 'Pagado', 'Objetado'))
    ];
});
