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
            <div class="mt-4">
                <BuildMetricsLink :gun-id="getSelectedSecondary" :combo="selectedSecondaryBuildMetricsCombo" />
            </div>
        </div>
    </div>
</template>

<script>
import ClassSecondaries from "./ClassSecondaries";
import BuildMetricsLink from "./BuildMetricsLink";
import { mapGetters } from "vuex";
import MainStatsDisplay from "./MainStatsDisplay";

export default {
    name: "SecondaryBuilder",
    components: { ClassSecondaries, BuildMetricsLink, MainStatsDisplay },
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

