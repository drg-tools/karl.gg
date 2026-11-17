<?php

namespace Tests\Feature;

use App\Character;
use App\Loadout;
use App\Patch;
use App\User;
use Hazzard\Comments\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class UpgradeRegressionTest extends TestCase
{
    use RefreshDatabase;

    public function testLoginRejectsInvalidCredentials()
    {
        $user = User::factory()->create();

        $this->post('/login', ['email' => $user->email, 'password' => 'incorrect'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function testLoginAndLogoutPreserveTheBackpackGuard()
    {
        $user = User::factory()->create();

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect();
        $this->assertAuthenticatedAs($user, 'backpack');
        $this->post('/logout')->assertRedirect();
        $this->assertGuest('backpack');
    }

    public function testPasswordResetSendsANotification()
    {
        Notification::fake();
        $user = User::factory()->create();

        $this->post('/password/email', ['email' => $user->email])->assertSessionHasNoErrors();
        Notification::assertSentTo($user, \Illuminate\Auth\Notifications\ResetPassword::class);
    }

    public function testGuestsCannotFavoriteOrDeleteLoadoutsOrReadTheApiUser()
    {
        $this->post('/build/1/favorite')->assertRedirect('/login');
        $this->delete('/build/delete/1')->assertRedirect('/login');
        $this->getJson('/api/user')->assertUnauthorized();
    }

    public function testOnlyTheOwnerCanDeleteALoadout()
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $loadout = $this->loadout($owner);

        $this->actingAs($other)->delete('/build/delete/'.$loadout->id)->assertForbidden();
        $this->assertDatabaseHas('loadouts', ['id' => $loadout->id]);
        $this->actingAs($owner)->delete('/build/delete/'.$loadout->id)->assertRedirect('/profile/'.$owner->id);
        $this->assertDatabaseMissing('loadouts', ['id' => $loadout->id]);
    }

    public function testFavoritesToggleAndVotesRemainUnique()
    {
        $user = User::factory()->create();
        $loadout = $this->loadout($user);
        $this->actingAs($user);

        $this->post('/build/'.$loadout->id.'/favorite')->assertRedirect();
        $this->assertSame(1, $loadout->favorites()->count());
        $this->post('/build/'.$loadout->id.'/favorite')->assertRedirect();
        $this->assertSame(0, $loadout->favorites()->count());

        $user->upVote($loadout);
        $user->upVote($loadout);
        $this->assertSame(1, $loadout->votes()->count());
        $this->assertEquals(1, $loadout->votes()->sum('value'));
        $user->downVote($loadout);
        $this->assertSame(1, $loadout->votes()->count());
        $this->assertEquals(0, $loadout->votes()->sum('value'));
    }

    public function testGraphqlReadsPublicLoadoutsAndGuardsPrivateLists()
    {
        $loadout = $this->loadout(User::factory()->create());
        $response = $this->postJson('/graphql', ['query' => '{ loadout(id: '.$loadout->id.') { id name } }']);
        $this->assertNull($response->json('errors'), $response->getContent());
        $response->assertOk()
            ->assertJsonPath('data.loadout.name', $loadout->name);

        $this->postJson('/graphql', ['query' => '{ loadouts { data { id } } }'])
            ->assertOk()->assertJsonStructure(['errors']);
    }

    public function testPublicCommentsHidePendingCommentsAndGuestsCannotModerate()
    {
        Comment::unguard();
        try {
            Comment::create(['page_id' => 'upgrade-test', 'content' => 'Published', 'status' => 'approved']);
            $pending = Comment::create(['page_id' => 'upgrade-test', 'content' => 'Pending', 'status' => 'pending']);
        } finally {
            Comment::reguard();
        }

        $this->getJson('/comments?page_id=upgrade-test')->assertOk()->assertJsonCount(1, 'comments');
        $this->deleteJson('/comments/'.$pending->id)->assertUnauthorized();
        $this->assertDatabaseHas('comments', ['id' => $pending->id, 'status' => 'pending']);
    }

    public function testAdminRequiresAuthenticationAndPermission()
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->actingAs(User::factory()->create(), 'backpack')
            ->getJson('/admin')->assertForbidden();
    }

    private function loadout(User $owner): Loadout
    {
        Patch::factory()->create(['launched_at' => now()->subDay()]);

        return Loadout::factory()->create([
            'user_id' => $owner->id,
            'character_id' => Character::factory()->create()->id,
        ]);
    }
}
