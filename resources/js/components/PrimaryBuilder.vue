<template>
    <div class="flex sm:flex-nowrap flex-wrap my-5 gap-8">
        <div class="sm:w-1/2 w-full">
            <ClassPrimaries />
            <SelectedItemDisplay />
        </div>

        <div class="sm:w-1/2 w-full">
            <MainStatsDisplay
                :equipment="getSelectedPrimaryDetails"
                :overclock="selectedPrimaryOverclock"
                :mods="selectedPrimaryMods"
            />

            <div class="mt-4 sm:text-right text-center">
                <BuildMetricsLink :gun-id="getSelectedPrimary" :combo="selectedPrimaryBuildMetricsCombo" />
            </div>
        </div>
    </div>
</template>

<script>
import ClassPrimaries from "./ClassPrimaries";
import MainStatsDisplay from "./MainStatsDisplay";
import { mapGetters } from "vuex";
import BuildMetricsLink from "./BuildMetricsLink";
import SelectedItemDisplay from "./SelectedItemDisplay";

export default {
    name: "PrimaryBuilder",
    components: { MainStatsDisplay, BuildMetricsLink, ClassPrimaries, SelectedItemDisplay },
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
