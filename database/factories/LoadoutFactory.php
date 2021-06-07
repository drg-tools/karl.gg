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
        $createdAt = $this->faker->dateTimeThisYear;

        return [
            'name' => $this->faker->name,
            'description' => $this->faker->sentence,
            'user_id' => 1,
            'character_id' => $this->faker->numberBetween(1, 4),
            'patch_id' => 1,
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
            'throwable_id' => 12,
        ];
    }
}
