<?php

namespace Tests\Feature;

use App\Character;
use App\Loadout;
use App\Patch;
use App\User;
use ErrorException;
use Hazzard\Comments\Comment;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

class UpgradeRegressionTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_rejects_invalid_credentials()
    {
        $user = User::factory()->create();

        $this->post('/login', ['email' => $user->email, 'password' => 'incorrect'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_login_and_logout_preserve_the_backpack_guard()
    {
        $user = User::factory()->create();

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect();
        $this->assertAuthenticatedAs($user, 'backpack');
        $this->post('/logout')->assertRedirect();
        $this->assertGuest('backpack');
    }

    public function test_password_reset_sends_a_notification()
    {
        Notification::fake();
        $user = User::factory()->create();

        $this->post('/password/email', ['email' => $user->email])->assertSessionHasNoErrors();
        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_guests_cannot_favorite_or_delete_loadouts_or_read_the_api_user()
    {
        $this->post('/build/1/favorite')->assertRedirect('/login');
        $this->delete('/build/delete/1')->assertRedirect('/login');
        $this->getJson('/api/user')->assertUnauthorized();
    }

    public function test_only_the_owner_can_delete_a_loadout()
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $loadout = $this->loadout($owner);

        $this->actingAs($other)->delete('/build/delete/'.$loadout->id)->assertForbidden();
        $this->assertDatabaseHas('loadouts', ['id' => $loadout->id]);
        $this->actingAs($owner)->delete('/build/delete/'.$loadout->id)->assertRedirect('/profile/'.$owner->id);
        $this->assertDatabaseMissing('loadouts', ['id' => $loadout->id]);
    }

    public function test_favorites_toggle_and_votes_remain_unique()
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

    public function test_graphql_reads_public_loadouts_and_guards_private_lists()
    {
        $loadout = $this->loadout(User::factory()->create());
        $response = $this->postJson('/graphql', ['query' => '{ loadout(id: '.$loadout->id.') { id name } }']);
        $this->assertNull($response->json('errors'), $response->getContent());
        $response->assertOk()
            ->assertJsonPath('data.loadout.name', $loadout->name);

        $this->postJson('/graphql', ['query' => '{ loadouts { data { id } } }'])
            ->assertOk()->assertJsonStructure(['errors']);
    }

    public function test_public_comments_hide_pending_comments_and_guests_cannot_moderate()
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

    public function test_admin_requires_authentication_and_permission()
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->actingAs(User::factory()->create(), 'backpack')
            ->getJson('/admin')->assertForbidden();
    }

    #[TestWith(['/'])]
    #[TestWith(['/browse'])]
    public function test_unrated_loadouts_render_without_deprecations(string $path): void
    {
        $loadout = $this->loadout(User::factory()->create());
        $loadout->forceFill(['name' => 'Unrated migration build', 'created_at' => now()])->save();

        set_error_handler(static function (int $severity, string $message, string $file, int $line): never {
            throw new ErrorException($message, 0, $severity, $file, $line);
        }, E_DEPRECATED);

        try {
            $this->get($path)->assertOk()->assertSeeText('Unrated migration build');
        } finally {
            restore_error_handler();
        }
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
