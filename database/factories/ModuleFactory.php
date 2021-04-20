<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Module::class, function (Faker $faker) {
    return [
        'description' => $faker->unique()->word,
        'name' => $faker->jobTitle,
        'parent' => function () use ($faker) {
            $module = App\Models\Module::find($faker->numberBetween($min = 1, $max = 20));
            $faker->boolean ? !$module ?: $module->id : null;
        },
        'title' => $faker->boolean,
        'url' => $faker->url,
        'icon' => $faker->state,
        'class' => $faker->state,
        'badge' => null,
        'wrapper' => null,
        'variant' => null,
        'attributes' => null,
        'divider' => $faker->boolean,
    ];
});
