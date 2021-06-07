<?php

namespace Tests\Feature;

use App\Loadout;
use App\Patch;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoadoutNewPatchTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testLoadoutGetsLatestPatchWhenCreated()
    {
        $patch = Patch::factory()->create([
            'launched_at' => Carbon::now()->subYear(),
        ]);

        $loadout = Loadout::factory()->create();

        $this->assertEquals($loadout->patch_id, $patch->id);
        $this->assertEquals($loadout->patch->patch_title, $patch->patch_title);
    }

    public function testLoadoutGetsLatestPatchWhenUpdating()
    {
        $oldPatch = Patch::factory()->create([
            'launched_at' => Carbon::now()->subYear(),
        ]);

        $loadout = Loadout::factory()->create([
            'patch_id' => $oldPatch->id,
        ]);

        $this->assertEquals($loadout->patch_id, $oldPatch->id);
        $this->assertEquals($loadout->patch->patch_title, $oldPatch->patch_title);

        // Create new patch and update
        $newPatch = Patch::factory()->create([
            'launched_at' => Carbon::now(),
        ]);

        $loadout->touch();
        $loadout->save();

        $this->assertEquals($loadout->patch_id, $newPatch->id);
        $this->assertEquals($loadout->patch->patch_title, $newPatch->patch_title);
    }
}
