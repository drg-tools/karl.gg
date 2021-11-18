<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BuildMetricsShortNameLimits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('build_metrics', function (Blueprint $table) {
            $table->string('weapon_short_name')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('build_metrics', function (Blueprint $table) {
            $table->string('weapon_short_name', 20)->change();
        });
    }
}
