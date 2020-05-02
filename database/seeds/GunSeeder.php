<?php

use App\Gun;
use App\Mod;
use Illuminate\Database\Seeder;

class GunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $gun = factory(Gun::class)->create(['name' => '"Warthog" Auth 210', 'gun_class' => 'Shotgun', 'character_slot' => 1, 'character_id' => 1]);
        for ($i = 1; $i < 5; $i++) {
            $gun->mods()->save(factory(Mod::class)->make(['row' => $i, 'column' => 1]));
            $gun->mods()->save(factory(Mod::class)->make(['row' => $i, 'column' => 2]));
        }
        $gun = factory(Gun::class)->create(['name' => '"Stubby" Voltaic SMG', 'gun_class' => 'Submachine Gun', 'character_slot' => 1, 'character_id' => 1]);
        for ($i = 1; $i < 5; $i++) {
            $gun->mods()->save(factory(Mod::class)->make(['row' => $i, 'column' => 1]));
            $gun->mods()->save(factory(Mod::class)->make(['row' => $i, 'column' => 2]));
        }
        $gun = factory(Gun::class)->create(['name' => 'Deepcore 40MM PGL', 'gun_class' => 'Heavy Weapon', 'character_slot' => 2, 'character_id' => 1]);
        for ($i = 1; $i < 5; $i++) {
            $gun->mods()->save(factory(Mod::class)->make(['row' => $i, 'column' => 1]));
            $gun->mods()->save(factory(Mod::class)->make(['row' => $i, 'column' => 2]));
        }
        $gun = factory(Gun::class)->create(['name' => 'Breach Cutter', 'gun_class' => 'Heavy Weapon', 'character_slot' => 2, 'character_id' => 1]);
        for ($i = 1; $i < 5; $i++) {
            $gun->mods()->save(factory(Mod::class)->make(['row' => $i, 'column' => 1]));
            $gun->mods()->save(factory(Mod::class)->make(['row' => $i, 'column' => 2]));
        }
    }
}
