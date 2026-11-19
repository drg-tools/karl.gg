<?php

namespace Database\Factories;

use App\Character;
use App\Equipment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Equipment>
 */
class EquipmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'character_id' => Character::factory(),
            'name' => fake()->unique()->words(2, true),
            'json_stats' => json_encode(['ex1' => ['name' => 'Max Range', 'value' => 30]]),
            'icon' => 'G_E_Zipline',
            'eq_type' => 'Equipment',
        ];
    }
}
