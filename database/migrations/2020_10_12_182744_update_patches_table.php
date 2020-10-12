<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('patches', function (Blueprint $table) {
            $table->timestamp('launched_at')->nullable();
        });

        Schema::table('equipment', function (Blueprint $table) {
            $table->dropColumn('patch_id');
        });
        Schema::table('equipment_mods', function (Blueprint $table) {
            $table->dropColumn('patch_id');
        });
        Schema::table('loadouts', function (Blueprint $table) {
            $table->dropColumn('patch_id');
        });
        Schema::table('mods', function (Blueprint $table) {
            $table->dropColumn('patch_id');
        });
        Schema::table('overclocks', function (Blueprint $table) {
            $table->dropColumn('patch_id');
        });
        Schema::table('throwables', function (Blueprint $table) {
            $table->dropColumn('patch_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patches', function (Blueprint $table) {
            $table->dropColumn('launched_at');
        });

        Schema::table('equipment', function (Blueprint $table) {
            $table->unsignedBigInteger('patch_id')->default(0);
        });
        Schema::table('equipment_mods', function (Blueprint $table) {
            $table->unsignedBigInteger('patch_id')->default(0);
        });
        Schema::table('loadouts', function (Blueprint $table) {
            $table->unsignedBigInteger('patch_id')->default(0);
        });
        Schema::table('mods', function (Blueprint $table) {
            $table->unsignedBigInteger('patch_id')->default(0);
        });
        Schema::table('overclocks', function (Blueprint $table) {
            $table->unsignedBigInteger('patch_id')->default(0);
        });
        Schema::table('throwables', function (Blueprint $table) {
            $table->unsignedBigInteger('patch_id')->default(0);
        });
    }
}
