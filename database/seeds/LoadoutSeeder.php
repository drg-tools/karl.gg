<?php

use App\Loadout;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class LoadoutSeeder extends Seeder
{
    public function run()
    {
        // There's likely a more elegant way to do this.
        $loadouts = [
           [
                'name' => 'Scout Test Loadout',
                'description' => 'Testing the scout loadout',
                'user_id' => 1,
                'character_id' => 2,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'patch_id' => 5,
                'throwable_id' => 12
           ]
        ];
        $loadout_eq = [
            [
                'loadout_id' => 1,
                'equipment_id' => 7,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'loadout_id' => 1,
                'equipment_id' => 10,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ];
        $loadout_eq_mods = [
            [
                'loadout_id' => 1,
                'equipment_mod_id' => 47,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'loadout_id' => 1,
                'equipment_mod_id' => 49,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'loadout_id' => 1,
                'equipment_mod_id' => 50,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'loadout_id' => 1,
                'equipment_mod_id' => 54,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'loadout_id' => 1,
                'equipment_mod_id' => 89,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'loadout_id' => 1,
                'equipment_mod_id' => 92,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'loadout_id' => 1,
                'equipment_mod_id' => 94,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'loadout_id' => 1,
                'equipment_mod_id' => 96,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ];
        $loadout_guns = [
            [
                'loadout_id' => 1,
                'gun_id' => 5,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'loadout_id' => 1,
                'gun_id' => 8,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ];
        $loadout_gun_mods = [
            [
                'loadout_id' => 1,
                'mod_id' => 156,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'loadout_id' => 1,
                'mod_id' => 157,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'loadout_id' => 1,
                'mod_id' => 159,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'loadout_id' => 1,
                'mod_id' => 162,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'loadout_id' => 1,
                'mod_id' => 167,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'loadout_id' => 1,
                'mod_id' => 194,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'loadout_id' => 1,
                'mod_id' => 197,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'loadout_id' => 1,
                'mod_id' => 198,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'loadout_id' => 1,
                'mod_id' => 201,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'loadout_id' => 1,
                'mod_id' => 203,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ];
        $loadout_overclocks = [
            [
                'loadout_id' => 1,
                'overclock_id' => 76,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'loadout_id' => 1,
                'overclock_id' => 95,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ];
        Loadout::insert($loadouts);
        DB::table('loadout_equipment')->insert($loadout_eq);
        DB::table('loadout_equipment_mod')->insert($loadout_eq_mods);
        DB::table('loadout_gun')->insert($loadout_guns);
        DB::table('loadout_mod')->insert($loadout_gun_mods);
        DB::table('loadout_overclock')->insert($loadout_overclocks);
    }
}
