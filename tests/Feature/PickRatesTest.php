<?php

namespace Tests\Feature;

use App\Character;
use App\Gun;
use App\Loadout;
use App\Mod;
use App\Patch;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class PickRatesTest extends TestCase
{
    use RefreshDatabase;

    public function test_classes_are_ranked_by_this_patchs_pick_rate_against_the_last_patch(): void
    {
        [$previousPatch, $currentPatch] = $this->patches();
        $scout = Character::factory()->create(['name' => 'Scout']);
        $driller = Character::factory()->create(['name' => 'Driller']);
        $this->loadouts(3, $driller, $previousPatch);
        $this->loadouts(1, $scout, $previousPatch);
        $this->loadouts(3, $scout, $currentPatch);
        $this->loadouts(1, $driller, $currentPatch);

        $this->get('/pickrates/classes')
            ->assertOk()
            ->assertSee('<title>Karl: Born Ready</title>', false)
            ->assertSeeInOrder(['Season 06: Relics of Hoxxes', 'Builds this patch', '4', 'Last patch (Season 5)', '4'])
            ->assertSeeInOrder(['Scout', '75%', 'was 25%', '+50', '3 builds', 'Driller', '25%', 'was 75%', '-50', '1 build'])
            ->assertSee(e(route('loadout.index', ['characters' => [$scout->id], 'isCurrentPatch' => 1])), false);

        $this->get('/pickrates/classes?sort=falling')
            ->assertSeeInOrder(['Driller', '-50', 'Scout', '+50']);
    }

    public function test_weapons_and_mods_keep_their_filter_params(): void
    {
        [, $currentPatch] = $this->patches();
        $scout = Character::factory()->create(['name' => 'Scout']);
        $driller = Character::factory()->create(['name' => 'Driller']);
        $scoutGun = Gun::factory()->create(['name' => 'Deepcore GK2', 'gun_class' => 'Rifle', 'character_id' => $scout->id]);
        $drillerGun = Gun::factory()->create(['name' => 'CRSPR Flamethrower', 'gun_class' => 'Flamethrower', 'character_id' => $driller->id]);
        $popularMod = Mod::create($this->modAttributes($scoutGun, 'Hollow-Point Bullets', 4));
        $rareMod = Mod::create($this->modAttributes($scoutGun, 'Gyro Stabilisation', 1));
        Mod::create($this->modAttributes($drillerGun, 'Sticky Flame Duration', 1));

        $scoutLoadouts = $this->loadouts(4, $scout, $currentPatch);
        $popularMod->loadouts()->attach($scoutLoadouts->modelKeys());
        $rareMod->loadouts()->attach($scoutLoadouts->first());

        $this->get("/pickrates/guns?character={$scout->id}")
            ->assertOk()
            ->assertSee('Deepcore GK2')
            ->assertDontSee('CRSPR Flamethrower')
            ->assertSeeInOrder(['Deepcore GK2', '100%', '4 builds'])
            ->assertSee(e(route('loadout.index', ['characters' => [$scout->id], 'primaries' => [$scoutGun->id], 'isCurrentPatch' => 1])), false);

        $this->get('/pickrates/guns?search=CRSPR')
            ->assertSee('CRSPR Flamethrower')
            ->assertDontSee('Deepcore GK2');

        $this->get("/pickrates/mods?gun={$scoutGun->id}")
            ->assertOk()
            ->assertDontSee('Sticky Flame Duration')
            ->assertSeeInOrder([
                'Hollow-Point Bullets', 'in 100% of its weapon’s builds', '4 builds',
                'Gyro Stabilisation', 'in 25% of its weapon’s builds', '1 build',
            ]);
    }

    public function test_pick_rates_render_without_a_previous_patch_or_any_patch(): void
    {
        $this->get('/pickrates/classes')
            ->assertOk()
            ->assertSee('No patch data yet.');

        [$currentPatch] = $this->patches(1);
        $this->loadouts(2, Character::factory()->create(['name' => 'Gunner']), $currentPatch);

        $this->get('/pickrates/classes')
            ->assertOk()
            ->assertSeeInOrder(['Gunner', '100%', '2 builds'])
            ->assertDontSee('was 0%');
    }

    /**
     * @return list<Patch> Oldest first; the last one is the current patch.
     */
    private function patches(int $count = 2): array
    {
        return collect([
            ['Season 5', 'Season 05: Drilling Deeper'],
            ['Season 6', 'Season 06: Relics of Hoxxes'],
        ])
            ->take(-$count)
            ->values()
            ->map(fn (array $patch, int $index) => Patch::factory()->create([
                'short_name' => $patch[0],
                'patch_title' => $patch[1],
                'launched_at' => Carbon::now()->subMonths($count - $index),
            ]))
            ->all();
    }

    /**
     * @return Collection<int, Loadout>
     */
    private function loadouts(int $count, Character $character, Patch $patch)
    {
        return Loadout::factory()->count($count)->create(['character_id' => $character->id])
            ->each(fn (Loadout $loadout) => $loadout->forceFill(['patch_id' => $patch->id])->saveQuietly());
    }

    /**
     * @return array<string, mixed>
     */
    private function modAttributes(Gun $gun, string $name, int $tier): array
    {
        return [
            'character_id' => $gun->character_id,
            'gun_id' => $gun->id,
            'mod_tier' => $tier,
            'mod_index' => 'A',
            'mod_name' => $name,
            'credits_cost' => 0,
            'magnite_cost' => 0,
            'bismor_cost' => 0,
            'umanite_cost' => 0,
            'croppa_cost' => 0,
            'enor_pearl_cost' => 0,
            'jadiz_cost' => 0,
            'text_description' => '',
            'json_stats' => '',
            'icon' => '',
            'mod_type' => '',
        ];
    }
}
