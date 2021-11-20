<template>
    <div class="flex flex-wrap my-5 gap-8">
        <div class="sm:w-1/2 w-full">
            <ClassPrimaries />
        </div>

        <div class="sm:w-1/2 w-full">
            <MainStatsDisplay
                :equipment="getSelectedPrimaryDetails"
                :overclock="selectedPrimaryOverclock"
                :mods="selectedPrimaryMods"
            />

            <div class="mt-4">
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

export default {
    name: "PrimaryBuilder",
    components: { MainStatsDisplay, BuildMetricsLink, ClassPrimaries },
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
