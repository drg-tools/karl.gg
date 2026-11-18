<?php

namespace Tests\Feature;

use App\Loadout;
use App\Patch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BackfillLoadoutPatchesTest extends TestCase
{
    use RefreshDatabase;

    public function test_backfill_uses_creation_time_and_preserves_edit_timestamps(): void
    {
        $launchedAt = now()->subWeek()->startOfSecond();
        $editedAt = $launchedAt->copy()->addDay();
        $oldPatch = Patch::factory()->create(['launched_at' => $launchedAt->copy()->subYear()]);
        $currentPatch = Patch::factory()->create(['launched_at' => $launchedAt]);

        $olderLoadout = Loadout::factory()->createQuietly([
            'patch_id' => $oldPatch->id,
            'created_at' => $launchedAt->copy()->subSecond(),
            'updated_at' => $editedAt,
        ]);
        $launchLoadout = Loadout::factory()->createQuietly([
            'patch_id' => $oldPatch->id,
            'created_at' => $launchedAt,
            'updated_at' => $editedAt,
        ]);
        $untaggedLoadout = Loadout::factory()->createQuietly([
            'patch_id' => 0,
            'created_at' => $editedAt,
            'updated_at' => $editedAt,
        ]);
        Loadout::factory()->create([
            'created_at' => $editedAt,
            'updated_at' => $editedAt,
        ]);

        $this->artisan('app:backfill-loadout-patches')
            ->expectsOutputToContain('2 loadouts would be updated')
            ->assertSuccessful();
        $this->assertEquals($oldPatch->id, $launchLoadout->fresh()->patch_id);
        $this->assertEquals(0, $untaggedLoadout->fresh()->patch_id);

        $this->artisan('app:backfill-loadout-patches', ['--apply' => true])
            ->expectsOutput('Updated 2 loadouts.')
            ->assertSuccessful();

        $this->assertEquals($oldPatch->id, $olderLoadout->fresh()->patch_id);
        $this->assertEquals($currentPatch->id, $launchLoadout->fresh()->patch_id);
        $this->assertEquals($currentPatch->id, $untaggedLoadout->fresh()->patch_id);
        $this->assertEquals($editedAt, $launchLoadout->fresh()->updated_at);

        $this->artisan('app:backfill-loadout-patches', ['--apply' => true])
            ->expectsOutput('Updated 0 loadouts.')
            ->assertSuccessful();
    }

    public function test_backfill_requires_a_launched_patch(): void
    {
        $this->artisan('app:backfill-loadout-patches', ['--apply' => true])->assertFailed();

        Patch::factory()->create(['launched_at' => null]);
        $this->artisan('app:backfill-loadout-patches', ['--apply' => true])->assertFailed();

        Patch::factory()->create(['launched_at' => now()->addDay()]);
        $this->artisan('app:backfill-loadout-patches', ['--apply' => true])->assertFailed();
    }
}
