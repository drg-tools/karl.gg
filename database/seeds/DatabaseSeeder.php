<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CharacterSeeder::class);
        $this->call(GunSeeder::class);
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(OverclockSeeder::class);
        $this->call(ModStatSeeder::class);
        $this->call(EquipmentSeeder::class);
        $this->call(EquipmentModSeeder::class);
        $this->call(ThrowableSeeder::class);
        $this->call(LoadoutSeeder::class);
    }
}
