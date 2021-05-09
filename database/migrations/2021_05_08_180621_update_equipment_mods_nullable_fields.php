<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateEquipmentModsNullableFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('equipment_mods', function (Blueprint $table) {
            $table->string('description', 1000)->nullable()->change();
            $table->string('json_stats', 1000)->nullable()->change();
            $table->unsignedSmallInteger('credits_cost')->nullable()->change();
            $table->unsignedTinyInteger('magnite_cost')->nullable()->change();
            $table->unsignedTinyInteger('bismor_cost')->nullable()->change();
            $table->unsignedTinyInteger('umanite_cost')->nullable()->change();
            $table->unsignedTinyInteger('croppa_cost')->nullable()->change();
            $table->unsignedTinyInteger('enor_pearl_cost')->nullable()->change();
            $table->unsignedTinyInteger('jadiz_cost')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('equipment_mods', function (Blueprint $table) {
            $table->string('description', 1000)->change();
            $table->string('json_stats', 1000)->change();
            $table->unsignedSmallInteger('credits_cost')->change();
            $table->unsignedTinyInteger('magnite_cost')->change();
            $table->unsignedTinyInteger('bismor_cost')->change();
            $table->unsignedTinyInteger('umanite_cost')->change();
            $table->unsignedTinyInteger('croppa_cost')->change();
            $table->unsignedTinyInteger('enor_pearl_cost')->change();
            $table->unsignedTinyInteger('jadiz_cost')->change();
        });
    }
}
