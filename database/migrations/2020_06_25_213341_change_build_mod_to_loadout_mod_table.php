<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeBuildModToLoadoutModTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Rename the table from build_mod to loadout_mod
        Schema::rename('build_mod', 'loadout_mod');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loadout_mod', function (Blueprint $table) {
            // We do the opposite here so Laravel can reverse the migration
            Schema::rename('loadout_mod', 'build_mod');
        });
    }
}
