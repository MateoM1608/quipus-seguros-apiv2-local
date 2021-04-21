<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Illuminate\Support\Str;
use App\Models\Policy\SRisk;
use Faker\Generator as Faker;

$factory->define(SRisk::class, function (Faker $faker) {
    $faker->addProvider(new \Faker\Provider\en_US\Text($faker));
    $faker->addProvider(new \Faker\Provider\Base($faker));
    return [

        'risk_number' => rand(1, 20),
		'risk_description' => $faker->realText($maxNbChars = 50, $indexSize = 2),
        'risk_premium' => $faker->randomFloat($nbMaxDecimals = 2, $min = 100000, $max = NULL),
        's_policy_id' => rand(1, 20)
        //
    ];
});
