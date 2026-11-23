<?php

namespace Tests\Feature;

use App\Models\Character;
use App\Models\Gun;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BuilderDataTest extends TestCase
{
    use RefreshDatabase;

    public function test_builder_query_returns_weapon_statistics_for_guests(): void
    {
        $character = Character::factory()->create();
        Gun::factory()->create([
            'character_id' => $character->id,
            'gun_class' => 'Shotgun',
            'json_stats' => '{"damage":{"value":7}}',
        ]);
        $this->getJson('/builder/catalog/'.$character->id)->assertOk()
            ->assertJsonPath('data.guns.0.json_stats.damage.value', 7);
    }
}
