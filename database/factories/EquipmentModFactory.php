<?php

namespace Database\Factories;

use App\Equipment;
use App\EquipmentMod;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EquipmentMod>
 */
class EquipmentModFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'equipment_id' => Equipment::factory(),
            'character_id' => fn (array $attributes): int => Equipment::findOrFail($attributes['equipment_id'])->character_id,
            'mod_tier' => 1,
            'mod_index' => 'A',
            'mod_name' => fake()->words(2, true),
            'description' => fake()->sentence(),
            'json_stats' => json_encode(['ex1' => ['name' => 'Max Range', 'value' => 10]]),
            'credits_cost' => 1000,
            'magnite_cost' => 0,
            'bismor_cost' => 0,
            'umanite_cost' => 0,
            'croppa_cost' => 0,
            'enor_pearl_cost' => 0,
            'jadiz_cost' => 0,
            'icon' => 'Icon_Upgrade_Distance',
            'mod_type' => 'Range',
        ];
    }
}
