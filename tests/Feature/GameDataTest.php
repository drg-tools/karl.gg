<?php

namespace Tests\Feature;

use App\Character;
use App\GameDataCatalog;
use App\Gun;
use App\Loadout;
use App\Mod;
use App\Overclock;
use App\Patch;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class GameDataTest extends TestCase
{
    use RefreshDatabase;

    public function test_preview_reports_new_records_without_writing_them(): void
    {
        Character::factory()->create(['name' => 'Engineer']);

        $this->artisan('app:sync-game-data', ['--weapon' => 'Shard Diffractor'])->expectsOutputToContain('"mode": "preview"')->assertSuccessful();

        $this->assertDatabaseCount('guns', 0);
        $this->assertDatabaseCount('mods', 0);
        $this->assertDatabaseCount('overclocks', 0);
    }

    public function test_apply_creates_a_complete_weapon_visible_to_the_builder(): void
    {
        $character = Character::factory()->create(['name' => 'Engineer']);

        $this->artisan('app:sync-game-data', ['--apply' => true, '--weapon' => 'Shard Diffractor'])->assertSuccessful();

        $this->assertDatabaseCount('guns', 1);
        $this->assertDatabaseCount('mods', 12);
        $this->assertDatabaseCount('overclocks', 6);
        $this->assertDatabaseHas('guns', ['character_id' => $character->id, 'name' => 'Shard Diffractor', 'character_slot' => 2]);
        $response = $this->postJson('/graphql', ['query' => '{character(id: '.$character->id.') {guns {name json_stats mods {mod_name json_stats} overclocks {overclock_name json_stats}}}}']);
        $response->assertOk();
        $this->assertNull($response->json('errors'), $response->getContent());
        $response->assertJsonPath('data.character.guns.0.json_stats.charge_up.value', 0.2);
        $response->assertJsonPath('data.character.guns.0.mods.5.json_stats.clip.value', 50);
        $response->assertJsonPath('data.character.guns.0.overclocks.1.json_stats.clip.multiply', true);
        $response->assertJsonPath('data.character.guns.0.overclocks.1.json_stats.clip.value', 0.8);
    }

    public function test_updates_preserve_ids_loadout_links_and_unknown_base_stats_and_are_repeatable(): void
    {
        Character::factory()->create(['name' => 'Engineer']);
        $catalog = app(GameDataCatalog::class);
        $catalog->sync(true, 'Shard Diffractor');
        $gun = Gun::firstOrFail();
        $stats = json_decode($gun->json_stats, true);
        $stats['charge_up']['value'] = 9;
        $stats['custom'] = ['name' => 'Custom stat', 'value' => 42];
        $gun->update(['json_stats' => json_encode($stats)]);
        $mod = $gun->mods()->firstOrFail();
        $mod->update(['text_description' => 'Outdated']);
        $overclock = $gun->overclocks()->firstOrFail();
        $overclock->update(['text_description' => 'Outdated']);
        $loadout = Loadout::factory()->create(['user_id' => User::factory()->create()->id, 'character_id' => $gun->character_id, 'patch_id' => Patch::factory()->create()->id]);
        $loadout->mods()->attach($mod);
        $loadout->overclocks()->attach($overclock);

        $catalog->sync(true, 'Shard Diffractor');

        $this->assertDatabaseCount('guns', 1);
        $this->assertDatabaseCount('mods', 12);
        $this->assertDatabaseCount('overclocks', 6);
        $this->assertDatabaseHas('loadout_mod', ['loadout_id' => $loadout->id, 'mod_id' => $mod->id]);
        $this->assertDatabaseHas('loadout_overclock', ['loadout_id' => $loadout->id, 'overclock_id' => $overclock->id]);
        $stats = json_decode($gun->fresh()->json_stats, true);
        $this->assertSame(0.2, $stats['charge_up']['value']);
        $this->assertSame(42, $stats['custom']['value']);
        $this->assertSame([], $catalog->sync(true, 'Shard Diffractor'));
    }

    public function test_slot_conflicts_roll_back_earlier_changes(): void
    {
        Character::factory()->create(['name' => 'Engineer']);
        app(GameDataCatalog::class)->sync(true, 'Shard Diffractor');
        $gun = Gun::firstOrFail();
        $gun->update(['gun_class' => 'Outdated']);
        $overclock = Overclock::where('overclock_index', 6)->firstOrFail();
        $overclock->update(['overclock_name' => 'Different overclock']);
        $mod = Mod::firstOrFail();
        $mod->update(['text_description' => 'Outdated']);

        $this->artisan('app:sync-game-data', ['--apply' => true, '--weapon' => 'Shard Diffractor'])->expectsOutputToContain('Slot conflict')->assertFailed();

        $this->assertDatabaseHas('guns', ['id' => $gun->id, 'gun_class' => 'Outdated']);
        $this->assertDatabaseHas('mods', ['id' => $mod->id, 'text_description' => 'Outdated']);
        $this->assertDatabaseHas('overclocks', ['id' => $overclock->id, 'overclock_name' => 'Different overclock']);
    }

    public function test_stat_key_conflicts_fail_without_changing_data(): void
    {
        Character::factory()->create(['name' => 'Engineer']);
        $gun = Gun::factory()->create(['name' => 'Shard Diffractor', 'gun_class' => 'Heavy Weapon', 'character_slot' => 2, 'json_stats' => '{"ex1":{"name":"Unrelated stat","value":99}}']);

        $this->artisan('app:sync-game-data', ['--apply' => true, '--weapon' => 'Shard Diffractor'])->expectsOutputToContain('Stat key conflict')->assertFailed();

        $this->assertDatabaseHas('guns', ['id' => $gun->id, 'json_stats' => '{"ex1":{"name":"Unrelated stat","value":99}}']);
        $this->assertDatabaseCount('mods', 0);
    }

    public function test_missing_character_prevents_import(): void
    {
        $this->artisan('app:sync-game-data', ['--apply' => true, '--weapon' => 'Shard Diffractor'])->expectsOutputToContain('Expected exactly one Engineer')->assertFailed();

        $this->assertDatabaseCount('guns', 0);
    }

    public function test_preview_of_existing_records_does_not_save_updates(): void
    {
        Character::factory()->create(['name' => 'Engineer']);
        app(GameDataCatalog::class)->sync(true, 'Shard Diffractor');
        $gun = Gun::firstOrFail();
        $gun->update(['gun_class' => 'Outdated']);

        $this->artisan('app:sync-game-data', ['--weapon' => 'Shard Diffractor'])->expectsOutputToContain('"before": "Outdated"')->assertSuccessful();

        $this->assertDatabaseHas('guns', ['id' => $gun->id, 'gun_class' => 'Outdated']);
    }

    public function test_duplicate_slots_cannot_silently_choose_an_existing_record(): void
    {
        Character::factory()->create(['name' => 'Engineer']);
        app(GameDataCatalog::class)->sync(true, 'Shard Diffractor');
        Overclock::firstOrFail()->replicate()->save();

        $this->artisan('app:sync-game-data', ['--apply' => true, '--weapon' => 'Shard Diffractor'])->expectsOutputToContain('Slot conflict')->assertFailed();

        $this->assertDatabaseCount('overclocks', 7);
    }

    public function test_unreviewed_upgrade_effects_are_not_silently_kept_or_deleted(): void
    {
        Character::factory()->create(['name' => 'Engineer']);
        app(GameDataCatalog::class)->sync(true, 'Shard Diffractor');
        $mod = Mod::firstOrFail();
        $stats = json_decode($mod->json_stats, true);
        $stats['custom'] = ['name' => 'Unknown effect', 'value' => 42];
        $mod->update(['json_stats' => json_encode($stats)]);

        $this->artisan('app:sync-game-data', ['--apply' => true, '--weapon' => 'Shard Diffractor'])->expectsOutputToContain('Unreviewed upgrade statistics')->assertFailed();

        $this->assertSame(42, json_decode($mod->fresh()->json_stats, true)['custom']['value']);
    }

    public function test_legacy_plastcrete_labels_update_without_replacing_the_overclock(): void
    {
        Character::factory()->create(['name' => 'Engineer']);
        app(GameDataCatalog::class)->sync(true, 'Shard Diffractor');
        $overclock = Overclock::where('overclock_name', 'Plastcrete Catalyst')->firstOrFail();
        $stats = json_decode($overclock->json_stats, true);
        $stats['ex12']['name'] = 'Endothermic Explosion';
        $overclock->update(['json_stats' => json_encode($stats)]);

        $this->artisan('app:sync-game-data', ['--apply' => true, '--weapon' => 'Shard Diffractor'])->assertSuccessful();

        $this->assertSame('Plastcrete Catalyst', json_decode($overclock->fresh()->json_stats, true)['ex12']['name']);
        $this->assertDatabaseCount('overclocks', 6);
    }

    public function test_audit_reports_missing_weapons_and_malformed_data_without_writes(): void
    {
        Character::factory()->create(['name' => 'Scout']);
        $gun = Gun::factory()->create(['name' => 'Deepcore GK2', 'gun_class' => 'Assault Rifle', 'json_stats' => 'invalid']);

        $this->assertSame(0, Artisan::call('app:audit-game-data'));
        $report = json_decode(Artisan::output(), true, flags: JSON_THROW_ON_ERROR);

        $this->assertContains('Gun #'.$gun->id.': invalid statistics JSON', $report['integrity_issues']);
        $this->assertContains('Deepcore GK2: missing tier 1', $report['integrity_issues']);
        $this->assertContains('Shard Diffractor', $report['missing_weapons']);
        $this->assertSame([], $report['numerical_review_required']);
        $this->assertCount(24, $report['curated_reference']['reviews']);

        $this->assertDatabaseHas('guns', ['id' => $gun->id, 'json_stats' => 'invalid']);
        $this->assertDatabaseCount('guns', 1);
    }

    public function test_full_catalog_import_resolves_characters_by_name_and_allows_repeated_upgrade_names_across_tiers(): void
    {
        foreach (['Scout', 'Gunner', 'Engineer', 'Driller'] as $name) {
            Character::factory()->create(['name' => $name]);
        }

        $this->artisan('app:sync-game-data', ['--apply' => true])->assertSuccessful();

        $this->assertDatabaseCount('guns', 24);
        $this->assertDatabaseCount('mods', 308);
        $this->assertDatabaseCount('overclocks', 160);
        $this->assertSame('Scout', Gun::where('name', 'Deepcore GK2')->firstOrFail()->character->name);
        $this->assertDatabaseHas('overclocks', ['overclock_name' => 'AI Stability Engine']);
        $stats = json_decode(Overclock::where('overclock_name', 'AI Stability Engine')->firstOrFail()->json_stats, true);
        $this->assertSame(9, $stats['ex10']['value']);
        $subata = Gun::where('name', 'Subata 120')->firstOrFail();
        $stats = json_decode($subata->overclocks()->where('overclock_name', 'Automatic Fire')->firstOrFail()->json_stats, true);
        $this->assertSame(3.5, $stats['recoil']['value']);
        $breach = Gun::where('name', 'Breach Cutter')->firstOrFail();
        $this->assertSame(1.5, json_decode($breach->json_stats, true)['ex2']['value']);
        $this->assertDatabaseHas('overclocks', ['gun_id' => $breach->id, 'overclock_name' => 'High Voltage Crossover', 'credits_cost' => 8250, 'magnite_cost' => 100, 'umanite_cost' => 0]);
        $this->assertSame([], app(GameDataCatalog::class)->sync(true));
    }

    public function test_reviewed_obsolete_modifier_is_removed_without_replacing_its_upgrade(): void
    {
        Character::factory()->create(['name' => 'Engineer']);
        app(GameDataCatalog::class)->sync(true, 'Deepcore 40MM PGL');
        $mod = Mod::where('mod_name', 'Homebrew Explosive')->firstOrFail();
        $stats = json_decode($mod->json_stats, true);
        $stats['ex8'] = ['name' => 'Direct Damage', 'value' => 1.1, 'multiply' => true];
        $mod->update(['json_stats' => json_encode($stats)]);

        $this->artisan('app:sync-game-data', ['--apply' => true, '--weapon' => 'Deepcore 40MM PGL'])->assertSuccessful();

        $this->assertModelExists($mod);
        $stats = json_decode($mod->fresh()->json_stats, true);
        $this->assertArrayNotHasKey('ex8', $stats);
        $this->assertSame(1.1, $stats['dmg']['value']);
    }

    public function test_modified_obsolete_effect_is_not_removed_without_review(): void
    {
        Character::factory()->create(['name' => 'Engineer']);
        app(GameDataCatalog::class)->sync(true, 'Deepcore 40MM PGL');
        $mod = Mod::where('mod_name', 'Homebrew Explosive')->firstOrFail();
        $stats = json_decode($mod->json_stats, true);
        $stats['ex8'] = ['name' => 'Direct Damage', 'value' => 2, 'multiply' => true];
        $mod->update(['json_stats' => json_encode($stats)]);

        $this->artisan('app:sync-game-data', ['--apply' => true, '--weapon' => 'Deepcore 40MM PGL'])->expectsOutputToContain('Removed statistic has changed')->assertFailed();

        $this->assertSame(2, json_decode($mod->fresh()->json_stats, true)['ex8']['value']);
    }

    public function test_unknown_weapon_filter_fails_without_importing_the_catalog(): void
    {
        $this->artisan('app:sync-game-data', ['--apply' => true, '--weapon' => 'Unknown'])->expectsOutputToContain('Unknown curated weapon')->assertFailed();

        $this->assertDatabaseCount('guns', 0);
    }

    public function test_missing_character_rolls_back_weapons_imported_earlier_in_the_catalog(): void
    {
        Character::factory()->create(['name' => 'Engineer']);

        $this->artisan('app:sync-game-data', ['--apply' => true])->expectsOutputToContain('Expected exactly one Scout')->assertFailed();

        $this->assertDatabaseCount('guns', 0);
        $this->assertDatabaseCount('mods', 0);
        $this->assertDatabaseCount('overclocks', 0);
    }
}
