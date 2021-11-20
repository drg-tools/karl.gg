<template>
    <div class="flex my-5 gap-8">
        <div class="w-1/2">
            <ClassPrimaries />
        </div>

        <div class="w-1/2">
            <MainStatsDisplay
                :equipment="getSelectedPrimaryDetails"
                :overclock="selectedPrimaryOverclock"
                :mods="selectedPrimaryMods"
            />
            <!-- <BuildMetricsCard
                v-if="getSelectedPrimary"
                :weapon-id="getSelectedPrimary"
                :combination="selectedPrimaryBuildMetricsCombo"
            /> -->
        </div>
    </div>
</template>

<script>
import ClassPrimaries from "./ClassPrimaries";
import BuildMetricsCard from "./BuildMetricsCard";
import MainStatsDisplay from "./MainStatsDisplay";
import { mapGetters } from "vuex";

export default {
    name: "PrimaryBuilder",
    components: { MainStatsDisplay, BuildMetricsCard, ClassPrimaries },
    computed: {
        ...mapGetters([
            "selectedPrimaryBuildMetricsCombo",
            "getSelectedPrimary",
            "getSelectedPrimaryDetails",
            "selectedPrimaryOverclock",
            "selectedPrimaryMods",
        ]),
    },
};
</script>
