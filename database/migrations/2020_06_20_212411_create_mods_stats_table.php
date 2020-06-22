<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModsStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mods_stats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('character_id');
            $table->unsignedBigInteger('gun_id');
            $table->unsignedTinyInteger('mod_tier');
            $table->string('mod_index', 1);
            $table->string('mod_name', 50);
            $table->unsignedSmallInteger('credits_cost');
            $table->unsignedTinyInteger('magnite_cost');
            $table->unsignedTinyInteger('bismor_cost');
            $table->unsignedTinyInteger('umanite_cost');
            $table->unsignedTinyInteger('croppa_cost');
            $table->unsignedTinyInteger('enor_pearl_cost');
            $table->unsignedTinyInteger('jadiz_cost');
            $table->string('text_description', 1000);
            $table->string('json_stats', 1000);
            $table->string('icon', 1000);
            $table->string('mod_type', 1000);
            $table->unsignedBigInteger('patch_number_index');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mods_stats');
    }
}
