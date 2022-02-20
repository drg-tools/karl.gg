<template>
    <div class="flex sm:flex-nowrap flex-wrap my-5 gap-8">
        <div class="sm:w-1/2 w-full">
            <ClassSecondaries />
            <SelectedItemDisplay />
        </div>

        <div class="sm:w-1/2 w-full">
            <MainStatsDisplay
                :equipment="getSelectedSecondaryDetails"
                :overclock="selectedSecondaryOverclock"
                :mods="selectedSecondaryMods"
            />
            <div class="mt-4 sm:text-right text-center">
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
import SelectedItemDisplay from "./SelectedItemDisplay";

export default {
    name: "SecondaryBuilder",
    components: { ClassSecondaries, BuildMetricsLink, MainStatsDisplay, SelectedItemDisplay },
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

