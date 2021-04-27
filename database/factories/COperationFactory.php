<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Crm\COperation;
use Faker\Generator as Faker;

$factory->define(COperation::class, function (Faker $faker) {
    $faker->addProvider(new \Faker\Provider\en_US\Text($faker));
    $faker->addProvider(new \Faker\Provider\Base($faker));
    return [
        'operation_name' => $faker->realText($maxNbChars = 10, $indexSize = 2),
		'operation_description' => $faker->realText($maxNbChars = 50, $indexSize = 2),
		'start_date' => $faker->date($format = 'Y-m-d', $min = 'now'),
		'end_date' => $faker->date($format = 'Y-m-d', $min = 'now'),
		'user_id' => rand(1, 20),
		'c_operation_type_id' => rand(1, 4)
    ];
});
