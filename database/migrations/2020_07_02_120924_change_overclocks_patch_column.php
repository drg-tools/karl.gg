<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeOverclocksPatchColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('overclocks', function (Blueprint $table) {
            $table->renameColumn('patch_number_index', 'patch_id');
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
            $table->renameColumn('patch_number_index', 'patch_id');
        });
    }
}
