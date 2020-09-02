<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFkRefsToPivots extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loadout_equipment_mod', function (Blueprint $table) {
            $table->foreign('loadout_id')
                ->references('id')
                ->on('loadouts')
                ->onDelete('cascade');

            $table->foreign('equipment_mod_id')
                ->references('id')
                ->on('equipment_mods')
                ->onDelete('cascade');

        });

        Schema::table('loadout_overclock', function (Blueprint $table) {
            $table->foreign('loadout_id')
                ->references('id')
                ->on('loadouts')
                ->onDelete('cascade');

            $table->foreign('overclock_id')
                ->references('id')
                ->on('overclocks')
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
        Schema::table('loadout_equipment_mod', function (Blueprint $table) {
            $table->dropForeign('loadout_equipment_mod_loadout_id_foreign');
            $table->dropForeign('loadout_equipment_mod_equipment_mod_id_foreign');
        });
        Schema::table('loadout_overclock', function (Blueprint $table) {
            $table->dropForeign('loadout_overclock_loadout_id_foreign');
            $table->dropForeign('loadout_overclock_overclock_id_foreign');
        });
    }
}
