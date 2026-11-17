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
        // SQLite has no CREATE OR REPLACE VIEW; a fresh migration only needs CREATE.
        $createView = DB::connection()->getDriverName() === 'sqlite'
            ? 'CREATE VIEW'
            : 'CREATE OR REPLACE VIEW';

        DB::statement($createView.' gun_loadout as
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
        DB::statement('DROP VIEW IF EXISTS gun_loadout');
    }
}
