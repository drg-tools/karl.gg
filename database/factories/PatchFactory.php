<?php

namespace Database\Factories;

use App\Models\Patch;
use Illuminate\Database\Eloquent\Factories\Factory;

class PatchFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Patch::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'patch_num' => $this->faker->numberBetween(100, 100000),
            'patch_title' => $this->faker->title,
            'short_name' => 'Season '.$this->faker->numberBetween(1, 20),
            'launched_at' => $this->faker->dateTimeThisMonth,
        ];
    }
}
