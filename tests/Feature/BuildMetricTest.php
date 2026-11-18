<?php

namespace Tests\Feature;

use App\Character;
use App\Gun;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BuildMetricTest extends TestCase
{
    use RefreshDatabase;

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
