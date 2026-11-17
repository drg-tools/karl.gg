<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Passport;
use Tests\TestCase;

class PassportTokenTest extends TestCase
{
    use RefreshDatabase;

    public function test_personal_access_tokens_authenticate_the_api_and_can_be_revoked()
    {
        app(ClientRepository::class)->createPersonalAccessGrantClient('Test client', 'users');
        $user = User::factory()->create();
        $result = $user->createToken('Upgrade regression');

        $this->withToken($result->accessToken)->getJson('/api/user')
            ->assertOk()->assertJsonPath('id', $user->id)->assertJsonMissingPath('password');
        $result->token->revoke();
        auth()->forgetGuards();
        $this->withToken($result->accessToken)->getJson('/api/user')->assertUnauthorized();
    }

    public function test_legacy_client_migration_preserves_ids_owners_grants_and_secrets()
    {
        $this->createLegacyClientsTable();
        $owner = User::factory()->create();
        DB::table('oauth_clients')->insert([
            'id' => 42, 'user_id' => $owner->id, 'name' => 'Existing client',
            'secret' => 'existing-client-secret', 'provider' => 'users',
            'redirect' => 'https://example.com/callback', 'personal_access_client' => false,
            'password_client' => true, 'revoked' => false,
        ]);

        $migration = require database_path('migrations/2026_11_16_000001_upgrade_legacy_oauth_clients.php');
        $migration->up();
        $this->assertDatabaseHas('oauth_clients', ['id' => 42, 'owner_id' => $owner->id, 'owner_type' => User::class]);
        $client = Passport::client()->findOrFail(42);
        $this->assertSame(['https://example.com/callback'], $client->redirect_uris);
        $this->assertContains('password', $client->grant_types);
        $this->assertSame('existing-client-secret', $client->secret);

        $this->artisan('passport:hash', ['--force' => true])->assertSuccessful();
        $this->assertTrue(Hash::check('existing-client-secret', $client->fresh()->secret));
        $hashed = $client->fresh()->secret;
        $this->artisan('passport:hash', ['--force' => true])->assertSuccessful();
        $this->assertSame($hashed, $client->fresh()->secret);
    }

    public function test_migrated_personal_tokens_authenticate_when_user_and_client_ids_match(): void
    {
        $user = User::factory()->create();
        $this->createLegacyClientsTable();
        $this->insertLegacyClient($user->id, personalAccess: true);
        $this->upgradeLegacyClients();

        $result = $user->createToken('Migrated personal token');

        $this->withToken($result->accessToken)->getJson('/api/user')
            ->assertOk()->assertJsonPath('id', $user->id)->assertJsonMissingPath('password');
    }

    public function test_migrated_password_tokens_authenticate_when_user_and_client_ids_match(): void
    {
        $user = User::factory()->create();
        $this->createLegacyClientsTable();
        $this->insertLegacyClient($user->id, passwordGrant: true);
        $this->upgradeLegacyClients();
        $this->artisan('passport:hash', ['--force' => true])->assertSuccessful();

        $response = $this->postJson('/oauth/token', [
            'grant_type' => 'password', 'client_id' => $user->id,
            'client_secret' => 'existing-client-secret',
            'username' => $user->email, 'password' => 'password',
        ])->assertOk()->assertJsonStructure(['access_token', 'refresh_token']);

        $this->withToken($response->json('access_token'))->getJson('/api/user')
            ->assertOk()->assertJsonPath('id', $user->id);
    }

    public function test_migrated_machine_clients_keep_their_grant_without_authenticating_as_a_user(): void
    {
        $user = User::factory()->create();
        $this->createLegacyClientsTable();
        $this->insertLegacyClient($user->id);
        $this->upgradeLegacyClients();
        $this->artisan('passport:hash', ['--force' => true])->assertSuccessful();

        $response = $this->postJson('/oauth/token', [
            'grant_type' => 'client_credentials', 'client_id' => $user->id,
            'client_secret' => 'existing-client-secret',
        ])->assertOk()->assertJsonStructure(['access_token']);

        $this->withToken($response->json('access_token'))->getJson('/api/user')->assertUnauthorized();
    }

    private function createLegacyClientsTable(): void
    {
        Schema::drop('oauth_clients');
        Schema::create('oauth_clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('name');
            $table->string('secret')->nullable();
            $table->string('provider')->nullable();
            $table->text('redirect');
            $table->boolean('personal_access_client');
            $table->boolean('password_client');
            $table->boolean('revoked');
            $table->timestamps();
        });
    }

    private function insertLegacyClient(int $id, bool $personalAccess = false, bool $passwordGrant = false): void
    {
        DB::table('oauth_clients')->insert([
            'id' => $id, 'user_id' => null, 'name' => 'Existing client',
            'secret' => 'existing-client-secret', 'provider' => null,
            'redirect' => 'http://localhost', 'personal_access_client' => $personalAccess,
            'password_client' => $passwordGrant, 'revoked' => false,
        ]);
    }

    private function upgradeLegacyClients(): void
    {
        $migration = require database_path('migrations/2026_11_16_000001_upgrade_legacy_oauth_clients.php');
        $migration->up();
        $migration = require database_path('migrations/2026_11_17_212000_remove_client_credentials_from_user_oauth_clients.php');
        $migration->up();
    }
}
