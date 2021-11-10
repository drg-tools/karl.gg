<template>
    <div class="flex my-5 gap-8">

        <div class="w-1/2">
            <ClassPrimaries/>
        </div>

        <div class="w-1/2">
            <BuildMetricsCard :weapon-id="getSelectedPrimary" :combination="selectedPrimaryBuildMetricsCombo" />
        </div>
    </div>
</template>

<script>
import ClassPrimaries from "./ClassPrimaries";
import BuildMetricsCard from "./BuildMetricsCard";
import {mapGetters} from "vuex";

export default {
    name: "PrimaryBuilder",
    components: {BuildMetricsCard, ClassPrimaries},
    computed: {
        ...mapGetters([
            'selectedPrimaryBuildMetricsCombo',
            'getSelectedPrimary'
        ]),

    }
};
</script>
