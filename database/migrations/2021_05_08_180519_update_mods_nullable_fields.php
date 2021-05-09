<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateModsNullableFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mods', function (Blueprint $table) {
            $table->unsignedSmallInteger('credits_cost')->nullable()->change();
            $table->unsignedTinyInteger('magnite_cost')->nullable()->change();
            $table->unsignedTinyInteger('bismor_cost')->nullable()->change();
            $table->unsignedTinyInteger('umanite_cost')->nullable()->change();
            $table->unsignedTinyInteger('croppa_cost')->nullable()->change();
            $table->unsignedTinyInteger('enor_pearl_cost')->nullable()->change();
            $table->unsignedTinyInteger('jadiz_cost')->nullable()->change();
            $table->string('text_description', 1000)->nullable()->change();
            $table->string('json_stats', 1000)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mods', function (Blueprint $table) {
            $table->unsignedSmallInteger('credits_cost')->change();
            $table->unsignedTinyInteger('magnite_cost')->change();
            $table->unsignedTinyInteger('bismor_cost')->change();
            $table->unsignedTinyInteger('umanite_cost')->change();
            $table->unsignedTinyInteger('croppa_cost')->change();
            $table->unsignedTinyInteger('enor_pearl_cost')->change();
            $table->unsignedTinyInteger('jadiz_cost')->change();
            $table->string('text_description', 1000)->change();
            $table->string('json_stats', 1000)->change();
        });
    }
}
