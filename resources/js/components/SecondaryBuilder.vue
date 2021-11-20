<template>
    <div class="flex my-5 gap-8">
        <div class="w-1/2">
            <ClassSecondaries />
        </div>

        <div class="w-1/2">
            <MainStatsDisplay
                :equipment="getSelectedSecondaryDetails"
                :overclock="selectedSecondaryOverclock"
                :mods="selectedSecondaryMods"
            />
<!--            <BuildMetricsCard-->
<!--                v-if="getSelectedSecondary"-->
<!--                :weapon-id="getSelectedSecondary"-->
<!--                :combination="selectedSecondaryBuildMetricsCombo"-->
<!--            />-->
        </div>
    </div>
</template>

<script>
import ClassSecondaries from "./ClassSecondaries";
import BuildMetricsCard from "./BuildMetricsCard";
import { mapGetters } from "vuex";
import MainStatsDisplay from "./MainStatsDisplay";

export default {
    name: "SecondaryBuilder",
    components: { ClassSecondaries, BuildMetricsCard, MainStatsDisplay },
    computed: {
        ...mapGetters([
            "selectedSecondaryBuildMetricsCombo",
            "getSelectedSecondary",
            "getSelectedSecondaryDetails",
            "selectedSecondaryOverclock",
            "selectedSecondaryMods"
        ]),
    },
};
</script>

