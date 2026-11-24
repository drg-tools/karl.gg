<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PulseDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_view_pulse(): void
    {
        $this->get(route('pulse'))->assertForbidden();
    }

    public function test_admin_panel_access_alone_does_not_grant_pulse_access(): void
    {
        $role = Role::findOrCreate('author', 'backpack');
        $role->givePermissionTo(Permission::findOrCreate('view-admin', 'backpack'));
        $user = User::factory()->create()->assignRole($role);

        $this->actingAs($user, 'backpack')->get(route('pulse'))->assertForbidden();
    }

    public function test_admins_with_stats_permission_can_view_pulse(): void
    {
        $role = Role::findOrCreate('super-admin', 'backpack');
        $role->givePermissionTo([
            Permission::findOrCreate('view-admin', 'backpack'),
            Permission::findOrCreate('manage-stats', 'backpack'),
        ]);
        $user = User::factory()->create()->assignRole($role);

        $this->actingAs($user, 'backpack')->get(route('pulse'))->assertOk();
    }
}
