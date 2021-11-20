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
