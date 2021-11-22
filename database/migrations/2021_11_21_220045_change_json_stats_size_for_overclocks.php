<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeJsonStatsSizeForOverclocks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('overclocks', function (Blueprint $table) {
            $table->string('json_stats', 2500)->change();
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
            $table->string('json_stats', 1000)->change();
        });
    }
}
