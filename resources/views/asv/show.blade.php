@extends('layouts.app')

@section('content')

    <div class="featuredLoadoutsContainer pb-20 pt-3">
        <div class="flex flex-row lg:justify-start flex-wrap pl-10">
            <x-equipment-component
                :gun="$gun"
                :modMatrix="$modMatrix"
                :overclock="$overclock"
                class="asvCol">
            </x-equipment-component>

            <div class="md:pl-20 lg:pl-20 asvCol">
                <h3>Burst DPS</h3>
                <p>Ideal: {{round($buildMetrics->ideal_burst_dps, 4)}}</p>
                <p>Weakpoint: {{round($buildMetrics->burst_dps_wp, 4)}}</p>
                <p>Accuracy: {{round($buildMetrics->burst_dps_acc, 4)}}</p>
                <p>Armor Wasting: {{round($buildMetrics->burst_dps_aw, 4)}}</p>
                <p>Weakpoint + Accuracy: {{round($buildMetrics->burst_dps_wp_acc, 4)}}</p>
                <p>Weakpoint + Armor Wasting: {{round($buildMetrics->burst_dps_wp_aw, 4)}}</p>
                <p>Accuracy + Armor Wasting: {{round($buildMetrics->burst_dps_acc_aw, 4)}}</p>
                <p>All three opt-in flags enabled: {{round($buildMetrics->burst_dps_wp_acc_aw, 4)}}</p>
            </div>

            <div class="md:pl-20 lg:pl-20 asvCol">
                <h3>Sustained DPS</h3>
                <p>Ideal: {{round($buildMetrics->ideal_sustained_dps, 4)}}</p>
                <p>Weakpoint: {{round($buildMetrics->sustained_dps_wp, 4)}}</p>
                <p>Accuracy: {{round($buildMetrics->sustained_dps_acc, 4)}}</p>
                <p>Armor Wasting: {{round($buildMetrics->sustained_dps_aw, 4)}}</p>
                <p>Weakpoint + Accuracy: {{round($buildMetrics->sustained_dps_wp_acc, 4)}}</p>
                <p>Weakpoint + Armor Wasting: {{round($buildMetrics->sustained_dps_wp_aw, 4)}}</p>
                <p>Accuracy + Armor Wasting: {{round($buildMetrics->sustained_dps_acc_aw, 4)}}</p>
                <p>All three opt-in flags enabled: {{round($buildMetrics->sustained_dps_wp_acc_aw, 4)}}</p>
            </div>
        </div>

        <div class="flex flex-row pt-10">
            <div class="pl-10 asvCol">
                <h3>Misc</h3>
                <p>Additional Target DPS: {{round($buildMetrics->ideal_additional_target_dps, 4)}}</p>
                <p>Theoretical maximum number of targets hit: {{round($buildMetrics->max_num_targets_per_shot, 4)}}</p>
                <p>Theoretical maximum multi-target damage: {{round($buildMetrics->max_multi_target_damage, 4)}}</p>
                <p>Ammo Efficiency: {{round($buildMetrics->ammo_efficiency, 4)}}</p>
                <p>Percentage of Damage lost to Armor: {{round($buildMetrics->damage_wasted_by_armor, 4)}}</p>
                <p>General Accuracy percentage: {{round($buildMetrics->general_accuracy, 4)}}</p>
                <p>Weakpoint Accuracy Percentage: {{round($buildMetrics->weakpoint_accuracy, 4)}}</p>
                <p>How long it takes to expend all ammo: {{round($buildMetrics->firing_duration, 4)}}</p>
                <p>Average time to kill: {{round($buildMetrics->average_time_to_kill, 4)}}</p>
                <p>Average overkill percentage: {{round($buildMetrics->average_overkill, 4)}}</p>
                <p>Breakpoints Sum: {{round($buildMetrics->breakpoints, 4)}}</p>
                <p>Utility score: {{round($buildMetrics->utility, 4)}}</p>
                <p>Average time to ignite or freeze: {{round($buildMetrics->average_time_to_ignite_or_freeze, 4)}}</p>
                <p>Damage Per Magazine: {{round($buildMetrics->damage_per_magazine, 4)}}</p>
                <p>Time to fire Magazine: {{round($buildMetrics->time_to_fire_magazine, 4)}}</p>
            </div>
        </div>
    </div><!-- end overall equipment container -->

@endsection
