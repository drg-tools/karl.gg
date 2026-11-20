<?php

namespace Tests\Feature;

use App\BuildMetric;
use App\Character;
use App\Gun;
use App\Overclock;
use App\Patch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class BuildMetricTest extends TestCase
{
    use RefreshDatabase;

    public function test_numeric_and_legacy_codes_resolve_to_the_same_metrics(): void
    {
        $character = Character::factory()->create();
        $gun = Gun::factory()->create(['character_id' => $character->id, 'gun_class' => 'Shotgun']);
        $patch = Patch::factory()->create();
        Overclock::create([
            'character_id' => $character->id,
            'gun_id' => $gun->id,
            'overclock_index' => 2,
            'overclock_name' => 'Test Overclock',
            'overclock_type' => 'Clean',
            'icon' => 'Icon_Overclock_ShotgunJump',
            'text_description' => '',
            'json_stats' => '',
            'credits_cost' => 0,
            'magnite_cost' => 0,
            'bismor_cost' => 0,
            'umanite_cost' => 0,
            'croppa_cost' => 0,
            'enor_pearl_cost' => 0,
            'jadiz_cost' => 0,
        ]);

        $attributes = [
            'character_id' => $character->id,
            'gun_id' => $gun->id,
            'patch_id' => $patch->id,
            'weapon_short_name' => 'Warthog',
            'build_combination' => 'AB-CA2',
        ];
        $metricColumns = array_diff(Schema::getColumnListing('build_metrics'), ['id'], array_keys($attributes));
        $metric = BuildMetric::create($attributes + array_fill_keys($metricColumns, 0));

        foreach (['AB-CA2', '12-312'] as $code) {
            $this->get(route('asv.show', [$gun->id, $code]))
                ->assertOk()
                ->assertSeeHtml('<code class="asv-combo">12-312</code>')
                ->assertSeeText('Test Overclock');

            $this->getJson('/builder/metrics?'.http_build_query(['gun_id' => $gun->id, 'combo' => $code]))
                ->assertOk()
                ->assertJsonPath('data.id', $metric->id);
        }
    }

    public function test_missing_metrics_show_an_explanation_for_an_existing_weapon(): void
    {
        $character = Character::factory()->create();
        $gun = Gun::factory()->create([
            'character_id' => $character->id,
            'gun_class' => 'Crossbow',
            'name' => 'Nishanka Boltshark X-80',
        ]);

        $this->get(route('asv.show', [$gun->id, '------']))
            ->assertOk()
            ->assertSee('Nishanka Boltshark X-80')
            ->assertSee('Advanced statistics are not available');
    }

    public function test_unknown_weapons_still_return_404(): void
    {
        $this->get(route('asv.show', [999999, '------']))->assertNotFound();
    }
}
