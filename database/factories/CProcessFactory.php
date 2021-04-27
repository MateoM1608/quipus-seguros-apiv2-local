<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Crm\CProcess;
use Faker\Generator as Faker;

$factory->define(CProcess::class, function (Faker $faker) {
    $faker->addProvider(new \Faker\Provider\en_US\Text($faker));
    $faker->addProvider(new \Faker\Provider\Base($faker));
    return [
        'description' => $faker->realText($maxNbChars = 50, $indexSize = 2),
		'start_date' => $faker->date($format = 'Y-m-d', $min = 'now'),
		'end_date' => $faker->date($format = 'Y-m-d', $min = 'now'),
		'sale_value' => $faker->randomFloat($nbMaxDecimals = NULL, $min = 1000000, $max = NULL),
		'open_close' => $faker->randomElement(['Open', 'Close']),
		'c_process_stage_id' => rand(1, 5),
		's_client_id' => rand(1, 20)
    ];
});
