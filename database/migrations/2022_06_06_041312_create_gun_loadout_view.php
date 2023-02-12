<?php

use Illuminate\Database\Migrations\Migration;

class CreateGunLoadoutView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
            CREATE VIEW gun_loadout as
            SELECT DISTINCT
                m.gun_id,
                lm.loadout_id
            FROM
                loadout_mod AS lm
                JOIN mods AS m ON lm.mod_id = m.id
            GROUP BY
                lm.loadout_id,
                m.gun_id
            ORDER BY
                m.gun_id');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW gun_loadout');
    }
}
