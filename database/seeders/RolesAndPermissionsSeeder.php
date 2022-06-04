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
        $viewAdmin = Permission::create(['name' => 'view-admin']);
        Permission::create(['name' => 'access-api']);
        Permission::create(['name' => 'manage-comments']);
        $managePosts = Permission::create(['name' => 'manage-posts']);
        Permission::create(['name' => 'manage-users']);
        Permission::create(['name' => 'manage-stats']);
        // create roles and assign created permissions

        // this can be done as separate statements
        // $role = Role::create(['name' => 'Admin']);
        // $role->givePermissionTo('Admin Panel Access');

        $author = Role::create(['name' => 'author']);
        $author->givePermissionTo([$managePosts, $viewAdmin]);

        $role = Role::create(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());
    }
}
