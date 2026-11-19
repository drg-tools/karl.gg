<?php

namespace Tests\Feature;

use App\Character;
use App\Gun;
use App\Loadout;
use App\Mod;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class BrowseLoadoutsTest extends TestCase
{
    use RefreshDatabase;

    public function test_browse_keeps_its_seo_metadata(): void
    {
        $this->get('/browse')
            ->assertOk()
            ->assertSee('<title>Karl: Born Ready - Deep Rock Galactic Loadouts</title>', false)
            ->assertSee('<meta name="description" content="Best builds for the best game. Your go to partner for Deep Rock Galactic builds and content.">', false)
            ->assertSee('<meta name="keywords" content="Deep Rock Galactic builds, drg builds">', false)
            ->assertSee('<meta property="og:title" content="Deep Rock Galactic Loadouts">', false)
            ->assertSee('<meta property="og:description" content="Best builds for the best game. Your go to partner for Deep Rock Galactic builds and content.">', false)
            ->assertSee('<meta name="twitter:site" content="@DrgTools">', false)
            ->assertSee('<meta name="twitter:title" content="Deep Rock Galactic Loadouts">', false)
            ->assertSee('<script type="application/ld+json">{"@context":"https://schema.org","@type":"WebPage","name":"Deep Rock Galactic Loadouts","description":"Best builds for the best game. Your go to partner for Deep Rock Galactic builds and content."}</script>', false);
    }

    public function test_legacy_class_array_params_still_filter(): void
    {
        $scout = Character::factory()->create(['name' => 'Scout']);
        $driller = Character::factory()->create(['name' => 'Driller']);
        $gunner = Character::factory()->create(['name' => 'Gunner']);
        Loadout::factory()->create(['name' => 'Scout Zipline Build', 'character_id' => $scout->id]);
        Loadout::factory()->create(['name' => 'Driller Drill Build', 'character_id' => $driller->id]);
        Loadout::factory()->create(['name' => 'Gunner Shield Build', 'character_id' => $gunner->id]);

        $this->get("/browse?characters[]={$scout->id}")
            ->assertOk()
            ->assertSee('Scout Zipline Build')
            ->assertDontSee('Driller Drill Build')
            ->assertSee('Remove filter: Class: Scout');

        $this->get("/browse?characters[]={$scout->id}&characters[]={$driller->id}")
            ->assertOk()
            ->assertSee('Scout Zipline Build')
            ->assertSee('Driller Drill Build')
            ->assertDontSee('Gunner Shield Build')
            ->assertSee('Showing Driller and Scout.');

        $this->get('/browse?characters[]=&hasGuide=')
            ->assertOk()
            ->assertSee('Gunner Shield Build')
            ->assertSee('3 loadouts');
    }

    public function test_pickers_are_scoped_to_the_selected_class(): void
    {
        $scout = Character::factory()->create(['name' => 'Scout']);
        $driller = Character::factory()->create(['name' => 'Driller']);
        $scoutGun = Gun::factory()->create(['name' => 'Deepcore GK2', 'gun_class' => 'Rifle', 'character_id' => $scout->id]);
        $drillerGun = Gun::factory()->create(['name' => 'CRSPR Flamethrower', 'gun_class' => 'Flamethrower', 'character_id' => $driller->id]);
        Mod::create($this->modAttributes($scoutGun, 'Gyro Stabilisation'));
        Mod::create($this->modAttributes($drillerGun, 'Sticky Flame Duration'));

        $this->get('/browse')
            ->assertOk()
            ->assertSee('<label class="browse-option" data-option="deepcore gk2 scout ">', false)
            ->assertSee('<label class="browse-option" data-option="crspr flamethrower driller ">', false)
            ->assertSee('<label class="browse-option is-out-of-scope" data-option="gyro stabilisation deepcore gk2 t1a">', false)
            ->assertSee('Pick a class above to choose mods');

        $this->get("/browse?characters[]={$scout->id}")
            ->assertOk()
            ->assertSee('<label class="browse-option" data-option="deepcore gk2 scout ">', false)
            ->assertSee('<label class="browse-option is-out-of-scope" data-option="crspr flamethrower driller ">', false)
            ->assertSee('<label class="browse-option" data-option="gyro stabilisation deepcore gk2 t1a">', false)
            ->assertSee('<label class="browse-option is-out-of-scope" data-option="sticky flame duration crspr flamethrower t1a">', false);
    }

    public function test_results_can_be_sorted(): void
    {
        $voter = User::factory()->create();
        $character = Character::factory()->create();
        $older = Loadout::factory()->create(['name' => 'Older Popular Build', 'character_id' => $character->id, 'created_at' => Carbon::now()->subDays(5), 'updated_at' => Carbon::now()->subDays(5)]);
        $newer = Loadout::factory()->create(['name' => 'Newer Quiet Build', 'character_id' => $character->id, 'created_at' => Carbon::now()->subDay(), 'updated_at' => Carbon::now()->subDays(10)]);
        $voter->upVote($older);

        $this->get('/browse')->assertSeeInOrder(['Older Popular Build', 'Newer Quiet Build']);
        $this->get('/browse?sort=newest')->assertSeeInOrder(['Newer Quiet Build', 'Older Popular Build']);
        $this->get('/browse?sort=salutes')
            ->assertSeeInOrder(['Older Popular Build', 'Newer Quiet Build'])
            ->assertSee('<option value="salutes" selected>Top salutes</option>', false);

        // Column-sortable links from the old table headers.
        $this->get('/browse?sort=votes_sum_value&direction=desc')
            ->assertSee('<option value="salutes" selected>Top salutes</option>', false);
        $this->get('/browse?sort=created_at&direction=desc')
            ->assertSeeInOrder(['Newer Quiet Build', 'Older Popular Build']);
    }

    public function test_live_requests_receive_only_the_results_fragment(): void
    {
        Loadout::factory()->create(['name' => 'Fragment Build', 'character_id' => Character::factory()->create()->id]);

        $this->get('/browse?search=Fragment', ['X-Requested-With' => 'XMLHttpRequest'])
            ->assertOk()
            ->assertHeader('Vary', 'X-Requested-With')
            ->assertSee('data-browse-fragment', false)
            ->assertSee('Fragment Build')
            ->assertSee('Remove filter: Name: “Fragment”')
            ->assertDontSee('<html', false)
            ->assertDontSee('browse-form', false);
    }

    /**
     * @return array<string, mixed>
     */
    private function modAttributes(Gun $gun, string $name): array
    {
        return [
            'character_id' => $gun->character_id,
            'gun_id' => $gun->id,
            'mod_tier' => 1,
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
