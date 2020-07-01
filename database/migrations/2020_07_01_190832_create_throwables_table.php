<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThrowablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('throwables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('character_id');
            $table->string('name', 50);
            $table->string('description', 1000);
            $table->string('json_stats', 1000);
            $table->unsignedSmallInteger('credits_cost');
            $table->unsignedTinyInteger('magnite_cost');
            $table->unsignedTinyInteger('bismor_cost');
            $table->unsignedTinyInteger('umanite_cost');
            $table->unsignedTinyInteger('croppa_cost');
            $table->unsignedTinyInteger('enor_pearl_cost');
            $table->unsignedTinyInteger('jadiz_cost');
            $table->string('icon', 1000);
            $table->unsignedBigInteger('patch_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('throwables');
    }
}
