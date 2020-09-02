<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToLoadoutMods extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loadout_mod', function (Blueprint $table) {
            $table->foreign('loadout_id')
                ->references('id')
                ->on('loadouts')
                ->onDelete('cascade');

            $table->foreign('mod_id')
                ->references('id')
                ->on('mods')
                ->onDelete('cascade');
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
            $table->dropForeign('loadout_mod_loadout_id_foreign');
            $table->dropForeign('loadout_mod_mod_id_foreign');
        });
    }
}
