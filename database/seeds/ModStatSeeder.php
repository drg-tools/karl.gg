<?php

use Illuminate\Database\Seeder;

class ModStatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql = file_get_contents(database_path().'/seeds/mods_stats_import.sql');

        DB::statement($sql);
    }
}
