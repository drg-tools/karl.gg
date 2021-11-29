<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Hazzard\Comments\Tests\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Hazzard\Comments\Comment::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'content' => $faker->text,
        'permalink' => '',
    ];
});
