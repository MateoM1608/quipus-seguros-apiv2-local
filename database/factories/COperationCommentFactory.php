<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Crm\COperationComment;
use Faker\Generator as Faker;

$factory->define(COperationComment::class, function (Faker $faker) {
    $faker->addProvider(new \Faker\Provider\en_US\Text($faker));
    $faker->addProvider(new \Faker\Provider\Base($faker));
    return [
        'comment_description' => $faker->realText($maxNbChars = 50, $indexSize = 2),
		'comment_date' => $faker->date($format = 'Y-m-d', $min = 'now'),
		'user_id' => rand(1, 20),
		'c_operation_id' => rand(1, 20)
    ];
});
