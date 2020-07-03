<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Gun;
use Faker\Generator as Faker;

$factory->define(Gun::class, function (Faker $faker) {
    return [
        'name' => 'Warthog Auth 210',
        'character_slot' => 1,
        'character_id' => 1,
    ];
});
