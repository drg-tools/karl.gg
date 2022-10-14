<template>
    <div class="flex sm:flex-nowrap flex-wrap my-5 gap-8">
        <div class="sm:w-1/2 w-full">
            <ClassThrowables />
            <SelectedItemDisplay />
        </div>

    </div>
</template>


<!-- TODO: Map throwables to all this stuff -->
<script>
import ClassThrowables from "./ClassThrowables";
import MainStatsDisplay from "./MainStatsDisplay";
import { mapGetters } from "vuex";
import BuildMetricsLink from "./BuildMetricsLink";
import SelectedItemDisplay from "./SelectedItemDisplay";

export default {
    name: "ThrowableBuilder",
    components: { MainStatsDisplay, BuildMetricsLink, ClassThrowables, SelectedItemDisplay },
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
