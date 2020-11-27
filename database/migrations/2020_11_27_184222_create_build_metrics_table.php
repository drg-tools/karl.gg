<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBuildMetricsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('build_metrics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('character_id');
            $table->unsignedBigInteger('gun_id');
            $table->string('weapon_short_name', 20);
            $table->string('build_combination', 6);
            $table->double('ideal_burst_dps', 12, 6);
            $table->double('burst_dps_wp', 12, 6);
            $table->double('burst_dps_acc', 12, 6);
            $table->double('burst_dps_aw', 12, 6);
            $table->double('burst_dps_wp_acc', 12, 6);
            $table->double('burst_dps_wp_aw', 12, 6);
            $table->double('burst_dps_acc_aw', 12, 6);
            $table->double('burst_dps_wp_acc_aw', 12, 6);
            $table->double('ideal_sustained_dps', 12, 6);
            $table->double('sustained_dps_wp', 12, 6);
            $table->double('sustained_dps_acc', 12, 6);
            $table->double('sustained_dps_aw', 12, 6);
            $table->double('sustained_dps_wp_acc', 12, 6);
            $table->double('sustained_dps_wp_aw', 12, 6);
            $table->double('sustained_dps_acc_aw', 12, 6);
            $table->double('sustained_dps_wp_acc_aw', 12, 6);
            $table->double('ideal_additional_target_dps', 12, 6);
            $table->double('max_num_targets_per_shot', 12, 6);
            $table->double('max_multi_target_damage', 12, 6);
            $table->double('ammo_efficiency', 12, 6);
            $table->double('damage_wasted_by_armor', 12, 6);
            $table->double('general_accuracy', 12, 6);
            $table->double('weakpoint_accuracy', 12, 6);
            $table->double('firing_duration', 12, 6);
            $table->double('average_time_to_kill', 12, 6);
            $table->double('average_overkill', 12, 6);
            $table->double('breakpoints', 12, 6);
            $table->double('utility', 12, 6);
            $table->double('average_time_to_ignite_or_freeze', 12, 6);
            $table->double('damage_per_magazine', 12, 6);
            $table->double('time_to_fire_magazine', 12, 6);
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
        Schema::dropIfExists('build_metrics');
    }
}
