<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingTimestamps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('overclocks', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('mods', function (Blueprint $table) {
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
        Schema::table('overclocks', function (Blueprint $table) {
            $table->dropTimestamps();
        });
        Schema::table('mods', function (Blueprint $table) {
            $table->dropTimestamps();
        });
    }
}
