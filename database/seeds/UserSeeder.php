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
            'name' => 'Chase',
            'email' => 'rawr@rawr.com',
            'password' => bcrypt('rawrrawr'),
        ]);
    }
}
