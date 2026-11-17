<?php

namespace Tests\Feature;

use App\Character;
use App\Gun;
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
        // Exercise the actual client query so new selected fields cannot escape coverage.
        $source = file_get_contents(resource_path('js/apolloQueries.js'));
        preg_match('/character: \(id\) => `([^`]+)`/', $source, $matches);
        $query = str_replace('${id}', (string) $character->id, $matches[1]);

        $response = $this->postJson('/graphql', ['query' => $query]);
        $response->assertOk();
        $this->assertNull($response->json('errors'), $response->getContent());
        $response->assertJsonPath('data.character.guns.0.json_stats.damage.value', 7);
    }
}
