<?php

namespace Tests\Feature;

use App\Loadout;
use App\Patch;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoadoutScopesTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testOnOlderPatchScopeGrabsOldLoadouts()
    {
        // Order here matters because of our onSaved model event!
        $oldPatch = factory(Patch::class)->create([
            'launched_at' => Carbon::now()->subYear(),
        ]);
        $oldLoadouts = factory(Loadout::class, 3)->create([
            'patch_id' => '1',
        ]);

        $newPatch = factory(Patch::class)->create([
            'launched_at' => Carbon::now(),
        ]);
        $newLoadout = factory(Loadout::class, 2)->create([
            'patch_id' => $newPatch->id,
        ]);

        $loadouts = Loadout::onOlderPatch()->get();

        $this->assertEquals(3, $oldLoadouts->count());
        $this->assertEquals(2, $newLoadout->count());
        $this->assertEquals(3, $loadouts->count());
        $this->assertEquals($oldPatch->id, $loadouts->pluck('patch_id')->first());
    }
}
