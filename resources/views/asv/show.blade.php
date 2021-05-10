@extends('layouts.app')

@section('header')
    {{ $gun['name'] }}
@endsection

@section('styles')
    <style>
        /**
* Equipment Card Component
* */
        .asvEquipmentCardContainer {
            display: flex;
            flex-direction: column;
            flex-wrap: nowrap;
            width: 10rem;
        }
        .asvModMatrixContainer {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 1rem;
        }

        .asvModMatrixRow {
            display: flex;
            height: 1rem;
            width: 6rem;
            margin-bottom: 0.3rem;
        }

        .asvMod {
            width: 2rem;
            height: 100%;
            display: flex;
            justify-content: space-between;
        }

        .asvModActive {
            stroke: #fc9e00;
            fill: #fc9e00;
            stroke-width: 5px;
        }

        .asvModInactive {
            stroke: #fc9e00;
            fill: transparent;
            stroke-width: 5px;
        }

        .flexboxWeaponSelect {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 4rem;
        }

        .asvIcon {
            fill: #ada195;
            width: 180px;
        }

        .flexboxWeaponSelect > svg {
            margin: 0.5rem;
        }

        .overclockContainer {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .overclockName {
            margin-left: 0.4rem;
        }

        .baseButton {
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0.5rem 0 0.5rem 1rem;
            min-width: 8rem;
            height: 2.2rem;
            background: linear-gradient(90deg, #fc9e00 4%, rgba(0, 0, 0, 0) 4%, rgba(0, 0, 0, 0) 8%, #fc9e00 8%, #fc9e00 92%, rgba(0, 0, 0, 0) 92%, rgba(0, 0, 0, 0) 96%, #fc9e00 96%);
        }
        /* todo: base all hover effects on this one, like buttons ingame */
        .baseButton:hover {
            background: linear-gradient(90deg, #ffd200 4%, rgba(0, 0, 0, 0) 4%, rgba(0, 0, 0, 0) 8%, #ffd200 8%, #ffd200 92%, rgba(0, 0, 0, 0) 92%, rgba(0, 0, 0, 0) 96%, #ffd200 96%);
        }

        .baseButtonText {
            color: #000000;
        // font-size: 1.6rem;
            letter-spacing: 0.05rem;
            font-weight: bold;
            font-family: BebasNeue, sans-serif;
        }

    </style>
@endsection

@section('content')


    <div class="my-2">
        <a href="{{ url()->previous() }}" class="underline text-gray-300 ml-2 sm:ml-0">Back</a>
    </div>

    <div class="bg-gray-700 pb-20 text-white sm:rounded">
        @include('components.asv-alert')
        <div class="flex flex-col md:flex-row justify-between">
            <div class="mx-4 pt-2 text-gray-300 text-xl">
                <h3 class="mr-4">
                    @if($gun['character_slot'] == 1)
                        Primary Weapon
                    @elseif($gun['character_slot'] == 2)
                        Secondary Weapon
                    @else
                        Equipment
                    @endif
                </h3>
                <h3 class="text-gray-300">
                    {{ $combo }}
                    @if($modMatrix['selected_index'][6]['selected'])
                        / {{$overclock['overclock_name']}}
                    @endif
                </h3>
            </div>
        </div>


        <div class="grid grid-cols-1 md:grid-cols-2 md:gap-4">
            <div class="mx-auto mt-8">
                <x-equipment-component
                    :gun="$gun"
                    :modMatrix="$modMatrix"
                    :overclock="$overclock"
                    class="asvCol">
                </x-equipment-component>
            </div>

            <div>
                <div class="py-4">
                    <h3 class="mb-4">Burst DPS</h3>
                    <table class="table w-full md:w-10/12">
                        <tr>
                            <td>Ideal</td>
                            <td class="font-medium text-right">{{round($buildMetrics->ideal_burst_dps, 4)}}</td>
                        </tr>
                        <tr>
                            <td>Weakpoint</td>
                            <td class="font-medium text-right">{{round($buildMetrics->burst_dps_wp, 4)}}</td>
                        </tr>
                        <tr>
                            <td>Accuracy</td>
                            <td class="font-medium text-right">{{round($buildMetrics->burst_dps_acc, 4)}}</td>
                        </tr>
                        <tr>
                            <td>Armor Wasting</td>
                            <td class="font-medium text-right">{{round($buildMetrics->burst_dps_aw, 4)}}</td>
                        </tr>
                        <tr>
                            <td>Weakpoint + Accuracy</td>
                            <td class="font-medium text-right">{{round($buildMetrics->burst_dps_wp_acc, 4)}}</td>
                        </tr>
                        <tr>
                            <td>Weakpoint + Armor Wasting</td>
                            <td class="font-medium text-right">{{round($buildMetrics->burst_dps_wp_aw, 4)}}</td>
                        </tr>
                        <tr>
                            <td>Accuracy + Armor Wasting</td>
                            <td class="font-medium text-right">{{round($buildMetrics->burst_dps_acc_aw, 4)}}</td>
                        </tr>
                        <tr>
                            <td>All three opt-in flags enabled</td>
                            <td class="font-medium text-right">{{round($buildMetrics->burst_dps_wp_acc_aw, 4)}}</td>
                        </tr>
                    </table>
                </div>

                <div class="py-4">
                    <h3 class="mb-4">Sustained DPS</h3>
                    <table class="table w-full md:w-10/12">
                        <tr>
                            <td>Ideal</td>
                            <td class="font-medium text-right">{{round($buildMetrics->ideal_sustained_dps, 4)}}</td>
                        </tr>
                        <tr>
                            <td>Weakpoint</td>
                            <td class="font-medium text-right">{{round($buildMetrics->sustained_dps_wp, 4)}}</td>
                        </tr>
                        <tr>
                            <td>Accuracy</td>
                            <td class="font-medium text-right">{{round($buildMetrics->sustained_dps_acc, 4)}}</td>
                        </tr>
                        <tr>
                            <td>Armor Wasting</td>
                            <td class="font-medium text-right">{{round($buildMetrics->sustained_dps_aw, 4)}}</td>
                        </tr>
                        <tr>
                            <td>Weakpoint + Accuracy</td>
                            <td class="font-medium text-right">{{round($buildMetrics->sustained_dps_wp_acc, 4)}}</td>
                        </tr>
                        <tr>
                            <td>Weakpoint + Armor Wasting</td>
                            <td class="font-medium text-right">{{round($buildMetrics->sustained_dps_wp_aw, 4)}}</td>
                        </tr>
                        <tr>
                            <td>Accuracy + Armor Wasting</td>
                            <td class="font-medium text-right">{{round($buildMetrics->sustained_dps_acc_aw, 4)}}</td>
                        </tr>
                        <tr>
                            <td>All three opt-in flags enabled</td>
                            <td class="font-medium text-right">{{round($buildMetrics->sustained_dps_wp_acc_aw, 4)}}</td>
                        </tr>
                    </table>
                </div>

                <div class="py-4">
                    <h3 class="mb-4">Misc</h3>
                    <table class="table w-full md:w-10/12">
                        <tr>
                            <td>Additional Target DPS</td>
                            <td class="font-medium text-right">{{round($buildMetrics->ideal_additional_target_dps, 4)}}</td>
                        </tr>
                        <tr>
                            <td>Theoretical maximum number of targets hit</td>
                            <td class="font-medium text-right">{{round($buildMetrics->max_num_targets_per_shot, 4)}}</td>
                        </tr>
                        <tr>
                            <td>Theoretical maximum multi-target damage</td>
                            <td class="font-medium text-right">{{round($buildMetrics->max_multi_target_damage, 4)}}</td>
                        </tr>
                        <tr>
                            <td>Ammo Efficiency</td>
                            <td class="font-medium text-right">{{round($buildMetrics->ammo_efficiency, 4)}}</td>
                        </tr>
                        <tr>
                            <td>Percentage of Damage lost to Armor</td>
                            <td class="font-medium text-right">{{round($buildMetrics->damage_wasted_by_armor, 4)}}</td>
                        </tr>
                        <tr>
                            <td>General Accuracy percentage</td>
                            @if($buildMetrics->general_accuracy == -1)
                                <td class="font-medium text-sm text-right">Manually Aimed</td>
                            @else
                                <td class="font-medium text-right">{{round($buildMetrics->general_accuracy, 4)}}</td>
                            @endif
                        </tr>
                        <tr>
                            <td>Weakpoint Accuracy Percentage</td>
                            @if($buildMetrics->weakpoint_accuracy == -1)
                                <td class="font-medium text-sm text-right">Manually Aimed</td>
                            @else
                                <td class="font-medium text-right">{{round($buildMetrics->weakpoint_accuracy, 4)}}</td>
                            @endif
                        </tr>
                        <tr>
                            <td>How long it takes to expend all ammo</td>
                            <td class="font-medium text-right">{{round($buildMetrics->firing_duration, 4)}}</td>
                        </tr>
                        <tr>
                            <td>Average time to kill</td>
                            <td class="font-medium text-right">{{round($buildMetrics->average_time_to_kill, 4)}}</td>
                        </tr>
                        <tr>
                            <td>Average overkill percentage</td>
                            <td class="font-medium text-right">{{round($buildMetrics->average_overkill, 4)}}</td>
                        </tr>
                        <tr>
                            <td>Average time to ignite or freeze</td>
                            @if($buildMetrics->average_time_to_ignite_or_freeze == -1)
                                <td class="font-medium text-sm text-right">N/A</td>
                            @else
                                <td class="font-medium text-right">{{round($buildMetrics->average_time_to_ignite_or_freeze, 4)}}</td>
                            @endif
                        </tr>
                        <tr>
                            <td>Damage Per Magazine</td>
                            <td class="font-medium text-right">{{round($buildMetrics->damage_per_magazine, 4)}}</td>
                        </tr>
                        <tr>
                            <td>Time to fire Magazine</td>
                            <td class="font-medium text-right">{{round($buildMetrics->time_to_fire_magazine, 4)}}</td>
                        </tr>
                    </table>
                </div>
            </div>

        </div>
    </div><!-- end overall equipment container -->

@endsection
