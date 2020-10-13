<?php

namespace Tests\Feature;

use App\Loadout;
use App\Patch;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoadoutTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testLoadoutGetsLatestPatchWhenCreated()
    {
        $patch = factory(Patch::class)->create([
           'launched_at' => Carbon::now()->subYear(),
        ]);

        $loadout = factory(Loadout::class)->create();

        $this->assertEquals($loadout->patch_id, $patch->id);
        $this->assertEquals($loadout->patch->patch_title, $patch->patch_title);
    }

    public function testLoadoutGetsLatestPatchWhenUpdating()
    {
        $oldPatch = factory(Patch::class)->create([
            'launched_at' => Carbon::now()->subYear(),
        ]);

        $loadout = factory(Loadout::class)->create([
            'patch_id' => $oldPatch->id,
        ]);

        $this->assertEquals($loadout->patch_id, $oldPatch->id);
        $this->assertEquals($loadout->patch->patch_title, $oldPatch->patch_title);

        // Create new patch and update
        $newPatch = factory(Patch::class)->create([
            'launched_at' => Carbon::now(),
        ]);

        $loadout->touch();
        $loadout->save();

        $this->assertEquals($loadout->patch_id, $newPatch->id);
        $this->assertEquals($loadout->patch->patch_title, $newPatch->patch_title);
    }
}
