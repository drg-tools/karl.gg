<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OverclockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql = file_get_contents(database_path().'/seeds/overclocks_stats_import.sql');

        DB::statement($sql);
    }
}
