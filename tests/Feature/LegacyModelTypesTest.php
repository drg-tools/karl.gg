<?php

namespace Tests\Feature;

use App\Models\Loadout;
use App\Models\User;
use App\Notifications\NotificationType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class LegacyModelTypesTest extends TestCase
{
    use RefreshDatabase;

    public function test_legacy_notifications_roles_and_permissions_resolve(): void
    {
        $user = User::factory()->create();
        $role = Role::findOrCreate('legacy role', 'web');
        $permission = Permission::findOrCreate('legacy permission', 'web');

        DB::table('notifications')->insert([
            'id' => (string) Str::uuid(),
            'type' => NotificationType::BuildComment->value,
            'notifiable_type' => 'App\User',
            'notifiable_id' => $user->id,
            'data' => '{}',
        ]);
        DB::table('model_has_roles')->insert([
            'role_id' => $role->id,
            'model_type' => 'App\User',
            'model_id' => $user->id,
        ]);
        DB::table('model_has_permissions')->insert([
            'permission_id' => $permission->id,
            'model_type' => 'App\User',
            'model_id' => $user->id,
        ]);

        $this->assertTrue($user->unreadNotifications()->sole()->notifiable->is($user));
        $this->assertTrue($user->hasRole($role));
        $this->assertTrue($user->hasDirectPermission($permission));
    }

    public function test_legacy_favorites_and_votes_resolve(): void
    {
        $loadout = Loadout::factory()->create();
        $user = User::factory()->create();

        DB::table('favorites')->insert([
            'user_id' => $user->id,
            'favoriteable_type' => 'App\Loadout',
            'favoriteable_id' => $loadout->id,
        ]);
        $user->upVote($loadout);

        $this->assertTrue($loadout->isFavorited($user->id));
        $this->assertTrue($user->favorite(Loadout::class)->sole()->is($loadout));
        $this->assertDatabaseHas('ratings', [
            'model_type' => 'App\User',
            'model_id' => $user->id,
            'rateable_type' => 'App\Loadout',
            'rateable_id' => $loadout->id,
        ]);
        $vote = $user->votes()->sole();
        $this->assertTrue($vote->rateable->is($loadout));
        $this->assertTrue($vote->model->is($user));
    }
}
