<?php

use Illuminate\Database\Seeder;

class PatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Patch::class, 20)->create();
    }
}
