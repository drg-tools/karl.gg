<?php

namespace Database\Factories;

use App\Build;
use Illuminate\Database\Eloquent\Factories\Factory;

class BuildFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Build::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'Super Awesome Build',
            'user_id' => 1,
            'character_id' => 1,
        ];
    }
}
