@extends('layouts.asv')

@section('content')

    <div class="featuredLoadoutsContainer pb-20 pt-3">
        <div class="flex flex-row lg:justify-start flex-wrap pl-10">                        
            <x-equipment-component 
                :gun="$gun" 
                :gunIcon="$gunIcon" 
                :character="$character" 
                :modMatrix="$modMatrix" 
                :overclock="$overclock"
                class="asvCol">
            </x-equipment-component>

            <div class="md:pl-20 lg:pl-20 asvCol">
                <h3>Burst DPS</h3>
                <p>Ideal: {{$buildMetrics['ideal_burst_dps']}}</p>
                <p>Weakpoint: {{$buildMetrics['burst_dps_wp']}}</p>
                <p>Accuracy: {{$buildMetrics['burst_dps_acc']}}</p>
                <p>Armor Wasting: {{$buildMetrics['burst_dps_aw']}}</p>
                <p>Weakpoint + Accuracy: {{$buildMetrics['burst_dps_wp_acc']}}</p>
                <p>Weakpoint + Armor Wasting: {{$buildMetrics['burst_dps_wp_aw']}}</p>
                <p>Accuracy + Armor Wasting: {{$buildMetrics['burst_dps_acc_aw']}}</p>
                <p>All three opt-in flags enabled: {{$buildMetrics['burst_dps_wp_acc_aw']}}</p>
            </div>

            <div class="md:pl-20 lg:pl-20 asvCol">
                <h3>Sustained DPS</h3>
                <p>Ideal: {{$buildMetrics['ideal_sustained_dps']}}</p>
                <p>Weakpoint: {{$buildMetrics['sustained_dps_wp']}}</p>
                <p>Accuracy: {{$buildMetrics['sustained_dps_acc']}}</p>
                <p>Armor Wasting: {{$buildMetrics['sustained_dps_aw']}}</p>
                <p>Weakpoint + Accuracy: {{$buildMetrics['sustained_dps_wp_acc']}}</p>
                <p>Weakpoint + Armor Wasting: {{$buildMetrics['sustained_dps_wp_aw']}}</p>
                <p>Accuracy + Armor Wasting: {{$buildMetrics['sustained_dps_acc_aw']}}</p>
                <p>All three opt-in flags enabled: {{$buildMetrics['sustained_dps_wp_acc_aw']}}</p>
            </div>
        </div>

        <div class="flex flex-row pt-10">
            <div class="pl-10 asvCol">
                <h3>Misc</h3>
                <p>Additional Target DPS: {{$buildMetrics['ideal_additional_target_dps']}}</p>
                <p>Theoretical maximum number of targets hit: {{$buildMetrics['max_num_targets_per_shot']}}</p>
                <p>Theoretical maximum multi-target damage: {{$buildMetrics['max_multi_target_damage']}}</p>
                <p>Ammo Efficiency: {{$buildMetrics['ammo_efficiency']}}</p>
                <p>Percentage of Damage lost to Armor: {{$buildMetrics['damage_wasted_by_armor']}}</p>
                <p>General Accuracy percentage: {{$buildMetrics['general_accuracy']}}</p>
                <p>Weakpoint Accuracy Percentage: {{$buildMetrics['weakpoint_accuracy']}}</p>
                <p>How long it takes to expend all ammo: {{$buildMetrics['firing_duration']}}</p>
                <p>Average time to kill: {{$buildMetrics['average_time_to_kill']}}</p>
                <p>Average overkill percentage: {{$buildMetrics['average_overkill']}}</p>
                <p>Breakpoints Sum: {{$buildMetrics['breakpoints']}}</p>
                <p>Utility score: {{$buildMetrics['utility']}}</p>
                <p>Average time to ignite or freeze: {{$buildMetrics['average_time_to_ignite_or_freeze']}}</p>
                <p>Damage Per Magazine: {{$buildMetrics['damage_per_magazine']}}</p>
                <p>Time to fire Magazine: {{$buildMetrics['time_to_fire_magazine']}}</p>
            </div>
        </div>
        </div>
    </div><!-- end overall equipment container -->

@endsection