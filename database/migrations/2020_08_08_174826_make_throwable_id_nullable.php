<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeThrowableIdNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loadouts', function (Blueprint $table) {
            $table->unsignedBigInteger('throwable_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loadouts', function (Blueprint $table) {
            $table->unsignedBigInteger('throwable_id')->change();
        });
    }
}
