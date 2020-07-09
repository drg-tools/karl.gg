<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Mod;
use Faker\Generator as Faker;

$factory->define(Mod::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'effect' => '+1.0 Rate of Fire',
        'description' => $faker->sentence,
        'row' => 1,
        'column' => 1,
        'gun_id' => 1,
    ];
});
