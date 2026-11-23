<?php

namespace Tests\Feature;

use App\Models\Character;
use App\Models\Comment;
use App\Models\Loadout;
use App\Models\Patch;
use App\Models\User;
use ErrorException;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
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

    public function test_login_persists_in_the_database_across_requests(): void
    {
        config(['session.driver' => 'database']);
        $user = User::factory()->create();
        $cookieName = config('session.cookie');

        $response = $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect()
            ->assertCookie($cookieName);
        $sessionId = $response->getCookie($cookieName)->getValue();

        $this->assertDatabaseHas('sessions', ['id' => $sessionId, 'user_id' => $user->id]);

        Auth::forgetGuards();
        Session::forgetDrivers();
        $this->app->forgetInstance('session.store');

        $this->withCookie($cookieName, $sessionId)->get(route('account.settings'))->assertOk();
        $this->assertAuthenticatedAs($user, 'backpack');
    }

    public function test_guests_cannot_favorite_or_delete_loadouts_or_read_the_api_user()
    {
        $this->post('/build/1/favorite')->assertRedirect('/login');
        $this->delete('/build/delete/1')->assertRedirect('/login');
        $this->getJson('/api/user')->assertNotFound();
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

    public function test_retired_graphql_and_oauth_endpoints_are_unavailable(): void
    {
        $this->postJson('/graphql', [])->assertNotFound();
        $this->postJson('/oauth/token', [])->assertNotFound();
        $this->get('/settings/tokens')->assertNotFound();
    }

    public function test_fresh_install_has_no_retired_oauth_tables(): void
    {
        foreach (['oauth_refresh_tokens', 'oauth_access_tokens', 'oauth_auth_codes', 'oauth_device_codes', 'oauth_clients'] as $table) {
            $this->assertFalse(Schema::hasTable($table), "Retired OAuth table [{$table}] still exists.");
        }
    }

    public function test_upgrade_drops_the_legacy_personal_access_clients_table(): void
    {
        Schema::create('oauth_personal_access_clients', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('client_id');
        });
        $migration = require database_path('migrations/2026_11_20_004215_drop_retired_oauth_tables.php');

        $migration->up();

        $this->assertFalse(Schema::hasTable('oauth_personal_access_clients'));
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

    public function test_existing_comment_markup_renders_through_the_app_backend(): void
    {
        DB::table('comments')->insert([
            'page_id' => 'existing-thread',
            'content' => '<t>Legacy &amp; stable</t>',
            'permalink' => 'https://example.test/preview/1',
            'status' => Comment::STATUS_APPROVED,
            'created_at' => now(),
        ]);

        $this->getJson(route('comments.index', ['page_id' => 'existing-thread']))
            ->assertOk()
            ->assertJsonPath('comments.0.content', 'Legacy & stable')
            ->assertJsonPath('comments.0.content_html', 'Legacy &amp; stable');
    }

    public function test_loadout_pages_mount_the_comment_thread(): void
    {
        $loadout = $this->loadout(User::factory()->create());

        $html = $this->get(route('preview.show', $loadout->id))->assertOk()->getContent();

        $this->assertSame(1, preg_match('/data-comments="([^"]+)"/', $html, $matches));
        $config = json_decode(html_entity_decode($matches[1]), true, flags: JSON_THROW_ON_ERROR);
        $this->assertSame('loadout-'.$loadout->id, $config['pageId']);
        $this->assertFalse($config['loggedIn']);
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
