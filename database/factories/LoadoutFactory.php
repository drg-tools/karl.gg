<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Loadout;
use Faker\Generator as Faker;

$factory->define(Loadout::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->sentence,
        'user_id' => 1,
        'character_id' => $faker->numberBetween(1, 4),
        'created_at' => now(),
        'patch_id' => 5,
        'throwable_id' => 12,
    ];
});
