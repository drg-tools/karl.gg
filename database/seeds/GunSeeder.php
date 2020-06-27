<?php

use App\Gun;
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
        /**
         * 1	Engineer
         * 2	Scout
         * 3	Driller
         * 4	Gunner.
         */
        $characterGuns = [
            1 => [
                ['name' => '"Warthog" Auto 210', 'gun_class' => 'Shotgun', 'character_slot' => 1],
                ['name' => '"Stubby" Voltaic SMG', 'gun_class' => 'Submachine Gun', 'character_slot' => 1],
                ['name' => 'Deepcore 40MM PGL', 'gun_class' => 'Heavy Weapon', 'character_slot' => 2],
                ['name' => 'Breach Cutter', 'gun_class' => 'Heavy Weapon', 'character_slot' => 2],
            ],
            2 => [
                ['name' => 'Deepcore GK2', 'gun_class' => 'Assault Rifle', 'character_slot' => 1],
                ['name' => 'M1000 Classic', 'gun_class' => 'Semi-Automatic Rifle', 'character_slot' => 1],
                ['name' => 'Jury-Rigged Boomstick', 'gun_class' => 'Shotgun', 'character_slot' => 2],
                ['name' => 'Zhukov NUK17', 'gun_class' => 'Submachine Gun', 'character_slot' => 2],
            ],
            3 => [
                ['name' => 'CRSPR Flamethrower', 'gun_class' => 'Heavy Weapon', 'character_slot' => 1],
                ['name' => 'Cryo Cannon', 'gun_class' => 'Heavy Weapon', 'character_slot' => 1],
                ['name' => 'Subata 120', 'gun_class' => 'Pistol', 'character_slot' => 2],
                ['name' => 'Experimental Plasma Charger', 'gun_class' => 'Pistol', 'character_slot' => 2],
            ],
            4 => [
                ['name' => '"Lead Storm" Powered Minigun', 'gun_class' => 'Heavy Weapon', 'character_slot' => 1],
                ['name' => '"Thunderhead" Heavy Autocannon', 'gun_class' => 'Heavy Weapon', 'character_slot' => 1],
                ['name' => '"Bulldog" Heavy Revolver', 'gun_class' => 'Revolver', 'character_slot' => 2],
                ['name' => 'BRT7 Burst Fire Gun', 'gun_class' => 'Pistol', 'character_slot' => 2],
            ],
        ];

        foreach ($characterGuns as $character_id => $character) {
            foreach ($character as $gun) {
                $attrs = array_merge($gun, ['character_id' => $character_id]);
                factory(Gun::class)->create($attrs);
            }
        }
    }
}
