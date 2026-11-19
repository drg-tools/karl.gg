<?php

namespace Tests\Feature;

use App\Character;
use App\Gun;
use App\Loadout;
use App\Overclock;
use App\Patch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_keeps_its_seo_metadata(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('<title>Karl: Born Ready - Dashboard</title>', false)
            ->assertSee('<meta name="description" content="Best builds for the best game. Your go to partner for Deep Rock Galactic builds and content.">', false)
            ->assertSee('<meta property="og:title" content="Karl.gg: The best Deep Rock Galactic Builds">', false)
            ->assertSee('<meta name="twitter:site" content="@DrgTools">', false);
    }

    public function test_dashboard_summarises_the_current_patch(): void
    {
        $previousPatch = Patch::factory()->create(['launched_at' => Carbon::now()->subYear()]);
        $currentPatch = Patch::factory()->create([
            'patch_title' => 'Season 06: Relics of Hoxxes',
            'launched_at' => Carbon::now()->subMonth(),
        ]);
        $scout = Character::factory()->create(['name' => 'Scout']);
        $driller = Character::factory()->create(['name' => 'Driller']);
        $gun = Gun::factory()->create(['name' => 'Jury-Rigged Boomstick', 'gun_class' => 'Shotgun', 'character_id' => $scout->id]);
        $overclock = Overclock::create([
            'character_id' => $scout->id,
            'gun_id' => $gun->id,
            'overclock_type' => 'Clean',
            'overclock_index' => 1,
            'overclock_name' => 'Special Powder',
            'icon' => 'Icon_Overclock_ShotgunJump',
            'credits_cost' => 0,
            'magnite_cost' => 0,
            'bismor_cost' => 0,
            'umanite_cost' => 0,
            'croppa_cost' => 0,
            'enor_pearl_cost' => 0,
            'jadiz_cost' => 0,
            'text_description' => '',
            'json_stats' => '',
        ]);

        $scoutLoadouts = Loadout::factory()->count(3)->create(['character_id' => $scout->id]);
        Loadout::factory()->create(['character_id' => $driller->id]);
        Loadout::query()->update(['patch_id' => $currentPatch->id]);
        Loadout::factory()->count(5)->create(['character_id' => $driller->id])
            ->each(fn (Loadout $loadout) => $loadout->forceFill(['patch_id' => $previousPatch->id])->saveQuietly());
        $overclock->loadouts()->attach($scoutLoadouts->modelKeys());

        $this->get('/')
            ->assertOk()
            ->assertSee('Season 06: Relics of Hoxxes')
            ->assertSeeInOrder(['Builds this patch', '4', 'Builds all-time', '9', 'Most played', 'Scout'])
            ->assertSee('3 builds · 75% this patch')
            ->assertSee('1 build · 25% this patch')
            ->assertSeeInOrder(['Most-picked overclocks this patch', 'Special Powder', 'Jury-Rigged Boomstick', 'Clean', '3 builds']);
    }
}
