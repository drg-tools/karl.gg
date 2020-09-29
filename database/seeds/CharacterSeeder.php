<?php

use App\Character;
use Illuminate\Database\Seeder;

class CharacterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Character::factory()->create(['name' => 'Engineer', 'image' => '50px-E_icon']);
        Character::factory()->create(['name' => 'Scout', 'image' => '50px-S_icon']);
        Character::factory()->create(['name' => 'Driller', 'image' => '50px-D_icon']);
        Character::factory()->create(['name' => 'Gunner', 'image' => '50px-G_icon']);
    }
}
