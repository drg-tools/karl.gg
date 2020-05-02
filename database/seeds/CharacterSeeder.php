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
        factory(Character::class)->create(['name' => 'Engineer']);
        factory(Character::class)->create(['name' => 'Scout']);
        factory(Character::class)->create(['name' => 'Driller']);
        factory(Character::class)->create(['name' => 'Gunner']);
    }
}
