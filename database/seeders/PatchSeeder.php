<?php

namespace Database\Seeders;

use App\Models\Patch;
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
        Patch::factory()->count(20)->create();
    }
}
