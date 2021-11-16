<template>
    <div class="w-full">
        <div v-if="metrics && Object.keys(metrics).length > 0">
            <div class="py-4">
                <h2 class="mb-4 text-xl font-bold">Burst DPS</h2>
                <table class="table w-full md:w-10/12">
                    <tr>
                        <td>Ideal</td>
                        <td class="font-medium text-right">{{ metrics.ideal_burst_dps.toFixed(2) }}</td>
                    </tr>
                    <tr>
                        <td>Weakpoint</td>
                        <td class="font-medium text-right">{{ metrics.burst_dps_wp.toFixed(2) }}</td>
                    </tr>
                    <tr>
                        <td>Accuracy</td>
                        <td class="font-medium text-right">{{ metrics.burst_dps_acc.toFixed(2) }}</td>
                    </tr>
                    <tr>
                        <td>Armor Wasting</td>
                        <td class="font-medium text-right">{{ metrics.burst_dps_aw.toFixed(2) }}</td>
                    </tr>
                    <tr>
                        <td>Weakpoint + Accuracy</td>
                        <td class="font-medium text-right">{{ metrics.burst_dps_wp_acc.toFixed(2) }}</td>
                    </tr>
                    <tr>
                        <td>Weakpoint + Armor Wasting</td>
                        <td class="font-medium text-right">{{ metrics.burst_dps_wp_aw.toFixed(2) }}</td>
                    </tr>
                    <tr>
                        <td>Accuracy + Armor Wasting</td>
                        <td class="font-medium text-right">{{ metrics.burst_dps_acc_aw.toFixed(2) }}</td>
                    </tr>
                    <tr>
                        <td>All three opt-in flags enabled</td>
                        <td class="font-medium text-right">{{ metrics.burst_dps_wp_acc_aw.toFixed(2) }}</td>
                    </tr>
                </table>
            </div>

            <div class="py-4">
                <h2 class="mb-4 text-xl font-bold">Sustained DPS</h2>
                <table class="table w-full md:w-10/12">
                    <tr>
                        <td>Ideal</td>
                        <td class="font-medium text-right">{{ metrics.ideal_sustained_dps.toFixed(2) }}</td>
                    </tr>
                    <tr>
                        <td>Weakpoint</td>
                        <td class="font-medium text-right">{{ metrics.sustained_dps_wp.toFixed(2) }}</td>
                    </tr>
                    <tr>
                        <td>Accuracy</td>
                        <td class="font-medium text-right">{{ metrics.sustained_dps_acc.toFixed(2) }}</td>
                    </tr>
                    <tr>
                        <td>Armor Wasting</td>
                        <td class="font-medium text-right">{{ metrics.sustained_dps_aw.toFixed(2) }}</td>
                    </tr>
                    <tr>
                        <td>Weakpoint + Accuracy</td>
                        <td class="font-medium text-right">{{ metrics.sustained_dps_wp_acc.toFixed(2) }}</td>
                    </tr>
                    <tr>
                        <td>Weakpoint + Armor Wasting</td>
                        <td class="font-medium text-right">{{ metrics.sustained_dps_wp_aw.toFixed(2) }}</td>
                    </tr>
                    <tr>
                        <td>Accuracy + Armor Wasting</td>
                        <td class="font-medium text-right">{{ metrics.sustained_dps_acc_aw.toFixed(2) }}</td>
                    </tr>
                    <tr>
                        <td>All three opt-in flags enabled</td>
                        <td class="font-medium text-right">{{ metrics.sustained_dps_wp_acc_aw.toFixed(2) }}</td>
                    </tr>
                </table>
            </div>

            <div class="py-4">
                <h2 class="mb-4 text-xl font-bold">Misc</h2>
                <table class="table w-full md:w-10/12">
                    <tr>
                        <td>Additional Target DPS</td>
                        <td class="font-medium text-right">{{ metrics.ideal_additional_target_dps.toFixed(2) }}</td>
                    </tr>
                    <tr>
                        <td>Theoretical maximum number of targets hit</td>
                        <td class="font-medium text-right">{{ metrics.max_num_targets_per_shot.toFixed(2) }}</td>
                    </tr>
                    <tr>
                        <td>Theoretical maximum multi-target damage</td>
                        <td class="font-medium text-right">{{ metrics.max_multi_target_damage.toFixed(2) }}</td>
                    </tr>
                    <tr>
                        <td>Ammo Efficiency</td>
                        <td class="font-medium text-right">{{ metrics.ammo_efficiency.toFixed(2) }}</td>
                    </tr>
                    <tr>
                        <td>Percentage of Damage lost to Armor</td>
                        <td class="font-medium text-right">{{ metrics.damage_wasted_by_armor.toFixed(2) }}</td>
                    </tr>
                    <tr>
                        <td>General Accuracy percentage</td>
                        <td v-if="metrics.general_accuracy === -1" class="font-medium text-sm text-right">Manually
                            Aimed
                        </td>
                        <td v-else class="font-medium text-right">{{ metrics.general_accuracy.toFixed(2) }}</td>
                    </tr>
                    <tr>
                        <td>Weakpoint Accuracy Percentage</td>
                        <td v-if="metrics.weakpoint_accuracy === -1" class="font-medium text-sm text-right">Manually
                            Aimed
                        </td>
                        <td v-else class="font-medium text-right">{{ metrics.weakpoint_accuracy.toFixed(2) }}</td>
                    </tr>
                    <tr>
                        <td>How long it takes to expend all ammo</td>
                        <td class="font-medium text-right">{{ metrics.firing_duration.toFixed(2) }}</td>
                    </tr>
                    <tr>
                        <td>Average time to kill</td>
                        <td class="font-medium text-right">{{ metrics.average_time_to_kill.toFixed(2) }}</td>
                    </tr>
                    <tr>
                        <td>Average overkill percentage</td>
                        <td class="font-medium text-right">{{ metrics.average_overkill.toFixed(2) }}</td>
                    </tr>
                    <tr>
                        <td>Average time to ignite or freeze</td>
                        <td v-if="metrics.average_time_to_ignite_or_freeze === -1"
                            class="font-medium text-sm text-right">N/A
                        </td>
                        <td v-else class="font-medium text-right">{{ metrics.average_time_to_ignite_or_freeze.toFixed(2) }}</td>
                    </tr>
                    <tr>
                        <td>Damage Per Magazine</td>
                        <td class="font-medium text-right">{{ metrics.damage_per_magazine.toFixed(2) }}</td>
                    </tr>
                    <tr>
                        <td>Time to fire Magazine</td>
                        <td class="font-medium text-right">{{ metrics.time_to_fire_magazine.toFixed(2) }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</template>

<script>

import {Apollo} from "../apollo";
import apolloQueries from "../apolloQueries";
import {gql} from "apollo-boost";

export default {
    name: "BuildMetricsCard",
    props: {
        combination: String,
        weaponId: String
    },
    data() {
        return {
            metrics: {}
        }
    },
    methods: {
        async fetchData() {
            const resp = await Apollo.query({
                query: gql`
                    ${apolloQueries.getBuildMetricsByCombo(
                    this.combination,
                    parseInt(this.weaponId)
                )}
                `,
            });

            this.metrics = resp.data.buildMetricByCombo;
        }
    },
    created() {
        this.fetchData();
    },
    watch: {
        combination() {
            this.fetchData();
        },
        weaponId() {
            this.fetchData();
        }
    }
}
</script>

<style scoped>

</style>
