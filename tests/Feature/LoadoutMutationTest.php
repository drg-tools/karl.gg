<?php

namespace Tests\Feature;

use App\Character;
use App\Loadout;
use App\Patch;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class LoadoutMutationTest extends TestCase
{
    use RefreshDatabase;

    private function input(): array
    {
        Patch::factory()->create(['launched_at' => now()->subDay()]);

        return ['name' => 'Test build', 'description' => 'A loadout',
            'character_id' => Character::factory()->create()->id,
            'mods' => [], 'overclocks' => [], 'equipment_mods' => []];
    }

    public function testGuestCanCreateAnAnonymousLoadout()
    {
        $response = $this->postJson('/graphql', ['query' => 'mutation($input: LoadoutInput!) { createLoadout(input: $input) { id name } }',
            'variables' => ['input' => $this->input()]]);
        $this->assertNull($response->json('errors'), $response->getContent());
        $response->assertJsonPath('data.createLoadout.name', 'Test build');
        $this->assertDatabaseHas('loadouts', ['id' => $response->json('data.createLoadout.id'), 'user_id' => null]);
    }

    public function testOwnerCanUpdateButOtherUsersCannotUpdateOrDelete()
    {
        $input = $this->input();
        $owner = User::factory()->create();
        $loadout = Loadout::factory()->create(['user_id' => $owner->id, 'character_id' => $input['character_id']]);
        $query = 'mutation($id: Int!, $input: LoadoutInput!) { updateLoadout(id: $id, input: $input) { id name } }';
        $payload = ['query' => $query, 'variables' => ['id' => $loadout->id, 'input' => $input]];

        Passport::actingAs(User::factory()->create());
        $this->postJson('/graphql', $payload)->assertJsonStructure(['errors']);
        $this->postJson('/graphql', ['query' => 'mutation { deleteLoadout(id: '.$loadout->id.') { id } }'])
            ->assertJsonStructure(['errors']);
        $this->assertDatabaseHas('loadouts', ['id' => $loadout->id, 'name' => $loadout->name]);

        Passport::actingAs($owner);
        $response = $this->postJson('/graphql', $payload);
        $this->assertNull($response->json('errors'), $response->getContent());
        $response->assertJsonPath('data.updateLoadout.name', 'Test build');
        $this->assertDatabaseHas('loadouts', ['id' => $loadout->id, 'name' => 'Test build']);
    }
}
