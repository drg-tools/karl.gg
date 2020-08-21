<?php

use App\Equipment;
use App\Gun;
use App\Loadout;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class LoadoutSeeder extends Seeder
{
    public function run()
    {
	// Creates n (5 in this case) number of loadouts with random guns / equipment and mods
	$loadouts = factory(Loadout::class, 5)->create()->each(function ($loadout) {
	    $availableGuns = Gun::where('character_id', $loadout->character_id)->with('mods')->get();
	    $availableEquipment = Equipment::where('character_id', $loadout->character_id)->with('equipment_mods')->get();

	    $gunsBySlot = $availableGuns->groupBy('character_slot');
	    $equipmentBySlot = $availableEquipment->groupBy('character_slot');

	    // For each gun slot, grab random mods and a random overclock
	    foreach ($gunsBySlot as $slot => $guns) {
		$randomModIds = $guns->first()->mods->groupBy('mod_tier')->map(function (Collection $tier) {
		    return $tier->random()->id;
		});

		$randomOverclock = $guns->first()->overclocks->random()->id;


		$loadout->mods()->attach($randomModIds);
		$loadout->overclocks()->attach($randomOverclock);
	    }

	    // For each equipment slot, grab random mods
	    foreach ($equipmentBySlot as $slot => $equipment) {
		$randomEquipmentModIds = $equipment->first()->equipment_mods->groupBy('mod_tier')->map(function (Collection $tier) {
		    return $tier->random()->id;
		});

		$loadout->equipment_mods()->attach($randomEquipmentModIds);
	    }
	});
    }
}
