<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeBuildsToLoadoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Renaming Builds to Loadouts
        Schema::rename('builds', 'loadouts');
        Schema::table('loadouts', function (Blueprint $table) {
            // TODO: wire the connection to patches table. This is the interim sol'n
            $table->unsignedBigInteger('patch_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loadouts', function (Blueprint $table) {
            // We do the opposite here so Laravel can reverse the migration
            $table->dropColumn('patch_id');
        });
        Schema::rename('loadouts', 'builds');
    }
}
