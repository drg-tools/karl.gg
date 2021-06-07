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
        \App\Patch::factory()->count(20)->create();
    }
}
