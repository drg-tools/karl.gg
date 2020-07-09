<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateLoadModBuildColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loadout_mod', function (Blueprint $table) {
            $table->renameColumn('build_id', 'loadout_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loadout_mod', function (Blueprint $table) {
            $table->renameColumn('loadout_id', 'build_id');
        });
    }
}
