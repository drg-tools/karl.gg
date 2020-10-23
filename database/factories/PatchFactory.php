<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Patch;
use Faker\Generator as Faker;

$factory->define(Patch::class, function (Faker $faker) {
    return [
        'patch_num' => $faker->numberBetween(100, 100000),
        'patch_num_dev' => $faker->numberBetween(100, 100000),
        'patch_title' => $faker->title,
        'launched_at' => $faker->dateTimeThisMonth,
    ];
});
