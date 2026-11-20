<?php

namespace Tests\Feature;

use App\Character;
use App\Equipment;
use App\EquipmentMod;
use App\GameDataCatalog;
use App\Gun;
use App\Loadout;
use App\Mod;
use App\Patch;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BuilderEndpointTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_save_anonymous_build_without_forging_ownership(): void
    {
        $input = $this->input();
        $input['user_id'] = User::factory()->create()->id;
        $response = $this->postJson('/build', $input)->assertCreated();
        $this->assertDatabaseHas('loadouts', ['id' => $response->json('id'), 'name' => 'Test build', 'user_id' => null]);
        $this->assertDatabaseHas('loadout_mod', ['loadout_id' => $response->json('id'), 'mod_id' => $input['mods'][0]]);
        $response->assertJsonPath('url', route('preview.show', $response->json('id')));
    }

    public function test_session_user_owns_new_build_and_receives_one_initial_salute(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->postJson('/build', $this->input())->assertCreated();
        $loadout = Loadout::findOrFail($response->json('id'));
        $this->assertSame($user->id, $loadout->user_id);
        $this->assertEquals(1, $loadout->votes()->sum('value'));
        $this->assertSame(1, $loadout->votes()->count());
    }

    public function test_only_owner_can_update_and_relations_are_replaced(): void
    {
        $input = $this->input();
        $owner = User::factory()->create();
        $id = $this->actingAs($owner)->postJson('/build', $input)->json('id');
        $this->actingAs(User::factory()->create())->putJson('/build/'.$id, $input)->assertForbidden();
        $this->assertDatabaseHas('loadouts', ['id' => $id, 'name' => 'Test build']);
        $input['name'] = 'Updated build';
        $input['user_id'] = User::factory()->create()->id;
        $input['mods'] = [];
        $this->actingAs($owner)->putJson('/build/'.$id, $input)->assertOk();
        $this->assertDatabaseHas('loadouts', ['id' => $id, 'name' => 'Updated build', 'user_id' => $owner->id]);
        $this->assertDatabaseMissing('loadout_mod', ['loadout_id' => $id]);
        $this->assertSame(1, Loadout::findOrFail($id)->votes()->count());
    }

    public function test_guest_cannot_update_an_existing_build(): void
    {
        $loadout = Loadout::factory()->create(['character_id' => Character::factory()->create()->id]);
        $this->putJson('/build/'.$loadout->id, [])->assertUnauthorized();
    }

    public function test_owner_cannot_restore_a_hidden_build_through_the_public_builder(): void
    {
        $input = $this->input();
        $owner = User::factory()->create();
        $loadout = Loadout::factory()->for($owner, 'creator')->create([
            'character_id' => $input['character_id'],
            'is_hidden' => true,
        ]);

        $this->actingAs($owner)->putJson('/build/'.$loadout->id, $input + ['is_hidden' => false])->assertOk();
        $this->assertDatabaseHas('loadouts', [
            'id' => $loadout->id,
            'name' => $input['name'],
            'is_hidden' => true,
        ]);
    }

    public function test_invalid_selection_does_not_create_a_partial_build(): void
    {
        $input = $this->input();
        $input['character_id'] = Character::factory()->create(['name' => 'Scout'])->id;
        $this->postJson('/build', $input)->assertUnprocessable()->assertJsonValidationErrors('mods.0');
        $this->assertDatabaseCount('loadouts', 0);
    }

    public function test_two_mods_in_one_tier_are_rejected(): void
    {
        $input = $this->input();
        $mods = Character::findOrFail($input['character_id'])->guns->first()->mods;
        $tier = $mods->first()->mod_tier;
        $input['mods'] = $mods->where('mod_tier', $tier)->take(2)->pluck('id')->all();
        $this->postJson('/build', $input)->assertUnprocessable()->assertJsonValidationErrors('mods')
            ->assertJsonPath('errors.mods.0', 'Choose one mod per weapon tier.');
        $this->assertDatabaseCount('loadouts', 0);
    }

    public function test_two_weapons_in_the_same_slot_are_rejected(): void
    {
        $input = $this->input();
        app(GameDataCatalog::class)->sync(true, 'Breach Cutter');
        $character = Character::findOrFail($input['character_id']);
        $input['mods'][] = $character->guns()->where('name', 'Breach Cutter')->firstOrFail()->mods->first()->id;

        $this->postJson('/build', $input)->assertUnprocessable()->assertJsonValidationErrors('mods')
            ->assertJsonPath('errors.mods.0', 'Choose one weapon per slot.');
        $this->assertDatabaseCount('loadouts', 0);
    }

    public function test_two_overclocks_for_the_same_weapon_are_rejected(): void
    {
        $input = $this->input();
        $gun = Character::findOrFail($input['character_id'])->guns->first();
        $input['overclocks'] = $gun->overclocks->take(2)->modelKeys();

        $this->postJson('/build', $input)->assertUnprocessable()->assertJsonValidationErrors('overclocks')
            ->assertJsonPath('errors.overclocks.0', 'Choose one overclock per weapon.');
        $this->assertDatabaseCount('loadouts', 0);
    }

    public function test_low_level_builds_with_only_early_tiers_are_accepted(): void
    {
        $input = $this->input();
        $mods = Character::findOrFail($input['character_id'])->guns->first()->mods;
        $input['mods'] = [$mods->firstWhere('mod_tier', 1)->id, $mods->firstWhere('mod_tier', 2)->id];

        $this->postJson('/build', $input)->assertCreated();
    }

    public function test_weapon_upgrades_that_skip_tiers_are_accepted(): void
    {
        $input = $this->input();
        $mods = Character::findOrFail($input['character_id'])->guns->first()->mods;
        $input['mods'] = [$mods->firstWhere('mod_tier', 2)->id, $mods->firstWhere('mod_tier', 4)->id];

        $id = $this->postJson('/build', $input)->assertCreated()->json('id');
        $this->assertEqualsCanonicalizing($input['mods'], Loadout::findOrFail($id)->mods()->pluck('mods.id')->all());
    }

    public function test_a_weapon_needs_at_least_one_upgrade(): void
    {
        $input = $this->input();
        $gun = Character::findOrFail($input['character_id'])->guns->first();
        $input['mods'] = [];
        $input['overclocks'] = [$gun->overclocks->first()->id];

        $this->postJson('/build', $input)->assertUnprocessable()
            ->assertJsonPath('errors.mods.0', 'Choose at least one upgrade for each weapon.');
        $this->assertDatabaseCount('loadouts', 0);
    }

    public function test_equipment_upgrades_that_skip_tiers_are_accepted(): void
    {
        $input = $this->input();
        $equipment = Equipment::factory()->create(['character_id' => $input['character_id']]);
        $tierTwo = EquipmentMod::factory()->for($equipment)->create(['mod_tier' => 2]);
        $tierFour = EquipmentMod::factory()->for($equipment)->create(['mod_tier' => 4]);
        $input['equipment_mods'] = [$tierTwo->id, $tierFour->id];

        $id = $this->postJson('/build', $input)->assertCreated()->json('id');
        $this->assertEqualsCanonicalizing([$tierTwo->id, $tierFour->id], Loadout::findOrFail($id)->equipment_mods()->pluck('equipment_mods.id')->all());
    }

    public function test_required_fields_and_unknown_ids_are_rejected(): void
    {
        $this->postJson('/build', [])->assertUnprocessable()->assertJsonValidationErrors(['name', 'character_id', 'mods', 'overclocks', 'equipment_mods']);
        $input = $this->input();
        $input['mods'] = [999999];
        $this->postJson('/build', $input)->assertUnprocessable()->assertJsonValidationErrors('mods.0');
        $this->assertDatabaseCount('loadouts', 0);
    }

    public function test_builder_bootstrap_allows_copying_but_only_owners_update(): void
    {
        $input = $this->input();
        $owner = User::factory()->create();
        $id = $this->actingAs($owner)->postJson('/build', $input)->json('id');
        $this->get('/build/'.$id)->assertOk()->assertViewHas('builder', fn (array $builder): bool => $builder['method'] === 'PUT' && $builder['initial']['mods'] === $input['mods']);
        $this->actingAs(User::factory()->create())->get('/build/'.$id)->assertOk()->assertViewHas('builder', fn (array $builder): bool => $builder['method'] === 'POST');
        $this->get('/build/999999')->assertNotFound();
    }

    public function test_previews_render_without_vue_and_strip_unsafe_markdown(): void
    {
        $input = $this->input();
        $input['description'] = '# My guide'."\n\n".'<script>alert(1)</script>[unsafe](javascript:alert(1))';
        $id = $this->postJson('/build', $input)->json('id');
        $this->get('/preview/'.$id)->assertOk()->assertSeeText('Test build')->assertSeeText('My guide')
            ->assertSeeText('Shard Diffractor')->assertDontSee('<script>alert(1)</script>', false)
            ->assertDontSee('href="javascript:', false)->assertDontSee('/js/builder.js', false);
    }

    public function test_public_preview_renders_loadout_seo_metadata_and_main_heading(): void
    {
        $id = $this->postJson('/build', $this->input())->assertCreated()->json('id');
        $url = route('preview.show', $id);
        $description = 'Engineer build with Impact Splash';

        $this->get($url)->assertOk()->assertSeeHtml([
            '<title>Karl: Born Ready - Engineer - Test build</title>',
            '<meta name="description" content="'.$description.'">',
            '<meta name="keywords" content="Engineer build, Deep Rock Galactic builds, drg builds, Impact Splash build, Shard Diffractor build">',
            '<meta property="og:title" content="Engineer - Test build"',
            '<meta property="og:description" content="'.$description.'"',
            '<meta property="og:url" content="'.$url.'"',
            '<meta name="twitter:card" content="summary">',
            '<meta name="twitter:title" content="Engineer - Test build">',
            '<meta name="twitter:description" content="'.$description.'">',
            '<script type="application/ld+json">{"@context":"https://schema.org","@type":"WebPage","name":"Engineer - Test build","description":"'.$description.'"}</script>',
            '<h1 class="loadout-hero__name">Test build</h1>',
        ]);
    }

    public function test_preview_shows_the_patch_short_name_after_a_new_patch_is_released(): void
    {
        $this->freezeTime();
        $patch = Patch::factory()->create([
            'patch_num' => '39',
            'short_name' => 'Season 5',
            'launched_at' => now()->subDay(),
        ]);
        $loadout = Loadout::factory()->create([
            'character_id' => Character::factory()->create(['name' => 'Engineer'])->id,
            'user_id' => null,
            'patch_id' => $patch->id,
        ]);
        $url = route('preview.show', $loadout->id);

        $this->get($url)->assertOk()
            ->assertSeeText('· Current Patch')
            ->assertDontSeeText('This build may be outdated.');

        Patch::factory()->create(['launched_at' => now()]);

        $this->get($url)->assertOk()
            ->assertSeeText('· Season 5')
            ->assertSeeText('This build may be outdated.')
            ->assertDontSeeText('· Current Patch');
    }

    public function test_preview_falls_back_to_the_patch_number_without_a_short_name(): void
    {
        $patch = Patch::factory()->create([
            'patch_num' => '34',
            'short_name' => null,
            'launched_at' => now()->subYear(),
        ]);
        $loadout = Loadout::factory()->create([
            'character_id' => Character::factory()->create(['name' => 'Engineer'])->id,
            'user_id' => null,
            'patch_id' => $patch->id,
        ]);
        Patch::factory()->create(['launched_at' => now()]);

        $this->get(route('preview.show', $loadout->id))->assertOk()
            ->assertSeeText('· Patch 34');
    }

    public function test_salutes_toggle_using_session_authentication(): void
    {
        $input = $this->input();
        $id = $this->postJson('/build', $input)->json('id');
        $this->post('/build/'.$id.'/vote')->assertRedirect('/login');
        $this->actingAs(User::factory()->create())->post('/build/'.$id.'/vote')->assertRedirect('/preview/'.$id);
        $this->assertEquals(1, Loadout::findOrFail($id)->votes()->sum('value'));
        $this->post('/build/'.$id.'/vote')->assertRedirect('/preview/'.$id);
        $this->assertEquals(0, Loadout::findOrFail($id)->votes()->sum('value'));
        $this->assertSame(1, Loadout::findOrFail($id)->votes()->count());
    }

    public function test_metrics_distinguish_missing_data_from_invalid_combinations(): void
    {
        $input = $this->input();
        $gun = Character::findOrFail($input['character_id'])->guns->first();
        $this->getJson('/builder/metrics?gun_id='.$gun->id.'&combo=AAAAA-')->assertOk()->assertJsonPath('data', null);
        $this->getJson('/builder/metrics?gun_id='.$gun->id.'&combo=invalid')->assertUnprocessable()->assertJsonValidationErrors('combo');
        $this->getJson('/builder/catalog/999999')->assertNotFound();
    }

    public function test_builder_writes_require_csrf_tokens(): void
    {
        $input = $this->input();
        $this->app['env'] = 'local';
        $this->postJson('/build', $input)->assertStatus(419);
        $this->assertDatabaseCount('loadouts', 0);
    }

    /** @return array<string, mixed> */
    public function test_preview_shows_each_weapon_upgrade_code_with_open_tiers_dashed(): void
    {
        $engineer = Character::factory()->create(['name' => 'Engineer']);
        $gun = Gun::factory()->create(['name' => 'Warthog Auto 210', 'gun_class' => 'Shotgun', 'character_id' => $engineer->id]);
        $chosen = collect([[1, 'C'], [3, 'A'], [5, 'B']])->map(fn (array $pick): int => Mod::create([
            'character_id' => $engineer->id, 'gun_id' => $gun->id, 'mod_tier' => $pick[0], 'mod_index' => $pick[1],
            'mod_name' => 'Tier '.$pick[0].' upgrade', 'credits_cost' => 0, 'magnite_cost' => 0, 'bismor_cost' => 0,
            'umanite_cost' => 0, 'croppa_cost' => 0, 'enor_pearl_cost' => 0, 'jadiz_cost' => 0,
            'text_description' => '', 'json_stats' => '', 'icon' => '', 'mod_type' => '',
        ])->id);
        $loadout = Loadout::factory()->create(['character_id' => $engineer->id, 'user_id' => null]);
        $loadout->mods()->attach($chosen);

        $response = $this->get(route('preview.show', $loadout->id));

        $response->assertOk()->assertSeeHtml('<span class="sr-only">Upgrade code:</span><code class="kit-card__code" title="Upgrade code: type it into the compact builder">C–A–B</code>');
    }

    public function test_preview_kit_layout_defaults_to_compact_for_signed_in_players(): void
    {
        $input = $this->input();
        $overclock = Mod::find($input['mods'][0])->gun->overclocks->first();
        $input['overclocks'] = [$overclock->id];
        $url = route('preview.show', $this->postJson('/build', $input)->assertCreated()->json('id'));

        $this->get($url)->assertOk()
            ->assertSee('var view = false || (true && window.matchMedia', false)
            ->assertSee('aria-label="Weapons and gear layout"', false)
            ->assertSeeInOrder(['kit-card__oc-caption', $overclock->overclock_type.' overclock', $overclock->overclock_name], false);
        $this->actingAs(User::factory()->create())->get($url)->assertOk()
            ->assertSee('var view = true || (true && window.matchMedia', false);
    }

    public function test_preview_kit_layout_starts_detailed_for_search_crawlers_on_any_screen(): void
    {
        $url = route('preview.show', $this->postJson('/build', $this->input())->assertCreated()->json('id'));
        $googlebot = 'Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)';

        $this->withHeader('User-Agent', $googlebot)->get($url)->assertOk()
            ->assertSee('var view = false || (false && window.matchMedia', false);
    }

    public function test_preview_share_button_shares_the_preview_url_not_the_builder(): void
    {
        $engineer = Character::factory()->create(['name' => 'Engineer']);
        $loadout = Loadout::factory()->create(['character_id' => $engineer->id, 'user_id' => null]);

        $this->get(route('preview.show', $loadout->id))->assertOk()
            ->assertSee('data-share-url="'.route('preview.show', $loadout->id).'"', false);
    }

    private function input(): array
    {
        Patch::factory()->create(['launched_at' => now()->subDay()]);
        $character = Character::factory()->create(['name' => 'Engineer']);
        app(GameDataCatalog::class)->sync(true, 'Shard Diffractor');

        return ['name' => 'Test build', 'description' => 'A loadout', 'character_id' => $character->id,
            'throwable_id' => null, 'mods' => [$character->guns->first()->mods->first()->id], 'overclocks' => [], 'equipment_mods' => []];
    }
}
