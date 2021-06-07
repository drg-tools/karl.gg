<?php

namespace Database\Factories;

use App\Gun;
use Illuminate\Database\Eloquent\Factories\Factory;

class GunFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Gun::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'Warthog Auth 210',
            'character_slot' => 1,
            'character_id' => 1,
            'image' => 'E_P1_Warthog',
            'json_stats' => '',
        ];
    }
}
