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
        factory(Character::class)->create(['name' => 'Engineer', 'image' => '50px-E_icon']);
        factory(Character::class)->create(['name' => 'Scout', 'image' => '50px-S_icon']);
        factory(Character::class)->create(['name' => 'Driller', 'image' => '50px-D_icon']);
        factory(Character::class)->create(['name' => 'Gunner', 'image' => '50px-G_icon']);
    }
}
