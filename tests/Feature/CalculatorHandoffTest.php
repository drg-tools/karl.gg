<?php

namespace Tests\Feature;

use App\Character;
use App\Equipment;
use App\EquipmentMod;
use App\GameDataCatalog;
use App\Gun;
use App\Loadout;
use App\Mod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CalculatorHandoffTest extends TestCase
{
    use RefreshDatabase;

    public function test_builder_opens_with_the_picks_handed_over_by_the_calculator(): void
    {
        $engineer = Character::factory()->create(['name' => 'Engineer']);
        $warthog = $this->syncGun('"Warthog" Auto 210');
        $cutter = $this->syncGun('Breach Cutter');
        $tierOne = $this->mod($warthog, 1, 'B');
        $tierThree = $this->mod($cutter, 3, 'A');
        $overclock = $warthog->overclocks->first();
        $equipmentMod = EquipmentMod::factory()->for(Equipment::factory()->for($engineer))->create();

        $response = $this->get('/build?'.http_build_query([
            'class' => $engineer->id,
            'guns' => "{$warthog->id},{$cutter->id}",
            'mods' => "{$tierOne->id},{$tierThree->id}",
            'overclocks' => (string) $overclock->id,
            'equipment_mods' => (string) $equipmentMod->id,
        ]));

        $response->assertOk();
        $builder = $response->viewData('builder');
        $this->assertSame([
            'character_id' => $engineer->id,
            'throwable_id' => null,
            'mods' => [$tierOne->id, $tierThree->id],
            'overclocks' => [$overclock->id],
            'equipment_mods' => [$equipmentMod->id],
        ], $builder['initial']);
        $this->assertSame([1 => $warthog->id, 2 => $cutter->id], $builder['weapons']);
        $this->assertSame('POST', $builder['method']);
    }

    public function test_builder_drops_handed_over_picks_that_do_not_fit_the_loadout(): void
    {
        $engineer = Character::factory()->create(['name' => 'Engineer']);
        $driller = Character::factory()->create(['name' => 'Driller']);
        $warthog = $this->syncGun('"Warthog" Auto 210');
        $stubby = $this->syncGun('"Stubby" Voltaic SMG');
        $subata = $this->syncGun('Subata 120');
        $kept = $this->mod($warthog, 1, 'A');
        $sameTier = $this->mod($warthog, 1, 'B');
        [$firstOverclock, $secondOverclock] = $warthog->overclocks->sortBy('id')->values()->all();
        $equipment = Equipment::factory()->for($engineer)->create();
        $keptEquipmentMod = EquipmentMod::factory()->for($equipment)->create(['mod_index' => 'A']);
        $sameTierEquipmentMod = EquipmentMod::factory()->for($equipment)->create(['mod_index' => 'B']);
        $drillerEquipmentMod = EquipmentMod::factory()->for(Equipment::factory()->for($driller))->create();

        $builder = $this->get('/build?'.http_build_query([
            'class' => $engineer->id,
            'guns' => "{$warthog->id},{$stubby->id},{$subata->id}",
            'mods' => implode(',', [$kept->id, $sameTier->id, $this->mod($stubby, 2, 'A')->id, $this->mod($subata, 2, 'A')->id]),
            'overclocks' => "{$firstOverclock->id},{$secondOverclock->id}",
            'equipment_mods' => "{$keptEquipmentMod->id},{$sameTierEquipmentMod->id},{$drillerEquipmentMod->id}",
            'throwable' => '999999',
        ]))->assertOk()->viewData('builder');

        $this->assertSame([
            'character_id' => $engineer->id,
            'throwable_id' => null,
            'mods' => [$kept->id],
            'overclocks' => [$firstOverclock->id],
            'equipment_mods' => [$keptEquipmentMod->id],
        ], $builder['initial']);
        $this->assertSame([1 => $warthog->id], $builder['weapons']);
    }

    public function test_builder_ignores_a_handoff_for_an_unknown_class(): void
    {
        $builder = $this->get('/build?class=999999&mods=1,2')->assertOk()->viewData('builder');

        $this->assertNull($builder['initial']);
        $this->assertNull($builder['weapons']);
    }

    public function test_opening_a_saved_loadout_ignores_a_calculator_handoff(): void
    {
        $engineer = Character::factory()->create(['name' => 'Engineer']);
        $driller = Character::factory()->create(['name' => 'Driller']);
        $loadout = Loadout::factory()->create(['character_id' => $engineer->id]);

        $builder = $this->get("/build/{$loadout->id}?class={$driller->id}")->assertOk()->viewData('builder');

        $this->assertSame($engineer->id, $builder['initial']['character_id']);
        $this->assertNull($builder['weapons']);
    }

    private function syncGun(string $name): Gun
    {
        app(GameDataCatalog::class)->sync(true, $name);

        return Gun::with('mods', 'overclocks')->where('name', $name)->firstOrFail();
    }

    private function mod(Gun $gun, int $tier, string $index): Mod
    {
        return $gun->mods->firstOrFail(fn (Mod $mod): bool => $mod->mod_tier === $tier && $mod->mod_index === $index);
    }
}
