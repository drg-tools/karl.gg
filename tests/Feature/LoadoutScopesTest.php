<?php

namespace Tests\Feature;

use App\Models\Loadout;
use App\Models\Patch;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoadoutScopesTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_on_older_patch_scope_grabs_old_loadouts()
    {
        // Order here matters because of our onSaved model event!
        $oldPatch = Patch::factory()->create([
            'launched_at' => Carbon::now()->subYear(),
        ]);
        $oldLoadouts = Loadout::factory()->count(3)->create([
            'patch_id' => '1',
        ]);

        $newPatch = Patch::factory()->create([
            'launched_at' => Carbon::now(),
        ]);
        $newLoadout = Loadout::factory()->count(2)->create([
            'patch_id' => $newPatch->id,
        ]);

        $loadouts = Loadout::onOlderPatch()->get();

        $this->assertEquals(3, $oldLoadouts->count());
        $this->assertEquals(2, $newLoadout->count());
        $this->assertEquals(3, $loadouts->count());
        $this->assertEquals($oldPatch->id, $loadouts->pluck('patch_id')->first());
    }
}
