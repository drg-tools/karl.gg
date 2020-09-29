<?php

namespace Database\Seeders;

use Backpack\PermissionManager\app\Models\Permission;
use Backpack\PermissionManager\app\Models\Role;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'Admin Panel Access']);
        // create roles and assign created permissions

        // this can be done as separate statements
        // $role = Role::create(['name' => 'Admin']);
        // $role->givePermissionTo('Admin Panel Access');

        $role = Role::create(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());
    }
}
