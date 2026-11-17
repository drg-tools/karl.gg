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
}
