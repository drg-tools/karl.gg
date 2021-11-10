<template>
    <div class="flex my-5 gap-8">
        <div class="w-1/2">
            <ClassSecondaries/>
        </div>

        <div class="w-1/2">
            <BuildMetricsCard :weapon-id="getSelectedSecondary" :combination="selectedSecondaryBuildMetricsCombo" />
        </div>
    </div>
</template>

<script>
import ClassSecondaries from "./ClassSecondaries";
import BuildMetricsCard from "./BuildMetricsCard";
import {mapGetters} from "vuex";

export default {
    name: "SecondaryBuilder",
    components: {ClassSecondaries, BuildMetricsCard},
    computed: {
        ...mapGetters([
            'selectedSecondaryBuildMetricsCombo',
            'getSelectedSecondary'
        ]),

    }
};
</script>

