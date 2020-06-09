<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class)->create([
            'name' => 'admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('adminadmin')
        ])->assignRole('super-admin');
    }
}
