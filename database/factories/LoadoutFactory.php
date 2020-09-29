<?php

namespace Database\Factories;

use App\Loadout;
use Illuminate\Database\Eloquent\Factories\Factory;

class LoadoutFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Loadout::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->sentence,
            'user_id' => 1,
            'character_id' => $this->faker->numberBetween(1, 4),
            'created_at' => now(),
            'patch_id' => 5,
            'throwable_id' => 12,
        ];
    }
}
