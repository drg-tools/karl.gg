<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Build;
use Faker\Generator as Faker;

$factory->define(Build::class, function (Faker $faker) {
    return [
        'name' => 'Super Awesome Build',
        'user_id' => 1,
        'character_id' => 1,
    ];
});
