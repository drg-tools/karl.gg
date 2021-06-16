<?php

namespace Database\Factories;

use App\Mod;
use Illuminate\Database\Eloquent\Factories\Factory;

class ModFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Mod::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'effect' => '+1.0 Rate of Fire',
            'description' => $this->faker->sentence,
            'row' => 1,
            'column' => 1,
            'gun_id' => 1,
        ];
    }
}
